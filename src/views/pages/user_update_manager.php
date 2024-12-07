<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/form_manager.php');

final class UserUpdateManager extends FormManager{
    const REGISTER_TYPE = 'user';
    const FAIL_TITLE = 'Edição recusada';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de edição de usuário!';

    public function __construct() {}

    protected function persist_post_to_session($errors) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['errors'] = $errors;
    }

    protected function operation_failed(
        array $errors,
        string $register_type = self::REGISTER_TYPE,
        string $fail_title = self::FAIL_TITLE,
        string $error_warning = self::ERROR_WARNING)
            {parent::operation_failed($errors, $register_type, $fail_title, $error_warning);}

    protected function operation_succeed(&$args){
        try{
            PeopleDAO::edit_reader($args['reader_data']['id'], $args['reader_data'], $_SESSION['user_id']);
            $args['success_body'] = $this -> unordered_register_data($args['reader_data']);
            parent::operation_succeed($args);
        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro em nosso sistema!\n".
            error_log($e -> getMessage());
        }
    }

    protected function handle_errors() : array {
        $errors = array();        
        
        if (!SecurityManager::is_name_valid(htmlspecialchars($_POST['name'])))
            {$errors['invalid_name'] = 'O nome '.htmlspecialchars($_POST['name']).' é inválido.';}

        if (!SecurityManager::is_phone_valid(preg_replace('/\D/', '', htmlspecialchars($_POST['phone']))))
            {$errors['invalid_phone'] = 'O telefone '.htmlspecialchars($_POST['phone']).' é inválido.';}            
        
        return $errors;
    }

    protected function unordered_register_data(array $reader_data): string {
        return "
            <p>Usuário editado:</p></br>
            <ul>
                <li><span class='data_header'>Nome:</span> " . htmlspecialchars($reader_data['name']) . "</li>
                <li><span class='data_header'>Telefone:</span> " . InterfaceManager::mask_phone($reader_data['phone']) . "</li>
            </ul></li></ul>";
        
    }
    

    public function manage_post_variable(){
        parent::manage_post_variable();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this -> handle_errors(); 
            if (empty($errors)){
                $args = [
                    'register_type' => self::REGISTER_TYPE,
                    'success_title' => 'Edição aceita',
                    'success_message' => 'Edição de usuário realizada com sucesso'
                ];
                $args['reader_data'] = [
                    'id' => htmlspecialchars($_POST['id']),
                    'name' => htmlspecialchars($_POST['name']),
                    'phone' => preg_replace('/\D/', '', htmlspecialchars($_POST['phone'])),
                ];
                $this->operation_succeed($args);
                
            } 
            else $this->operation_failed($errors);
        }
    }
    

}

$management = new UserUpdateManager();
$management -> manage_post_variable();   

?>