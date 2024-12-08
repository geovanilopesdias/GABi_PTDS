<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/form_manager.php');

final class BookcopyRegisterManager extends FormManager{
    const REGISTER_TYPE = 'bookcopy';
    const FAIL_TITLE = 'Cadastro recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de cadastro de exemplar!';

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
            foreach ($args['bookcopy_data']['asset_code'] as $c) {
                BookDAO::register_book_copy(
                    ['asset_code' => trim($c), 'edition_id' => $args['bookcopy_data']['edition_id']],
                    $_SESSION['user_id']);
            }
            
            $args['success_body'] = $this -> unordered_register_data($args['bookcopy_data']);
            parent::operation_succeed($args);
        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro em nosso sistema!\n".$e -> getMessage();
        }
    }

    protected function handle_errors() : array {
        $errors = array();
        $bookcopy_asset_codes = explode(',', $_POST['asset_code']);
        if (isset($_POST['ordered_assets'])){
            $base_code = filter_var($_POST['asset_code'], FILTER_VALIDATE_INT);
            if ($base_code === false)
                {$errors['invalid_asset_code'] = 'The provided asset code must be a valid integer.';}
            
            if (!isset($_POST['quantity']) or $_POST['quantity'] < 2)
                {$errors['invalid_quantity'] = 'Para cadastro ordenado de patrimônios, informe uma quantidade maior que um para cadastro.';}
        }
        else {
            foreach ($bookcopy_asset_codes as $c){
                if (!BookDAO::is_asset_code_unique($c)) 
                    {$errors['invalid_asset_code'] = 'Patrimônio inserido já existe!';}
            }
        }

        if (!empty($_POST['quantity'])){
            if (isset($_POST['ordered_assets'])){
                if (!is_int(intval($_POST['asset_code'])))
                    {$errors['invalid_asset_code'] = 'Para cadastro de vários patrimônios ordenadamente, insira apenas o primeiro código.';}
                
                $base_code = filter_var($_POST['asset_code'], FILTER_VALIDATE_INT);
                for ($i = 0; $i < intval($_POST['quantity']); $i++) {
                    if (!BookDAO::is_asset_code_unique($base_code + $i)) 
                        {$errors['invalid_asset_code'] = 'Faixa de valores informada possui valor de patrimônio já existente!';}
                }
            }
            else {
                if (count($bookcopy_asset_codes) != $_POST['quantity'])
                    {$errors['incoerent_quantity_asset_list'] = 'A quantidade de exemplares e a quantidade de patrimônios devem ser iguais.';}
            }
        }
        
        

        return $errors;
    }

    protected function unordered_register_data(array $bookcopy_data): string {
        $edition = BookDAO::fetch_edition_with_opus_writer_data($bookcopy_data['edition_id']);
        return "
            <p><span class='data_header'>Exemplares cadastrados para:</span></p></br>
            <p>".
                (isset($edition['title']) ? $edition['title'] : '') . 
                (isset($edition['isbn']) ? "(".$edition['isbn'].")" : '') .
                (isset($edition['collection']) ? " da coleção ".$edition['collection'].")" : '') .
            "</p></br>
            <p><span class='data_header'>Patrimônio(s) cadastrado(s): </span></p>".
            implode(', ', $bookcopy_data['asset_code']);
    }
    

    public function manage_post_variable(){
        parent::manage_post_variable();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this -> handle_errors(); 
            if (empty($errors)){
                $args = [
                    'register_type' => self::REGISTER_TYPE,
                    'success_title' => 'Cadastro aceito',
                    'success_message' => 'Cadastro de exemplar realizado com sucesso'
                ];
                $args['bookcopy_data']['edition_id'] = intval(htmlspecialchars($_POST['edition_id']));

                if (isset($_POST['ordered_assets'])) {
                    $base_code = filter_var($_POST['asset_code'], FILTER_VALIDATE_INT);
                    $codes = [];
                    for ($i = 0; $i < intval($_POST['quantity']); $i++)
                        {$codes[] = $base_code + $i;}
                
                    $args['bookcopy_data']['asset_code'] = $codes;
                }
                else {
                    $args['bookcopy_data']['asset_code'] = [];
                    if (!empty($_POST['quantity']) && intval($_POST['quantity']) > 1){
                        $args['bookcopy_data']['asset_code'] = array_map('htmlspecialchars', explode(',', $_POST['asset_code']));
                    }
                    else {
                        $args['bookcopy_data']['asset_code'][] = htmlspecialchars($_POST['asset_code']);
                    }
                }       
                $this->operation_succeed($args);
            } 
            else $this->operation_failed($errors);
        }
    }
    

}

$management = new BookcopyRegisterManager();
$management -> manage_post_variable();   

?>