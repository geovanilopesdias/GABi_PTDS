<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');

final class TeacherMenu{
    static function echo_menu_table(){
        echo "
        <h1>NÃO IMPLEMENTADO</h1>
        ";
    }

    static function echo_structure(){
        session_start();
        if (!isset($_SESSION['user_id']) or $_SESSION['user_role'] !== 'teacher') {
            header('Location: login.php'); exit;
        }
        $title = "GABi | Menu Professor";
        InterfaceManager::echo_html_head($title, 'menu');
        echo InterfaceManager::menu_greetings($_SESSION['user_id']);
        self::echo_menu_table();
        echo InterfaceManager::logout_button();
        InterfaceManager::echo_html_tail();
    }

    
}

TeacherMenu::echo_structure();