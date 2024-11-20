<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');

session_start();

final class UserSearch{
    const PAGE_TYPE = 'searching';
    
    static function echo_logo_back(){
        echo "
            <div id='logo'>".
                InterfaceManager::system_logo(self::PAGE_TYPE).
            "</div>
            <div id='back_to_menu'>".
                InterfaceManager::back_to_menu_button().
            "</div>
        ";
    }

    static function echo_search_form(){
        $radio_options = [
            ['id' => 'all', 'content' => 'Todos'],
            ['id' => 'stu', 'content' => 'Estudantes'],
            ['id' => 'tea', 'content' => 'Professores'],
        ];

        echo "
            <div id='user_search_form'>
                <form class='search' action='user_search_result.php' method='get'>".
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

    static function echo_structure(){
        session_start();
        if (!isset($_SESSION['user_id']) and $_SESSION['user_role'] !== 'librarian') {
            header('Location: login.php'); exit;
        }
        $title = "GABi | Busca de Usuários";
        InterfaceManager::echo_html_head($title, self::PAGE_TYPE);
        echo "<div class='search_grid'>";
        self::echo_logo_back();
        self::echo_search_form();
        echo "</div>";
        InterfaceManager::echo_html_tail();
    }

    
}

UserSearch::echo_structure();