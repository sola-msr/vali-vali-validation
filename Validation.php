<?php
class Validation
{
    private $items = [];
    private $post  = [];

    public function __construct($rules)
    {
        foreach ($rules as $key => $rule) {
            $this->items[$key]['name'] = $rule['name'];
            foreach ($rule['rule'] as $validationRule) {
                $this->items[$key]['rule'][] = $validationRule;
            }
        }
    }

    /**
     * リクエストのバリデートを行う
     *
     * @param array $post
     * @return true|array
     */
    public function validate($post)
    {
        $this->post = $post;

        $errors = [];
        for ($i = 0; $i < count($this->items); $i++ ) {

            foreach ($this->items[$i]['rule'] as $rule) {

                $ruleArray = explode(':', $rule);
                if (count($ruleArray) > 1 && $ruleArray[0] !== '' && $ruleArray[1] !== '') {

                    $function = $ruleArray[0];
                    if ($this->$function($post[$this->items[$i]['name']], $ruleArray[1]) === false) {
                        $errors[$i][] = $this->message($function, $this->items[$i]['name'], $ruleArray[1]);
                    }

                } else {

                    if ($this->$rule($post[$this->items[$i]['name']]) === false) {
                        $errors[$i][] = $this->message($rule, $this->items[$i]['name']);
                    }

                }
            }

        }

        if (count($errors) > 0 ) {
            return $errors;
        }

        return true;
    }

    /**
     * 必須項目
     *
     * @param string $requestValue
     * @return bool
     */
    public function required($requestValue)
    {
        return $requestValue !== "" ? true : false;
    }

    /**
     * メールアドレスの妥当性チェック
     *
     * @param string $requestValue
     * @return bool
     */
    public function mail($requestValue)
    {
        return preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $requestValue) !== 1 ? false : true;
    }

    /**
     * 項目値の同一性チェック
     *
     * @param string $requestValue
     * @return bool
     */
    public function confirmed($requestValue, $itemName)
    {
        return $this->post[$itemName] === $requestValue ? true : false;
    }

    /**
     * 文字列の最大値チェック
     *
     * @param string $requestValue
     * @param string $maxlength
     * @return bool
     */
    public function maxLength($requestValue, $maxlength)
    {
        return mb_strlen($requestValue) <= (int)$maxlength && (int)$maxlength !== 0 ? true : false;
    }

     /**
     * バリデーションエラー時のエラーメッセージ
     *
     * @param string $rule         バリデーションルール
     * @param string $item         項目名
     * @param string $genericValue 汎用値
     * @return array
     */
    public function message($rule, $item, $genericValue = '')
    {
        $messages = [
            'required'  => "{$item}は必須項目です",
            'mail'      => "{$item}には正しくメールアドレスを入力してください",
            'confirmed' => "{$item}には{$genericValue}と同じ値を入力してください",
            'maxLength' => "{$item}には{$genericValue}文字以下で入力してください",
        ];

        return $messages[$rule];
    }

}