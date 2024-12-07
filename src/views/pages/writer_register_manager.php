<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/form_manager.php');

final class WriterRegisterManager extends FormManager{
    const REGISTER_TYPE = 'writer';
    const FAIL_TITLE = 'Cadastro recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de cadastro de autor!';
    const MINIMAL_AUTHOR_AGE = 6;

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
            $writer_data = $args['writer_data'];   
            BookDAO::register_writer($writer_data, $_SESSION['user_id']);

            $args['success_body'] = $this -> unordered_register_data($writer_data);
            parent::operation_succeed($args);
        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro em nosso sistema!\n".
            $e -> getMessage();
        }
    }

    protected function handle_errors() : array {
        $errors = array();
        if (!SecurityManager::is_name_valid(ucfirst(htmlspecialchars($_POST['name']))))
            {$errors['invalid_name'] = 'O nome '.htmlspecialchars($_POST['name']).' é inválido.';}

        if (!empty($_POST['birth_year'])){
            $author_age = intval(date("Y")) - intval(htmlspecialchars($_POST['birth_year']));
            if ($author_age < self::MINIMAL_AUTHOR_AGE and $author_age >= 0)
                {$errors['invalid_birth_year'] = 'Autor deve ser maior de '.self::MINIMAL_AUTHOR_AGE.' anos';}
            else if ((date("Y") - htmlspecialchars($_POST['birth_year'])) < 0)
                {$errors['invalid_birth_year'] = 'Não insira um ano futuro!';}
        }
            
        return $errors;
    }

    protected function unordered_register_data(array $writer_data): string {
        $list = "
            <p>Autor(a) cadastrado:</p></br>
            <ul>
                <li><span class='reader_data_header'>Nome:</span> " .
                    htmlspecialchars($writer_data['name']) . "</li>";
        $list .= (intval(htmlspecialchars($writer_data['birth_year'])) != 0) ?
                "<li><span class='reader_data_header'>Ano de nascimento:</span> ".
                htmlspecialchars($writer_data['birth_year'])."</li>" : '';
        
        $list .= "</ul>";
        return $list;
    }
    

    public function manage_post_variable(){
        parent::manage_post_variable();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this -> handle_errors(); 
            if (empty($errors)){
                $args = [
                    'register_type' => self::REGISTER_TYPE,
                    'success_title' => 'Cadastro aceito',
                    'success_message' => 'Cadastro de usuário realizado com sucesso'
                ];
                $args['writer_data'] = [
                    'name' => ucfirst(htmlspecialchars($_POST['name'])),
                    'birth_year' => (isset($_POST['birth_year']) ? intval($_POST['birth_year']) : null),
                ];
                $this->operation_succeed($args);
                
            } 
            else $this->operation_failed($errors);
        }
    }
    

}

$management = new WriterRegisterManager();
$management -> manage_post_variable();   

?>