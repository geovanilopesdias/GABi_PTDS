<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/search_result.php');


final class BookSearchResults extends SearchResults{
    const SEARCH_TYPE = 'bookcopy';
    const GET_FIELDS = ['title', 'writer', 'collection', 'cover_colors', 'asset_code'];

    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::SEARCH_TYPE){
            parent::echo_structure($search_type);
    }

    protected function echo_table_results(){
        if (!empty($_GET['asset_code'])){
            $code = htmlspecialchars(trim($_GET['asset_code']));
            $_SESSION['bookcopy'] = BookDAO::fetch_bookcopy_holistically_by_asset_code($code);
            header('Location: book_detail.php'); exit;
        }
    
        $results = [];
        $keywords = '';
    
        foreach (self::GET_FIELDS as $f) {
            if (!empty($_GET[$f])) {
                if ($f === 'cover_colors') {
                    $colors_list = array_map('trim', explode(',', $_GET[$f]));
                    $keywords .= implode(', ', $colors_list) . " ";
                    foreach ($colors_list as $color) {
                        $copies = BookDAO::fetch_bookcopy_essentially_by($f, $color);
                        if (!empty($copies)) {
                            foreach ($copies as $c) {
                                if (!in_array($c['id'], $results, true)) {
                                    $results[] = $c;
                                }
                            }
                        }
                    }
                } else {
                    $search = trim($_GET[$f]);
                    $keywords .= $search . " ";
                    $copies = BookDAO::fetch_bookcopy_essentially_by($f, $search);
                    if (!empty($copies)) {
                        foreach ($copies as $c) {
                            if (!in_array($c['id'], $results, true)) {
                                $results[] = $c;
                            }
                        }
                    }
                }
            }
        }
    
        if (empty($results)) {
            echo InterfaceManager::no_results_disclaimer("No results for: $keywords");
            return;
        }
    
        echo InterfaceManager::table_of_results(self::SEARCH_TYPE, "Results for: $keywords", $results);
    }
    
    
}

$results = new BookSearchResults();
$results -> echo_structure();

?>