<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');

final class Login{
    const PAGE_TYPE = 'login';
    static function echo_login_form(){
        $form_data = $_SESSION['form_data'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['form_data'], $_SESSION['errors']);
        echo InterfaceManager::system_logo(self::PAGE_TYPE).
        "
        <div class='login_container'>
            <form class='login' action='login_manager.php' method='post'>
                <input type='text' name='login' placeholder='Login'/ autofocus required value='".htmlspecialchars($form_data['login'] ?? '')."'><br>".
                ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_login']) : '') ."

                <input type='password' name='passphrase' placeholder='Senha' required/><br>".
                ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_passphrase']) : '') ."
                
                <input type='submit' value='Acessar &#128214;'>
            </form>
        </div>
        ";
    }

    static function echo_structure(){
        $title = "&#128214; GABi: Gestor Aberto de Bibliotecas";
        InterfaceManager::echo_html_head($title, self::PAGE_TYPE);
        self::echo_login_form();
        InterfaceManager::echo_html_tail();
    }

    
}

Login::echo_structure();