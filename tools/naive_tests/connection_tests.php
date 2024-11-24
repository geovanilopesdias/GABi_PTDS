<?php 

require_once('../../src/controllers/loan_dao.php');
// require_once('../../src/controllers/book_dao.php');
// require_once('../../src/controllers/people_dao.php');

function insertion_test(){
    $data['book_copy_id'] = 2;
    $data['loaner_id'] = 13;
    // $data['opener_id'] = 1;
    $data['loan_date'] = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        
    return LoanDAO::register_loan($data, 1);  // 1 should be the librarian id!
}

function updating_test(){
    $data['passphrase'] = 'valfenda';
    return PeopleDAO::edit_reader(1, $data, 1);
}

function exclusion_test(){
    // return BookDAO::clear_opus_authorships(3, 1);  // 1 should be the librarian id!
}

function fetching_test(){
    return BookDAO::fetch_bookcopy_essentially_by('title', 'conc');
}

   
// $i = insertion_test();
// echo ($i) ? "Insertion sucessfull" : "Insertion failed";

// $u = updating_test();
// echo ($u) ? "\nUpdating sucessfull\n" : "\nUpdating failed\n";

// $d = exclusion_test();
// echo ($d) ? "Deleting sucessfull" : "Deleting failed";

$f = fetching_test();
echo ($f) ? "\n".print_r($f) : "\nNo results";


?>