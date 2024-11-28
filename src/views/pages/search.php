<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');

abstract class Search{
    const PAGE_TYPE = 'searching';
    
    protected abstract function echo_search_form();

    protected function echo_logo_back(): void{
        echo "
            <div id='logo'>".
                InterfaceManager::system_logo(self::PAGE_TYPE).
            "</div>
            <div id='back_to_menu'>".
                InterfaceManager::back_to_menu_button().
            "</div>
        ";
    }
   
    protected function echo_structure(string $search_type){
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php'); exit;
        }
        
        $title = "GABi | Busca de ".
            match($search_type){
                'user' => 'Leitores',
                'book' => 'Livros',
                'loan' => 'Empréstimos',
                'classroom' => 'Discentes de turmas',
            };

        InterfaceManager::echo_html_head($title, self::PAGE_TYPE);
        echo "<div class='search_grid'>";
        self::echo_logo_back();
        $this -> echo_search_form();
        echo "</div>";
        InterfaceManager::echo_html_tail();
    }

    
}
?>