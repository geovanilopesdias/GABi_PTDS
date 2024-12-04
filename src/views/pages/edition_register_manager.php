<?php

require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/manager.php');

final class EditionRegisterManager extends FormManager{
    const REGISTER_TYPE = 'edition';
    const FAIL_TITLE = 'Cadastro recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de cadastro de edição!';

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
            $edition_data = $args['edition_data'];   
            BookDAO::register_edition($edition_data, $_SESSION['user_id']);         

            $args['success_body'] = $this -> unordered_register_data($edition_data);
            parent::operation_succeed($args);
        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro em nosso sistema!\n".
            $e -> getMessage();
        }
    }

    protected function handle_errors() : array {
        $errors = array();
        // Text fields:
        if (!empty($_POST['isbn'])) {
            if (!SecurityManager::is_isbn_valid(htmlspecialchars($_POST['isbn'])))
                {$errors['invalid_isbn'] = "ISBN inválido";}
            
            else {
                if (SecurityManager::is_isbn_in_database(htmlspecialchars($_POST['isbn'])))
                    {$errors['duplicate_isbn'] = 'Este ISBN já está cadastrado!';}
            }
        }

        if (!empty($_POST['cover_colors'])) {
            $colors = explode(',', trim(htmlspecialchars($_POST['cover_colors'])));
            $nonvalid_colors = array();
            foreach ($colors as $c) {if (!ctype_alpha($c)) {$nonvalid_colors[] = $c;}}
                    
            if (!empty($nonvalid_colors)){
                if (count($nonvalid_colors) == 1)
                    {$errors['invalid_cover_colors'] = $nonvalid_colors[0].' não é um nome de cor válido';}
                else
                    {$errors['invalid_cover_colors'] = implode(',', $nonvalid_colors).' não são nomes de cor válidos';}
            }
        }

        if (!empty(trim($_POST['translators']))) {
            $names = explode(',', trim(htmlspecialchars($_POST['translators'])));
            $nonvalid_names = array();
            foreach ($names as $n) {if (!SecurityManager::is_name_valid($n)) {$nonvalid_names[] = $c;}}
                    
            if (!empty($nonvalid_names)){
                if (count($nonvalid_names) == 1)
                    {$errors['invalid_translators'] = $nonvalid_names[0].' é um nome inválido de pessoa.';}
                else
                    {$errors['invalid_translators'] = implode(',', $nonvalid_names).' são nomes inválidos de pessoa.';}
            }
        }

        // Numeric fields:
        if (!empty($_POST['edition_number'])){
            if ($_POST['edition_number'] < 1)
                {$errors['invalid_edition_number'] = 'Edição precisa ser inteira e maior que zero!';}
        }

        if (!empty($_POST['pages'])){
            if ($_POST['pages'] < 1)
                {$errors['invalid_pages'] = 'Número de páginas precisa ser inteiro e maior que zero!';}
        }

        if (!empty($_POST['volume'])){
            if ($_POST['volume'] < 1)
                {$errors['invalid_volume'] = 'Volume do livro precisa ser inteiro e maior que zero!';}
        }

        if (!empty($_POST['publishing_year'])){
            if ($_POST['publishing_year'] < 1854)
                {$errors['invalid_publishing_year'] = 'A editora mais antiga do Brasil é de 1854...';}
        }
            
        return $errors;
    }

    protected function unordered_register_data(array $edition_data): string {
        $opus = (!empty($edition_data['opus_id'])) ? BookDAO::fetch_opus_by_id($edition_data['opus_id']) : null;
        $publisher = ((!empty($edition_data['publisher_id']))) ? BookDAO::fetch_publisher_by_id($edition_data['publisher_id']) : null;
        $collection = (!empty($edition_data['collection_id'])) ? BookDAO::fetch_collection_by_id($edition_data['collection_id']) : null;
        return "
            <p>Obra cadastrada:</p></br>
            <ul>
                <li><span class='data_header'>Título:</span> " .
                    (!is_null($opus) ? $opus -> get_title() : '') . "</li>
                <li><span class='data_header'>Editora:</span> " .
                    (!is_null($publisher) ? $publisher -> get_name() : '') . "</li>
                <li><span class='data_header'>Coleção:</span> " .
                    (!is_null($collection) ? $collection -> get_name() : '') . "</li>
                <li><span class='data_header'>ISBN:</span> " .
                    ($edition_data['isbn'] ?? '') . "</li>
                <li><span class='data_header'>Edição:</span> " .
                    ($edition_data['edition_number'] ?? '') . "</li>
                <li><span class='data_header'>Ano de publicação:</span> " .
                    ($edition_data['publishing_year'] ?? '') . "</li>
                <li><span class='data_header'>Número de páginas:</span> " .
                    ($edition_data['pages'] ?? '') . "</li>
                <li><span class='data_header'>Volume:</span> " .
                    ($edition_data['volume'] ?? '') . "</li>
                <li><span class='data_header'>Cores da capa:</span> " .
                    ($edition_data['cover_colors'] ?? '') . "</li>
                <li><span class='data_header'>Tradutores:</span> " .
                    ($edition_data['translators'] ?? '') . "</li>
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
                    'success_message' => 'Cadastro de edição realizado com sucesso'
                ];

                $args['edition_data'] = [
                    'opus_id' => !empty($_POST['opus_id']) ? (int) htmlspecialchars($_POST['opus_id']) : '',
                    'publisher_id' => !empty($_POST['publisher_id']) ? (int) htmlspecialchars($_POST['publisher_id']) : null,
                    'collection_id' => !empty($_POST['collection_id']) ? (int) htmlspecialchars($_POST['collection_id']) : null,
                    'isbn' => !empty($_POST['isbn']) ? htmlspecialchars($_POST['isbn']) : null,
                    'edition_number' => !empty($_POST['edition_number']) ? (int) htmlspecialchars($_POST['edition_number']) : null,
                    'publishing_year' => !empty($_POST['publishing_year']) ? (int) htmlspecialchars($_POST['publishing_year']) : null,
                    'pages' => !empty($_POST['pages']) ? (int) htmlspecialchars($_POST['pages']) : null,
                    'volume' => !empty($_POST['volume']) ? (int) htmlspecialchars($_POST['volume']) : null,
                    'cover_colors' => !empty($_POST['cover_colors']) ? htmlspecialchars($_POST['cover_colors']) : null,
                    'translators' => !empty($_POST['translators']) ? htmlspecialchars($_POST['translators']) : null,
                ];
                $this->operation_succeed($args);
                
            } 
            else $this->operation_failed('Cadastro de edição recusado pelos seguintes motivos:', $errors);
        }
    }
}

$management = new EditionRegisterManager();
$management -> manage_post_variable();   

?>