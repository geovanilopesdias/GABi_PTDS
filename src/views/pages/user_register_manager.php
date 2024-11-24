<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/manager.php');

final class UserRegisterManager extends ViewManager{
    const REGISTER_TYPE = 'user';
    const FAIL_TITLE = 'Cadastro recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de cadastro de usuário!';

    public function __construct() {}

    protected function operation_failed(
        string $error_detail, $errors = [],
        string $register_type = self::REGISTER_TYPE.'_register',
        string $fail_title = self::FAIL_TITLE,
        string $error_warning = self::ERROR_WARNING
        ){
            parent::operation_failed($error_detail, $errors, $register_type, $fail_title, $error_warning);
    }

    protected function operation_succeed($args){
        try{
            $reader_data = $args['reader_data'];   

            $classroom_id = htmlspecialchars($_POST['classroom']);
            if ($_SESSION['role'] === 'student'){
                PeopleDAO::register_student($reader_data, $_SESSION['user_id']);
                $student_fetched = PeopleDAO::fetch_reader_by_login($reader_data['login']);
                PeopleDAO::register_enrollment(
                    ['student_id' => $student_fetched['id'], 'classroom_id' => $classroom_id],
                    $_SESSION['user_id']);
            }
            else {
                if (isset($_SESSION['can_loan']))
                    PeopleDAO::register_loaner_teacher($reader_data, $_SESSION['user_id']);
                else
                    PeopleDAO::register_non_loaner_teacher($reader_data, $_SESSION['user_id']);
                
                $teacher_fetched = PeopleDAO::fetch_reader_by_login($reader_data['login']);
                PeopleDAO::register_teaching(
                    ['teacher_id' => $teacher_fetched['id'], 'classroom_id' => $classroom_id],
                    $_SESSION['user_id']);
            }

        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro em nosso sistema!\n".
            $e -> getMessage();
        }
    }

    protected function persist_post_to_session($errors){
        $_SESSION['form_data'] = $_POST;
        $_SESSION['errors'] = $errors;
    }

    protected function handle_errors() : array {
        $errors = array();
        // if (empty($_POST['name']) or 
        //     empty($_POST['phone']) or
        //     empty($_POST['classroom'])){
        //         $errors['empty_fields'] = 'Todos os campos são obrigatórios.';
        // }
        
        $login = SecurityManager::generate_login(
            htmlspecialchars($_POST['name']),
            htmlspecialchars($_POST['phone'])
        );

        if (!SecurityManager::is_login_valid($login))
            {$errors['invalid_login'] = 'Login inválido';}
        
        $login_duplicated = PeopleDAO::fetch_reader_by_login($login); // If doesn't fetch anythin, is unique
        if ($login_duplicated)
            {$errors['duplicate_login'] = 'Login duplicado.';}

        if (!SecurityManager::is_name_valid(htmlspecialchars($_POST['name'])))
            {$errors['invalid_name'] = 'Nome inválido.';}

        if (!SecurityManager::is_phone_valid(htmlspecialchars($_POST['phone'])))
            {$errors['invalid_phone'] = 'Telefone inválido.';}            
        
        return $errors;
    }

    public function manage_post_variable(){
        parent::manage_post_variable();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this -> handle_errors(); 
            if (empty($errors)){
                $args = [
                    'register_type' => 'user_register',
                    'success_title' => 'Cadastro aceito',
                    'success_message' => 'Cadastro de usuário realizado com sucesso'
                ];
                $args['reader_data'] = [
                    'name' => htmlspecialchars($_POST['name']),
                    'login' => SecurityManager::generate_login(
                        htmlspecialchars($_POST['name']),
                        htmlspecialchars($_POST['phone'])
                    ),
                    'phone' => htmlspecialchars($_POST['phone']),
                    'passphrase' => SecurityManager::generate_provisory_passphrase(),
                ];
                $this->operation_succeed($args);
                
            } 
            else $this->operation_failed('Cadastro recusado!', $errors);
        }
    }
    

}

$management = new UserRegisterManager();
$management -> manage_post_variable();   

?>