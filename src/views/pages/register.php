<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');

abstract class Register{
    const PAGE_TYPE = 'register';
    
    protected abstract function echo_register_form();

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
   
    protected function echo_structure(string $register_type){
        session_start();
        if (!isset($_SESSION['user_id']) and $_SESSION['user_role'] !== 'librarian') {
            header('Location: login.php'); exit;
        }
        
        $title = "GABi | Registro de ".
            match($register_type){
                'user' => 'Usuários',
                'writer' => 'Autores',
                'opus' => 'Obras',
                'edition' => 'Edições',
                'bookcopy' => 'Exemplares',
                'loan' => 'Empréstimos',
                'classroom' => 'Turmas',
            };

        InterfaceManager::echo_html_head($title, self::PAGE_TYPE);
        echo "<div class='register_grid'>";
        self::echo_logo_back();
        $this -> echo_register_form();
        echo "</div>";
        InterfaceManager::echo_html_tail();
    }

    
}
?>