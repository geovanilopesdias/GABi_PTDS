<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/element_detail.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/../../controllers/loan_dao.php');

final class ReaderDetail extends ElementDetail{
    const ELEMENT_TYPE = 'user';

    public function __construct(){}

    public function echo_structure(string $element_type = self::ELEMENT_TYPE){
        parent::echo_structure($element_type);
    }

    protected function detail_element($element): string{
        if(is_null($element)) return "<h1>Puxa vida! Erro ao encontrar o leitor...</h1>";
        
        $reader_role = match ($element -> get_role()){
            'student' => 'discente',
            'teacher' => 'docente',
            'librarian' => 'bibliotecário',
        };
        return "
            <p><strong>Nome:</strong> " . ucwords($element->get_name())." ($reader_role)</p>
            <p><strong>Telefone:</strong> " . InterfaceManager::mask_phone($element->get_phone()) . "</p>
            <p><strong>Último acesso:</strong> " . $element->get_last_login() -> format('d/m/y | H:i') . "</p>
            <p><strong>Débito:</strong> R$ " . $element->get_debt() . "</p>
        ";
    }

    protected function data_table($element): string {
        $open_loans = LoanDAO::fetch_open_loans_by_loaner_id($element -> get_id());
        if(!empty($open_loans)) {
            return InterfaceManager::table_of_results('loan', 'Empréstimos em aberto', $open_loans);
        }
        else return "<p>Este usuário está em posse de livros.</p>";
        
    }


}

$element = new ReaderDetail();
$element -> echo_structure();
      

?>