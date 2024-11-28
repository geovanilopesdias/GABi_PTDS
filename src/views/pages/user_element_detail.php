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
            <p class='element_detail'>Nome: " . $element->get_name()."</p>
            <p class='element_detail'>Telefone: " . InterfaceManager::mask_phone($element->get_phone()) . "</p>
            <p class='element_detail'>Tipo: $reader_role</p>
            <p class='element_detail'>Último acesso: " . $element->get_last_login() -> format('d/m/y | H:i') . "</p>
            <p class='element_detail'>Débito: " . $element->get_debt() . "</p>
        ";
    }


}

$element = new ReaderDetail();
$element -> echo_structure();
      

?>