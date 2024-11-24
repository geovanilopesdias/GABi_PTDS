<?php

use PhpParser\Node\Stmt\Interface_;

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/manager.php');

final class UserRegisterManager extends ViewManager{
    const REGISTER_TYPE = 'user';
    const FAIL_TITLE = 'Cadastro recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de cadastro de usuário!';

    public function __construct() {}

    protected function persist_post_to_session($errors) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['errors'] = $errors;
    }

    protected function operation_failed(
        string $error_detail, $errors = [],
        string $register_type = self::REGISTER_TYPE.'_register',
        string $fail_title = self::FAIL_TITLE,
        string $error_warning = self::ERROR_WARNING
        ){
            parent::operation_failed($error_detail, $errors, $register_type, $fail_title, $error_warning);
    }

    protected function operation_succeed(&$args){
        try{
            $reader_data = $args['reader_data'];   
            $classrooms_ids = $_POST['classrooms_ids'];
            if (is_array($classrooms_ids)) {
                foreach($classrooms_ids as $c_id) {
                    $classroom = PeopleDAO::fetch_classroom_by_id($c_id);
                    $classrooms_names[] = $classroom -> get_name();
                };
            }
            else {
                $classroom = PeopleDAO::fetch_classroom_by_id($classrooms_ids);
                $classroom_name = $classroom -> get_name();
            };

            if ($_SESSION['role'] === 'student'){
                PeopleDAO::register_student($reader_data, $_SESSION['user_id']);
                $student_fetched = PeopleDAO::fetch_reader_by_login($reader_data['login']);
                foreach ($classrooms_ids as $c_id)
                    PeopleDAO::register_enrollment(
                        ['student_id' => $student_fetched -> get_id(), 'classroom_id' => $c_id],
                        $_SESSION['user_id']);
            }
            else { // Teacher register
                if ($_SESSION['can_borrow'] === 'on')
                    PeopleDAO::register_loaner_teacher($reader_data, $_SESSION['user_id']);
                else
                    PeopleDAO::register_non_loaner_teacher($reader_data, $_SESSION['user_id']);
                $teacher_fetched = PeopleDAO::fetch_reader_by_login($reader_data['login']);
                foreach ($classrooms_ids as $c_id)
                    PeopleDAO::register_teaching(
                        ['teacher_id' => $teacher_fetched -> get_id(), 'classroom_id' => $c_id],
                        $_SESSION['user_id']);
            }
            $args['success_body'] = $this -> unordered_register_data($reader_data);
            parent::operation_succeed($args);
        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro em nosso sistema!\n".
            $e -> getMessage();
        }
    }

    protected function handle_errors() : array {
        $errors = array();
        // if (!isset($_POST['name'], $_POST['phone'], $_POST['classrooms']))
        //     {$errors['empty_fields'] = 'Todos os campos são obrigatórios.';}
        
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
            {$errors['invalid_name'] = 'O nome '.htmlspecialchars($_POST['name']).' é inválido.';}

        if (!SecurityManager::is_phone_valid(preg_replace('/\D/', '', htmlspecialchars($_POST['phone']))))
            {$errors['invalid_phone'] = 'O telefone '.htmlspecialchars($_POST['phone']).' é inválido.';}            
        
        return $errors;
    }

    protected function unordered_register_data(array $reader_data): string {
        $list = "
            <p>Usuário cadastrado:</p></br>
            <ul>
                <li><span class='reader_data_header'>Nome:</span> " . htmlspecialchars($reader_data['name']) . "</li>
                <li><span class='reader_data_header'>Telefone:</span> " . InterfaceManager::mask_phone($reader_data['phone']) . "</li>
                <li><span class='reader_data_header'>Login:</span> " . htmlspecialchars($reader_data['login']) . "</li>
                <li><span class='reader_data_header'>Senha provisória:</span> " . htmlspecialchars($reader_data['passphrase']) . "</li>
                <li><span class='reader_data_header'>Turma(s):</span><ul>";
    
        if (!empty($reader_data['classrooms']) && is_array($reader_data['classrooms'])) 
            foreach ($reader_data['classrooms'] as $c) 
                $list .= "<li>" . htmlspecialchars($c) . "</li>";
            
        else $list .= "<li>Nenhuma turma selecionada.</li>";    
        
        $list .= "</ul></li></ul>";
        return $list;
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
                    'phone' => preg_replace('/\D/', '', htmlspecialchars($_POST['phone'])),
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