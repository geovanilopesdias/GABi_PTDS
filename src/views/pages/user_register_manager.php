<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/manager.php');

final class UserRegisterManager extends ViewManager{
    const REGISTER_TYPE = 'user';
    const FAIL_TITLE = 'Cadastro recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de cadastro de usuário!';
    const PAGE_DATA = [
        'register_type' => 'user_register',
        'success_title' => 'Cadastro aceito',
        'success_message' => 'Cadastro de usuário realizado com sucesso'
    ];

    public function __construct() {}

    protected function operation_failed(
        string $error_detail, $errors = [],
        string $register_type = self::REGISTER_TYPE.'_register',
        string $fail_title = self::FAIL_TITLE,
        string $error_warning = self::ERROR_WARNING
        ){
            parent::operation_failed($error_detail, $errors, $register_type, $fail_title, $error_warning);
    }

    protected function operation_succeed($args = self::PAGE_DATA){
        parent::operation_succeed($args);
    }

    protected function persist_post_to_session($errors){
        $_SESSION['form_data'] = $_POST;
        $_SESSION['errors'] = $errors;
    }

    public function manage_post_variable(){
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = []; // Assume you validate the inputs here
            if (empty($errors)) $this->operation_succeed();
            else $this->operation_failed($errors, ''); // Elaborar mensagem de erro
        }
    }
    

}

$management = new UserRegisterManager();
$management -> manage_post_variable();   

?>