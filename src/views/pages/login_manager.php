<?php
session_start();
require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../managers/security_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/form_manager.php');

final class LoginManager extends FormManager{
    const REGISTER_TYPE = 'login';
    const FAIL_TITLE = 'Acesso Negado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de login';
    const SUCCESS_TITLE = '';
    const SUCCESS_MESSAGE = '';

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    private function get_user(): ?Reader{
        return PeopleDAO::fetch_reader_by_login(trim(htmlspecialchars($_POST['login'] ?? '')));
    }

    protected function operation_failed(
        array $errors,
        string $register_type = self::REGISTER_TYPE,
        string $fail_title = self::FAIL_TITLE,
        string $error_warning = self::ERROR_WARNING)
            {parent::operation_failed($errors, $register_type, $fail_title, $error_warning);}

    public function operation_succeed(mixed &$user){
        $user_role = $user -> get_role();
        header('Location:'.$user_role.'_menu.php');
        exit;
    }

    protected function persist_post_to_session($errors){
        if (!empty($errors)) {
            $_SESSION['form_data'] = $_POST;
            $_SESSION['errors'] = $errors;
        }
        
        else {
            $user = self::get_user();
            $new_login = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
            PeopleDAO::update_reader_last_login($user -> get_id(), $new_login);
            $_SESSION['user_id'] = $user -> get_id();
            $_SESSION['user_role'] = $user -> get_role();
            $_SESSION['user_name'] = ($user -> get_role() == 'teacher') ?
            'Prof. '.$user -> get_name() : $user -> get_name();
        }
    }

    protected function handle_errors(){
        $errors = array();
        $user = self::get_user();
        if (is_null($user))
            {$errors['invalid_login'] = 'Login informado não consta no cadastro!';}
        else {
            if (!SecurityManager::check_password($user, $_POST['passphrase']))
            {$errors['invalid_passphrase'] = 'A senha informada está incorreta!';}
        }
        
        return $errors;
    }

    public function manage_post_variable(){
        parent::manage_post_variable();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this -> handle_errors(); 
            if (empty($errors)) {
                $user = self::get_user();
                $this -> persist_post_to_session($errors);
                $this -> operation_succeed($user);
            }
            else
                {$this->operation_failed($errors);}
        }
    }
}

$management = new LoginManager();
$management -> manage_post_variable();   

?>