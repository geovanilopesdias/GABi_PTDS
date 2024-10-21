<?php 

require_once('../src/models/books/opus.php');

function instance_test(){
    $data['title'] = 'title';
    $data['original_year'] = 2003;
    $data['ddc'] = '333..6';
    $data['alternative_url'] = 'https://web';
    $data['cutter_sanborn'] = 'D234';
    return Opus::fromArray($data, true);
}

function getters_test(Opus $s){
    $fields_to_get = ['title', 'original_year', 'ddc',
                      'alternative_url', 'cutter_sanborn', 'id'];
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

echo '</body> </html>';

?>