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
        if(is_null($element))
            {return "<h1>Puxa vida! Erro ao encontrar o empréstimo...</h1>";}
        
        $loan = LoanDAO::fetch_loan_with_reader_and_opus_data_by_loan_id($element -> get_id());
        if(empty($loan))
            {return "<h1>Puxa vida! Erro ao construir o empréstimo...</h1>";}
        
        $opener = PeopleDAO::fetch_reader_by_id($loan['opener_id'], true);
        
        $detail = "
            <p><strong>Patrimônio:</strong> " . $loan['asset_code']."</p>
            <p><strong>Obra:</strong> " . $loan['title']."</p>
            <p><strong>Leitor:</strong> " . ucwords(($loan['name'] ?? '{REMOVIDO}')) . "</p>
            <p><strong>Retirada em:</strong> " . InterfaceManager::mask_timestamp($loan['loan_date']) . "</p>
            <p><strong>Emprestado por:</strong> ".ucwords(($opener -> get_name() ?? '{REMOVIDO}'))." (em ".InterfaceManager::mask_timestamp($loan['opening_date']).")</p>
        ";
        if (is_null($loan['return_date'])) {
            $detail .= InterfaceManager::loan_button_grid($element -> get_id(), $_SESSION['errors'] ?? array());
        }

        else {
            $closer = PeopleDAO::fetch_reader_by_id($loan['closer_id'], true);
            $detail .= "
                <p><strong>Retorno em:</strong> " . InterfaceManager::mask_timestamp($loan['return_date']) . "</p>
                <p><strong>Recebido por:</strong> ".ucwords(($closer -> get_name() ?? '{REMOVIDO}'))." (em ".InterfaceManager::mask_timestamp($loan['closing_date']) .")</p>
            ";
        }

        if (!is_null($loan['debt_receiver_id'])){
            $debt_receiver = PeopleDAO::fetch_reader_by_id($loan['debt_receiver_id'], true);
            $detail .= "
                <p><strong>Dívida paga para:</strong> ".ucwords(($debt_receiver -> get_name() ?? '{REMOVIDO}'))."</p>
            ";
        }
        
        return $detail;
    }

    protected function data_table($element): string {
        return "<p>[Aqui, futuramente, discriminar-se-ão informações de reservas]</p>";
        
    }


}

$element = new LoanDetail();
$element -> echo_structure();
      

?>