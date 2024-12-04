<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/form_manager.php');

final class PublisherRegisterManager extends FormManager{
    const REGISTER_TYPE = 'publisher';
    const FAIL_TITLE = 'Cadastro recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de cadastro de usuário!';

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
            $publisher_data = $args['publisher_data'];   
            BookDAO::register_publisher($publisher_data, $_SESSION['user_id']);
            $args['success_body'] = $this -> unordered_register_data($publisher_data);
            parent::operation_succeed($args);
        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro em nosso sistema!\n".
            $e -> getMessage();
        }
    }

    protected function handle_errors() : array {
        $errors = array();
        if(!isset($_POST['name'])) $errors['empty_name'] = 'O nome é única condição exigida...';
        return $errors;
    }

    protected function unordered_register_data(array $publisher_data): string {
        return "
            <p>Editora cadastrada:</p></br>
            <ul>
                <li><span class='data_header'>Nome:</span> " . htmlspecialchars($publisher_data['name']) . "</li>
            </ul>";
    }
    

    public function manage_post_variable(){
        parent::manage_post_variable();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this -> handle_errors(); 
            if (empty($errors)){
                $args = [
                    'register_type' => self::REGISTER_TYPE,
                    'success_title' => 'Cadastro aceito',
                    'success_message' => 'Cadastro de editora realizado com sucesso'
                ];
                $args['publisher_data'] = ['name' => htmlspecialchars($_POST['name'])];
                $this->operation_succeed($args);  
            } 
            else $this->operation_failed($errors);
        }
    }
    

}

$management = new PublisherRegisterManager();
$management -> manage_post_variable();   

?>