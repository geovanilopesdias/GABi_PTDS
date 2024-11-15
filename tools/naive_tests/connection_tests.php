<?php 

require('../../src/controllers/book_dao.php');
require('../../src/controllers/people_dao.php');

//Testing for student insertion:
function insertion_test(){
    $data['name'] = 'A divina comédia';
    $data['publisher_id'] = 2; // Nova fronteira
    // $data['title'] = 'Psicogênese da Língua Escrita'; 
    // $data['original_year'] = 1984; 
    // $data['alternative_url'] = '';
    // $data['ddc'] = '370';
    return BookDAO::register_collection($data, 1);  // 1 should be the librarian id!
}

// function updating_test(){
//     $data['name'] = 'turma 111';
//     // $data['passphrase'] = 'brunocaio';
//     return PeopleDAO::edit_classroom(1, $data, 1);  // 1 should be the librarian id!
// }

// function exclusion_test(){
//     return PeopleDAO::delete_teaching(1, 10, 1);  // 1 should be the librarian id!
// }

function fetching_test(){
    // return BookDAO::fetch_all_opuses();
    // return BookDAO::fetch_all_writers();
    return BookDAO::fetch_all_collections();
}

   
$i = insertion_test();
echo ($i) ? "Insertion sucessfull" : "Insertion failed";

// $u = updating_test();
// echo ($u) ? "Updating sucessfull" : "Updating failed";

// $d = exclusion_test();
// echo ($d) ? "Deleting sucessfull" : "Deleting failed";

$f = fetching_test();
echo ($f) ? print_r($f) : "No results";


?>