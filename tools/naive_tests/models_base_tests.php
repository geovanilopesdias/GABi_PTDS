<?php 

require_once('../src/models/interactions/like.php');

function instance_test(){
    $data['synopsis_id'] = 123;
    $data['student_id'] = 234;
    return Like::fromArray($data);
}

function getters_test(Like $s){
    $fields_to_get = ['synopsis_id', 'synopsis_id'];
    foreach ($fields_to_get as $field) {
        $getterMethod =  'get_' . $field;
        echo $field . ':' . $s -> $getterMethod() . '</br>';
    }
}

echo '<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>';
    
$instance = instance_test();
echo getters_test($instance);
echo var_dump($instance -> toArray());

echo '</body> </html>';

?>