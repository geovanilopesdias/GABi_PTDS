<?php

require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/manager.php');

final class UserRegisterManager extends ViewManager{
    const REGISTER_TYPE = 'opus';
    const FAIL_TITLE = 'Cadastro recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de cadastro de obra!';

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
            $opus_data = $args['opus_data'];   
            $last_opus_inserted_id = BookDAO::register_opus($opus_data, $_SESSION['user_id']);

            // Authorship registration:
            if(is_array($_POST['writer_ids'])){
                foreach ($_POST['writer_ids'] as $w_id)
                    BookDAO::register_authorship(
                        ['opus_id' => $last_opus_inserted_id,
                        'writer_id' => htmlspecialchars($w_id)], $_SESSION['user_id']);
            }
            else {
                BookDAO::register_authorship(
                    ['opus_id' => $last_opus_inserted_id,
                    'writer_id' => htmlspecialchars($_POST['writer_ids'])], $_SESSION['user_id']);
            }

            $args['success_body'] = $this -> unordered_register_data($opus_data);
            parent::operation_succeed($args);
        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro em nosso sistema!\n".
            $e -> getMessage();
        }
    }

    protected function handle_errors() : array {
        $errors = array();

        if (strlen($_POST['title']) < 2)
            {$errors['invalid_title'] = 'Títulos precisam conter mais que dois caracteres!';}

        if (isset($_POST['original_year']))
            if (($_POST['original_year']) > date("Y"))
                {$errors['invalid_original_year'] = 'Não insires ano futuro';}

        if (isset($_POST['alternative_url']))
            if (!SecurityManager::is_url_valid(htmlspecialchars($_POST['alternative_url'])))
                {$errors['invalid_alternative_url'] = 'O weblink '.htmlspecialchars($_POST['alternative_url']).' é inválido.';}

        if (isset($_POST['ddc']))
            if (!SecurityManager::is_ddc_valid(htmlspecialchars($_POST['ddc'])))
                {$errors['invalid_ddc'] = 'O CDD '.htmlspecialchars($_POST['ddc']).' é inválido.';}
            
        return $errors;
    }

    protected function unordered_register_data(array $opus_data): string {
        return "
            <p>Obra cadastrada:</p></br>
            <ul>
                <li><span class='reader_data_header'>Título:</span> " .
                    htmlspecialchars($opus_data['title']) . "</li>
                <li><span class='reader_data_header'>Ano original:</span> " .
                    htmlspecialchars($opus_data['original_year']) . "</li>
                <li><span class='reader_data_header'>CDD:</span> " .
                    htmlspecialchars($opus_data['ddc']) . "</li>
                <li><span class='reader_data_header'>Weblink (clique para testar):</span> 
                    <a href='" .
                        htmlspecialchars($opus_data['alternative_url'])."
                    ' target='blank'>&#128279;</a>
                </li>
            </ul>";
    }
    

    public function manage_post_variable(){
        parent::manage_post_variable();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this -> handle_errors(); 
            if (empty($errors)){
                $args = [
                    'register_type' => self::REGISTER_TYPE.'_register',
                    'success_title' => 'Cadastro aceito',
                    'success_message' => 'Cadastro de usuário realizado com sucesso'
                ];
                $args['opus_data'] = [
                    'title' => htmlspecialchars($_POST['title']),
                    'ddc' => htmlspecialchars($_POST['ddc']) ?? null,
                    'original_year' => htmlspecialchars($_POST['original_year']) ?? null,
                    'alternative_url' => htmlspecialchars($_POST['alternative_url']) ?? null,
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