<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/search.php');

final class UserSearch extends Search{
    const SEARCH_TYPE = 'user';
    
    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::SEARCH_TYPE){
            parent::echo_structure($search_type);
    }

    protected function echo_search_form(){
        $radio_options = [
            ['id' => 'all', 'content' => 'Todos'],
            ['id' => 'stu', 'content' => 'Estudantes'],
            ['id' => 'tea', 'content' => 'Professores'],
        ];

        echo "
            <div id='search_form'>
                <form class='search' action='".self::SEARCH_TYPE."_search_result.php' method='get'>".
                    InterfaceManager::input_radio_group(
                        $radio_options, 'radio_search_for', 'Buscar por:', 'radio')."
                    <input type='text' name='name' placeholder='Nome' autofocus/><br>".
                    InterfaceManager::search_input_disclaimer('Deixe vazio para buscar por toda a turma!').
                    InterfaceManager::classroom_selector(false).
                    InterfaceManager::search_input_disclaimer('A busca por nome ignora o campo de turma!').
                    InterfaceManager::search_button().
                "</form>
            </div>
        ";
    }
    
}

$search = new UserSearch();
$search -> echo_structure();

?>