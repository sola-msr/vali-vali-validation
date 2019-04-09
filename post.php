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
