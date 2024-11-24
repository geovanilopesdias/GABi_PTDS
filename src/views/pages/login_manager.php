<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../managers/security_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/manager.php');

final class LoginManager extends ViewManager{
    const REGISTER_TYPE = 'login';
    const FAIL_TITLE = 'Login recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de login';
    const SUCCESS_TITLE = '';
    const SUCCESS_MESSAGE = '';

    public function __construct() {}

    protected function operation_failed(
        string $error_detail, $errors = [],
        string $register_type = self::REGISTER_TYPE.'_register',
        string $fail_title = self::FAIL_TITLE,
        string $error_warning = self::ERROR_WARNING){
            parent::operation_failed($error_detail, $errors, $register_type, $fail_title, $error_warning);
    }

    public function operation_succeed(mixed &$user){
        header('Location:'.$user -> get_role().'_menu.php');
        InterfaceManager::echo_html_tail();
        exit;
    }

    protected function persist_post_to_session($user){
        $user -> set_last_login(new DateTime('now', new DateTimeZone('America/Sao_Paulo'))); //Não funciona
        $_SESSION['user_id'] = $user -> get_id();
        $_SESSION['user_role'] = $user -> get_role();
        $_SESSION['user_name'] = ($user -> get_role() == 'teacher') ?
        'Prof. '.$user -> get_name() : $user -> get_name();
    }

    protected function handle_errors(){
        $user = self::get_user();
        if (is_null($user)) $this -> operation_failed('usuário informado não foi encontrado!');
        if (!SecurityManager::check_password($user, $_POST['passphrase']))
            $this -> operation_failed('senha informada está incorreta!');
        return $user;
    }

    public function manage_post_variable(){
        session_start();
        if (empty($_POST['login']) or empty($_POST['passphrase'])) 
            $this -> operation_failed('os campos login e senha precisam ser preenchidos');
    
        $user = $this -> handle_errors();    
        $this -> persist_post_to_session($user);
        $this->operation_succeed($user);
    }
    

}

$management = new LoginManager();
$management -> manage_post_variable();   

?>