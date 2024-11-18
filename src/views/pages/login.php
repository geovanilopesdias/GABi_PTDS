<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');

final class Login{
    static function echo_login_form(){
        echo "
        <div class='login_container'>
            <form class='login' action='login_manager.php' method='post'>
                <input type='text' name='login' name='class' placeholder='Login'/><br>
                <input type='password' name='passphrase' name='class' placeholder='Senha'/><br>
                <input type='submit' value='Acessar &#128214;'>
            </form>
        </div>
        ";
    }

    static function echo_structure(){
        $title = "&#128214; GABi: Gestor Aberto de Bibliotecas";
        InterfaceManager::echo_html_head($title, 'login');
        self::echo_login_form();
        InterfaceManager::echo_html_tail();
    }

    
}

Login::echo_structure();