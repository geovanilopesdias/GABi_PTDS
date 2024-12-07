<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/loan_dao.php');
require_once(__DIR__ . '/search_result.php');


final class LoanHistoryReader extends SearchResults{
    const SEARCH_TYPE = 'loan';

    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::SEARCH_TYPE){
            parent::echo_structure($search_type);
    }

    protected function echo_table_results(){
        $loaner_id = intval(htmlspecialchars($_GET['user_id']));
        $loans = LoanDAO::fetch_loan_history_by_loaner_id($loaner_id);
                
        // Disclaimer to no results:
        if (empty($loans)){
            $disclaimer = "Nenhum empréstimo foi encontrado para ti!" ;
            echo InterfaceManager::no_results_disclaimer($disclaimer);
        }
        
        // Table build:
        else {
            $caption = "Histórico de empréstimos:";
            echo InterfaceManager::table_of_results(self::SEARCH_TYPE, $caption, $loans);
        }
    }
    
}

$results = new LoanHistoryReader();
$results -> echo_structure();

?>