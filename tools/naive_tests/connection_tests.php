<?php 

require('../../src/controllers/book_dao.php');
require('../../src/controllers/people_dao.php');

//Testing for student insertion:
function insertion_test(){
    $data['edition_id'] = 3;
    $data['asset_code'] = '2024-1';
    // $data['edition_number'] = 12;
    // $data['collection_id'] = 1;
    // $data['translators'] = 'Xavier Pinheiro';
    // $data['pages'] = 176;
    // $data['publishing_year'] = 2017;
    // $data['isbn'] = '9788520941607';
    // $data['volume'] = 3;
    // $data['cover_colors'] = 'azul';
    return BookDAO::register_book_copy($data, 1);  // 1 should be the librarian id!
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
    return BookDAO::fetch_whole_bookshelf();
}

   
// $i = insertion_test();
// echo ($i) ? "Insertion sucessfull" : "Insertion failed";

// $u = updating_test();
// echo ($u) ? "Updating sucessfull" : "Updating failed";

// $d = exclusion_test();
// echo ($d) ? "Deleting sucessfull" : "Deleting failed";

$f = fetching_test();
echo ($f) ? print_r($f) : "No results";


?>