<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/loan_dao.php');
require_once(__DIR__ . '/search_result.php');


final class LoanSearchResults extends SearchResults{
    const SEARCH_TYPE = 'loan';
    const GET_FIELDS = ['name', 'asset_code'];

    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::SEARCH_TYPE,
        array $get_fields = self::GET_FIELDS){
            parent::echo_structure($search_type, $get_fields);
    }

    protected function echo_table_results(){
        $open_only = isset($_GET['open_only']);
        $results = array();
        $keywords = '';
        foreach(self::GET_FIELDS as $f) {
            if (!empty($_GET[$f])){
                $value = htmlspecialchars(trim($_GET[$f]));
                $keywords .= "$value ";
                $loans = LoanDAO::fetch_loan_by($f, $value, $open_only);
                foreach ($loans as $l) if (!in_array($l['id'], $results, true)) $results[] = $l;
            }
        }
                
        // Disclaimer to no results:
        if (empty($results)){
            $disclaimer = "Puxa vida: nenhum empréstimo encontrado..." ;
            echo InterfaceManager::no_results_disclaimer($disclaimer);
        }
        
        // Table build:
        else {
            $caption = "Resultados da busca por '$keywords'";
            echo InterfaceManager::table_of_results(self::SEARCH_TYPE, $caption, $results);
        }
    }
    
}

$results = new LoanSearchResults();
$results -> echo_structure();

?>