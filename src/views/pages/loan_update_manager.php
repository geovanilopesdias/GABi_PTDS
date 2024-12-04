<?php

require_once(__DIR__ . '/form_manager.php');
require_once(__DIR__ . '/../../controllers/loan_dao.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');

final class LoanUpdateManager extends FormManager {
    const UPDATE_TYPE = 'loan';
    const FAIL_TITLE = 'Atualização recusada';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de atualização de empréstimo!';

    public function __construct() {}

    protected function persist_post_to_session($errors) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['errors'] = $errors;
    }

    protected function operation_failed(
        array $errors,
        string $register_type = self::UPDATE_TYPE,
        string $fail_title = self::FAIL_TITLE,
        string $error_warning = self::ERROR_WARNING)
            {parent::operation_failed($errors, $register_type, $fail_title, $error_warning);}

    protected function operation_succeed(&$args){
        try{
            $loan = LoanDAO::fetch_loan_by_id($args['loan_id']);
            $date = new DateTime($args['date']);
            if ($args['action'] === 'close')
                {LoanDAO::close_loan($loan -> get_loaner_id(), $_SESSION['user_id'], $date);}
            else 
                {LoanDAO::close_loan($loan -> get_loaner_id(), $_SESSION['user_id'], $date);}

            $loan_data = $loan -> toArray();
            $loan_data['action'] = $args['action'];
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
        $loan = LoanDAO::fetch_loan_by_id($_POST['id']);
        $book = BookDAO::fetch_bookcopy_by_id($loan -> get_book_copy_id());
        $loaner = PeopleDAO::fetch_reader_by_id($loan -> get_loaner_id(), true);
        $date = new DateTime(htmlspecialchars($_POST['date']));
        if ($date < $loan -> get_loan_date())
            {$errors['invalid_date'] = 'A data precisa ser anterior (ou a mesma) à retirada!';}
            
        if ($_POST['action'] == 'close' and $book -> get_status() != 'loaned')
            {$errors['invalid_closing'] = 'O exemplar não consta como emprestado!';}
        
        if ($_POST['action'] == 'renovate' and $loaner -> get_debt() > 0)
            {$errors['invalid_renovation'] = 'O leitor não pode renovar, pois possui débitos!';}
                    
        return $errors;
    }

    protected function unordered_register_data(array $loan_data): string {
        if ($loan_data['action'] == 'close') {
            return "
            <p>Empréstimo finalizado com sucesso:</p></br>
            <ul>
                <li><span class='data_header'>Leitor:</span> " .
                    $loan_data['reader_name'] . "</li>
                <li><span class='data_header'>Obra:</span> " .
                $loan_data['title'] . " (".$loan_data['asset_code'].")</li>
            </ul>";
        }

        else {
            return "
            <p>Empréstimo cadastrado:</p></br>
            <ul>
                <li><span class='data_header'>Leitor:</span> " .
                    $loan_data['reader_name'] . "</li>
                <li><span class='data_header'>Obra:</span> " .
                    $loan_data['title'] . " (".$loan_data['asset_code'].")</li>
                <li><span class='data_header'>Devolver até:</span> " .
                    $loan_data['return_until'] . "</li>
            </ul>";
        }
    }
    
    public function manage_post_variable(){
        parent::manage_post_variable();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this -> handle_errors(); 
            if (empty($errors)){
                $args = [
                    'register_type' => self::UPDATE_TYPE,
                    'success_message' => 'Atualização de empréstimo realizado com sucesso!',
                ];

                if ($_POST['action'] === 'close')
                    {$args['success_title'] = 'Empréstimo finalizado';}
                else 
                    {$args['success_title'] = 'Empréstimo renovado';}

                $loan = LoanDAO::fetch_loan_by_id(htmlspecialchars($_POST['id']));
                $bookcopy = BookDAO::fetch_bookcopy_essentially_by('id', $loan -> get_book_copy_id());                             
                
                $args['loan_data'] = [
                    'loaner_id' => intval(htmlspecialchars($_POST['loaner_id'])),
                    'title' => $bookcopy['title'],
                    'asset_code' => $bookcopy['asset_code'],
                    'reader_name' => PeopleDAO::fetch_reader_by_id($loan -> get_loaner_id(), true) -> get_name(),
                    'loan_date' => (new DateTime(htmlspecialchars($_POST['date']))),
                ];

                if ($_POST['action' == 'renovate']) {
                    // It shall update with the library settings:
                    $args['loan_data']['return_until'] =
                    (new DateTime(htmlspecialchars($_POST['date']))) ->
                        add(new DateInterval('P7D')) -> format('d/m/Y');
                }

                $this->operation_succeed($args);
              
            } 
            else $this->operation_failed($errors);
        }
    }
}

$management = new LoanUpdateManager();
$management -> manage_post_variable();   

?>