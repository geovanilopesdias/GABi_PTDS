<?php 

require('../../src/controllers/book_dao.php');
require('../../src/controllers/people_dao.php');

function insertion_test(){
    $data['isbn'] = '9788573075724';
    $data['opus_id'] = 4;
    $data['publisher_id'] = 1;
    $data['edition_number'] = 1;
    $data['pages'] = 304;
    $data['cover_colors'] = 'rosa, lilás';
    $data['publishing_year'] = 1999;
    $data['translators'] = 'Diana Myriam Lichtenstein, Liana Di Marco, Mário Corso';
    // $data['edition_number'] = 12;
    // $data['collection_id'] = 1;
    // $data['translators'] = 'Xavier Pinheiro';
    // $data['pages'] = 176;
    // $data['publishing_year'] = 2017;
    // $data['isbn'] = '9788520941607';
    // $data['volume'] = 3;
    // $data['cover_colors'] = 'azul';
    return BookDAO::register_edition($data, 1);  // 1 should be the librarian id!
}

function updating_test(){
    // $data['name'] = 'Artmedi';
    // $data['passphrase'] = 'brunocaio';
    $w = array(BookDAO::fetch_writer_by_id(1));
    return BookDAO::edit_all_opus_authorship($w, 3, 1);  // 1 should be the librarian id!
}

function exclusion_test(){
    return BookDAO::clear_opus_authorships(3, 1);  // 1 should be the librarian id!
}

function fetching_test(){
    // return BookDAO::fetch_all_opuses();
    // return BookDAO::fetch_all_writers();
    // return BookDAO::fetch_whole_bookshelf();
    return BookDAO::fetch_edition_with_opus_writer_data(1);
}

   
$i = insertion_test();
echo ($i) ? "Insertion sucessfull" : "Insertion failed";

// $u = updating_test();
// echo ($u) ? "\nUpdating sucessfull" : "\nUpdating failed";

// $d = exclusion_test();
// echo ($d) ? "Deleting sucessfull" : "Deleting failed";

// $f = fetching_test();
// echo ($f) ? "\n".print_r($f) : "\nNo results";


?>