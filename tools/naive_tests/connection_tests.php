<?php 

require('../src/controllers/people_dao.php');

//Testing for student insertion:
function insertion_test(){
    $data['name'] = 'Geovani Lopes Dias';
    $data['login'] = 'login';
    $data['phone'] = '51992380715';
    return PeopleDAO::register_student($data, 111);  //111 should be the librarian id!
}

function fetching_test(){
    return PeopleDAO::fetch_students_by_name('Geovani');
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
echo (insertion_test()) ? "Insertion sucessfull" : "Insertion failed";
echo (fetching_test()) ? var_dump($fetching_test()) : "No results";;

echo '</body> </html>';

?>