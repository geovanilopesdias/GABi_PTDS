<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/search_result.php');


final class BookSearchResults extends SearchResults{
    const SEARCH_TYPE = 'book';
    const GET_FIELDS = ['title', 'author', 'publisher', 'collection', 'cover_colors'];

    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::SEARCH_TYPE,
        array $get_fields = self::GET_FIELDS){
            parent::echo_structure($search_type, $get_fields);
    }

    protected function echo_table_results(){
        // Edit fetchers so always return bookcopy with opus and writer and editin
        // data by any field.
        if (!empty($_GET['asset_code'])){
            $code = htmlspecialchars(trim($_GET['asset_code']));
            $_SESSION['bookcopy'] = BookDAO::fetch_bookcopy_holistically_by_asset_code($code);
            header('Location: book_detail.php'); exit;
        }
        
        $results = array();
        $keywords = '';
        foreach(self::GET_FIELDS as $f){
            if(!empty($_GET[$f])){
                if ($f === 'cover_colors'){
                    $colors_list = explode(',', htmlspecialchars(trim($_GET[$f])));
                    $keywords .= implode(',', $colors_list);
                    foreach($colors_list as $color)
                        $copies = BookDAO::fetch_bookcopy_essentially_by($f, $color);
                }

                else {
                    $search = htmlspecialchars(trim($_GET[$f]));
                    $keywords .= $search . " ";
                    $copies = BookDAO::fetch_bookcopy_essentially_by($f, $search);
                }

                foreach ($copies as $c) if (!in_array($c['id'], $results, true)) $results[] = $c;
            }
        }
        
        // Disclaimer to no results:
        if (empty($results)){
            $disclaimer = "Que pena: nenhum livro encontrado..." ;
            echo InterfaceManager::no_results_disclaimer($disclaimer);
        }
        
        // Table build:
        else {
            $caption = "Resultados da busca por '$keywords'";
            echo InterfaceManager::table_of_results($caption, $results);
        }
    }
    
}

$results = new BookSearchResults();
$results -> echo_structure();

?>