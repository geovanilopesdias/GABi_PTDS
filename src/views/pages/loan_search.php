<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/search.php');

final class LoanSearch extends Search{
    const SEARCH_TYPE = 'loan';
    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::SEARCH_TYPE){
            parent::echo_structure($search_type);
    }

    protected function echo_search_form(){

        echo "
            <div id='search_form'>
                <form class='search' action='".self::SEARCH_TYPE."_search_result.php' method='get'>".
                    InterfaceManager::input_checkbox_single('open_only', 'Buscar apenas empréstimos abertos')."</br>    
                    <input type='text' name='asset_code' placeholder='Patrimônio' autofocus /></br>
                    <input type='text' name='name' placeholder='Nome do leitor' /></br>".
                    InterfaceManager::search_button().
                "</form>
            </div>
        ";
    }

    
}


$search = new LoanSearch();
$search -> echo_structure();

?>