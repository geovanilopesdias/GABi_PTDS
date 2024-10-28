<?php 

require('../../src/controllers/people_dao.php');

//Testing for student insertion:
function insertion_test(){
    $data['name'] = 'Márcia Maria Etzberger Dias';
    $data['login'] = 'login';
    $data['phone'] = '51992380715';
    return PeopleDAO::register_student($data, 1);  //1 should be the librarian id!
}

function fetching_test(){
    return PeopleDAO::fetch_students_by_name('Maria');
}

   
$i = insertion_test();
$f = fetching_test();
echo ($i) ? "Insertion sucessfull" : "Insertion failed";
echo ($f) ? var_dump($fetching_test()) : "No results";;


?>