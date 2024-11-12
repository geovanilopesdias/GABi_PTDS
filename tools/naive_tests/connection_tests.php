<?php 

require('../../src/controllers/people_dao.php');

//Testing for student insertion:
function insertion_test(){
    $data['name'] = 'Marili Musskopf';
    $data['login'] = 'mmusskopf';
    $data['passphrase'] = 'brunocaio';
    $data['phone'] = '51999997777';
    return PeopleDAO::register_non_loaner_teacher($data, 1);  //1 should be the librarian id!
}

function fetching_test(){
    return PeopleDAO::fetch_teachers_by_name('muss');
}

   
// $i = insertion_test();
// echo ($i) ? "Insertion sucessfull<br>" : "Insertion failed<br>";

$f = fetching_test();
echo ($f) ? var_dump($f) : "No results";;


?>