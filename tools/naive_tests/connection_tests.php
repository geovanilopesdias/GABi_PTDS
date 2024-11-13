<?php 

require('../../src/controllers/people_dao.php');

//Testing for student insertion:
function insertion_test(){
    // $data['id'] = 7;
    // $data['login'] = 'abeck';
    // $data['passphrase'] = 'abeck';
    $data['phone'] = '51988888888';
    // $data['teacher_id'] = 11;
    // $data['classroom_id'] = 1;
    return PeopleDAO::register_student($data, 1);  //1 should be the librarian id!
}

function updating_test(){
    return PeopleDAO::disallow_teacher_as_loaner(12, 1);  // 1 should be the librarian id!
}

function fetching_test(){
    return PeopleDAO::fetch_reader_by_id(10);
}

   
// $i = insertion_test();
// echo ($i) ? "Insertion sucessfull" : "Insertion failed";

$u = updating_test();
echo ($u) ? "Updating sucessfull" : "Updating failed";

$f = fetching_test();
echo ($f) ? var_dump($f) : "No results";;


?>