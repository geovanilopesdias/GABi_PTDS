<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/element_detail.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/../../controllers/loan_dao.php');

final class LoanDetail extends ElementDetail{
    const ELEMENT_TYPE = 'loan';

    public function __construct(){}

    public function echo_structure(string $element_type = self::ELEMENT_TYPE){
        parent::echo_structure($element_type);
    }

    protected function detail_element($element): string{
        if(is_null($element)) return "<h1>Puxa vida! Erro ao encontrar o empréstimo...</h1>";
        $loan = LoanDAO::fetch_loan_with_reader_and_opus_data_by_loan_id($element -> get_id());

        if(empty($loan)) return "<h1>Puxa vida! Erro ao construir o empréstimo...</h1>";
        $opener_name = PeopleDAO::fetch_reader_by_id($loan['opener_id'], true) -> get_name();

        if(empty($opener_name)) return "<h1>Puxa vida! Erro o responsável pelo empréstimo...</h1>";
        
        $detail = "
            <p><strong>Patrimônio:</strong> " . $loan['asset_code']."</p>
            <p><strong>Obra:</strong> " . $loan['title']."</p>
            <p><strong>Leitor:</strong> " . ucwords($loan['name']) . "</p>
            <p><strong>Retirada em:</strong> " . InterfaceManager::mask_timestamp($loan['loan_date']) . "</p>
            <p><strong>Emprestado por:</strong> $opener_name (em ".InterfaceManager::mask_timestamp($loan['opening_date']).")</p>
        ";
        if (!is_null($loan['return_date'])){
            $closer_name = PeopleDAO::fetch_reader_by_id($loan['closer_id'], true) -> get_name();
            $detail .= "
                <p><strong>Retorno em:</strong> " . InterfaceManager::mask_timestamp($loan['return_date']) . "</p>
                <p><strong>Recebido por:</strong> $closer_name (em ".InterfaceManager::mask_timestamp($loan['closing_date']) .")</p>
            ";
        }

        if (!is_null($loan['debt_receiver_id'])){
            $debt_receiver_name = PeopleDAO::fetch_reader_by_id($loan['debt_receiver_id'], true) -> get_name();
            $detail .= "
                <p><strong>Dívida paga para:</strong> $debt_receiver_name</p>
            ";
        }
        
        return $detail;
    }

    protected function data_table($element): string {
        return "<p>[Aqui, futuramente discriminar-se-ão informações de reservas]</p>";
        
    }


}

$element = new LoanDetail();
$element -> echo_structure();
      

?>