<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');

session_start();
    const PAGE_TYPE = 'login';

    function get_user(): ?Reader{
        return PeopleDAO::fetch_reader_by_login(trim(htmlspecialchars($_POST['login'] ?? '')));
    }

    /**
     * By now, is what it is; I'll change Reader set_passphrase to comport
     * password_hash after studying about peppering passwords.
     * https://www.php.net/manual/en/function.password-hash.php
    */
    function check_password(?Reader $user): bool{
        $post_passphrase_sha256 = hash('sha256', $_POST['passphrase']);
        return $user -> get_passphrase() === $post_passphrase_sha256;
    }

    function login_failed(string $error){
        InterfaceManager::echo_html_head('GABi | Login recusado', 'login');
        echo InterfaceManager::system_logo(PAGE_TYPE);
        echo '<h2>Algo deu errado com sua tentativa de login!</h2>';
        echo "<h4>O que houve: $error<h4>";
        echo '<h3>Em instantes, serás redirecionado à tela de Login</h3>';
        header('refresh:7; url=login.php');
        InterfaceManager::echo_html_tail();
        exit;
    }

    function login_succeed_for($user){
        InterfaceManager::echo_html_head("GABi | Login Autorizado", 'login');
        echo InterfaceManager::system_logo(PAGE_TYPE);
        echo '<h2>Login Autorizado!</h2>';
        header('Location:'.$user -> get_role().'_menu.php');
        InterfaceManager::echo_html_tail();
        exit;
    }
        
    // Error checking:
    if (empty($_POST['login']) or empty($_POST['passphrase'])) 
        login_failed('campos login e/ou senha estão vazios!');
    
    $user = get_user();
    if (is_null($user)) login_failed('usuário informado não foi encontrado!');
    if (!check_password($user)) login_failed('senha informada está incorreta!');
    
    // Creating the session and redirecting:
    $_SESSION['user_id'] = $user -> get_id();
    $_SESSION['user_role'] = $user -> get_role();
    $_SESSION['user_name'] = ($user -> get_role() == 'teacher') ?
        'Prof. '.$user -> get_name() : $user -> get_name();
    login_succeed_for($user);

    

?>