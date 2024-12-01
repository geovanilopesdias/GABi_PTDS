<?php

require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/../../controllers/loan_dao.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/manager.php');

final class LoanRegisterManager extends FormManager{
    const REGISTER_TYPE = 'loan';
    const FAIL_TITLE = 'Cadastro recusado';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de cadastro de empréstimo!';

    public function __construct() {}

    protected function persist_post_to_session($errors) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['errors'] = $errors;
    }

    protected function operation_failed(
        string $error_detail, $errors = [],
        string $register_type = self::REGISTER_TYPE,
        string $fail_title = self::FAIL_TITLE,
        string $error_warning = self::ERROR_WARNING
        ){
            parent::operation_failed($error_detail, $errors, $register_type, $fail_title, $error_warning);
    }

    protected function operation_succeed(&$args){
        try{
            $loan_data = $args['loan_data'];
            foreach ($loan_data['book_data'] as $c => $title) {
                $data = [
                    'loaner_id' => $loan_data['loaner_id'],
                    'book_copy_id' => $c,
                    'loan_date' => $loan_data['loan_date']
                ];
                LoanDAO::register_loan($data, $_SESSION['user_id']);
            }
            
            //Registration
            $args['success_body'] = $this -> unordered_register_data($loan_data);
            parent::operation_succeed($args);
        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro durante o registro!\n";
            error_log($e -> getMessage());
        }
    }

    protected function handle_errors() : array {
        $errors = array();

        if (!empty($_POST['asset_code'])){
            $asset_codes = explode(',', $_POST['asset_code']);
            foreach ($asset_codes as $c) {
                if (BookDAO::is_asset_code_unique($c))
                    {$errors['non_existent_asset_code'] = 'Um dos patrimônios inseridos não está cadastrado!';}
            }
        }       

        if (!empty($_POST['loaner_id'])){
            $loaner = PeopleDAO::fetch_reader_by_id($_POST['loaner_id'], true);
            if ($loaner -> get_debt() > 0)
                {$errors['debtor_loaner'] = 'O leitor possui débitos pendentes!';}
        }
            
        return $errors;
    }

    protected function unordered_register_data(array $loan_data): string {
        $book_data = array();
        foreach ($loan_data['book_data'] as $asset_code => $title)
            {$book_data[] = "$title ($asset_code)";}

        return "
            <p>Empréstimo cadastrado:</p></br>
            <ul>
                <li><span class='data_header'>Leitor:</span> " .
                    $loan_data['reader_name'] . "</li>
                <li><span class='data_header'>Obra(s):</span> " .
                    implode(',', $book_data) . "</li>
                <li><span class='data_header'>Devolver até:</span> " .
                    $loan_data['return_until'] . "</li>
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
                    'success_message' => 'Cadastro de empréstimo realizado com sucesso'
                ];

                $asset_codes = explode(',', htmlspecialchars($_POST['asset_code']));
                foreach ($asset_codes as $c) {
                    $bookcopy = BookDAO::fetch_bookcopy_holistically_by_asset_code($c);
                    if ($bookcopy) {$book_data[$c] = $bookcopy['title'];}
                }
                              
                $reader_name = PeopleDAO::fetch_reader_by_id(
                    htmlspecialchars($_POST['loaner_id']), true) -> get_name();
                
                $args['loan_data'] = [
                    'loaner_id' => intval(htmlspecialchars($_POST['loaner_id'])),
                    'book_data' => $book_data,
                    'reader_name' => $reader_name,
                    'loan_date' => (new DateTime(htmlspecialchars($_POST['loan_date']))),

                    // It shall update with the library settings:
                    'return_until' => (new DateTime(
                        htmlspecialchars($_POST['loan_date']))) ->
                            add(new DateInterval('P7D')) -> format('d/m/Y'),
                ];
                $this->operation_succeed($args);
              
            } 
            else $this->operation_failed('Cadastro recusado!', $errors);
        }
    }
}

$management = new LoanRegisterManager();
$management -> manage_post_variable();   

?>