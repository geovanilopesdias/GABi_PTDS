<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');

abstract class SearchResults{
    const PAGE_TYPE = 'result_list';

    protected abstract function echo_table_results();

    protected function echo_structure(string $search_type){
        session_start();
        if (!isset($_SESSION['user_id'])) {header('Location: login.php'); exit;}   
        $title = "GABi | Busca por ";
        $title .= match($search_type){
                'user' => 'Leitores',
                'bookcopy' => 'Exemplares',
            };
        InterfaceManager::echo_html_head($title, self::PAGE_TYPE);
        echo "<div id='results_grid'>";
        self::echo_logo_back_buttons($search_type);
        $this -> echo_table_results();
        echo "</div>";
        InterfaceManager::echo_html_tail();
    }

    protected function echo_logo_back_buttons($search_type){
        echo "
            <div id='logo'>".
                InterfaceManager::system_logo(self::PAGE_TYPE)."
            </div>
            <div id='back_to_menu'>".
                InterfaceManager::back_to_menu_button()."
            </div>
            <div id='back_to_search'>".
                InterfaceManager::back_to_search_button($search_type)."
            </div>
        ";
    }
}

?>