<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');

abstract class Menu{
    const PAGE_TYPE = 'menu';
    const IMAGE_DIR = '/code/src/views/images/';     
        
    protected abstract function echo_logo_greeting();
    protected abstract function echo_menu_table();

    public function echo_structure(string $menu_type){
        session_start();
        if (!isset($_SESSION['user_id']) and $_SESSION['user_role'] !== 'librarian') {
            header('Location: login.php'); exit;
        }
        $title = "GABi | ";
        $title .= match ($menu_type){
            'librarian' => 'Menu Bibliotecário',
            'student' => 'Menu Discente',
            'teacher' => 'Menu Docente',
            'book_register_options' => 'Cadastro de livros',
        };
        InterfaceManager::echo_html_head($title, self::PAGE_TYPE);
        echo "<div id='menu_grid'>";
        $this -> echo_logo_greeting();
        $this -> echo_menu_table();
        echo "</div>";
        InterfaceManager::echo_html_tail();
    }

    
}