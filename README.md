# vali-vali-validation
オリジナルのバリデーションチェックPHPです

# 対応しているバリデーション項目

## 必須項目チェック

`required`

## メールアドレスの妥当性チェック

`mail`

## 項目値の同一性チェック

`confirmed:***`

## 文字列の最大値チェック

`maxLength`

# 記述例

```post.php
<?php
require_once('Validation.php');

// バリデーション定義
// name => 項目名（input name）
// rule => バリデーションルール（関数名）※「maxLength」と「confirmed」は「:」の後に引数を付けること
$array = [
    [
        'name' => 'name',
        'rule' => [
            'required',
            'maxLength:10',
        ],
    ],
    [
        'name' => 'email',
        'rule' => [
            'required',
            'mail',
        ],
    ],
    [
        'name' => 'email_confirmation',
        'rule' => [
            'required',
            'confirmed:email',
        ],
    ],
];

$validation = new Validation($array);

// result
if ($validation->validate($_POST) === true) {
    echo 'success' . PHP_EOL;
}

var_dump($validation->validate($_POST));
exit;
```
