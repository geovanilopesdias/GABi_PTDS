<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');

final class Login{
    static function echo_structure(){
        $title = "&#128214;GABi: Gestor Aberto de Bibliotecas";
        InterfaceManager::echo_html_head($title, 'login');
        InterfaceManager::echo_html_tail();
    }

    
}

Login::echo_structure();