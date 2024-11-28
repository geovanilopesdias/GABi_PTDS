<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/../../controllers/loan_dao.php');

abstract class ElementDetail{
    const PAGE_TYPE = 'element_detail';

    protected abstract function detail_element($element): string;
    protected abstract function data_table($element): string;

    protected function get_element(string $element_type): mixed{
        $id = htmlspecialchars($_POST['id']);
        return match($element_type){
            'user' => PeopleDAO::fetch_reader_by_id($id, true),
            'classroom' => PeopleDAO::fetch_classroom_by_id($id),
            'opus' => BookDAO::fetch_opus_by_id($id),
            'edition' => BookDAO::fetch_edition_by_id($id),
            'bookcopy' => BookDAO::fetch_bookcopy_by_id($id),
            'loan' => LoanDAO::fetch_loan_by_id($id),
        };
    }

    public function echo_structure(string $element_type){
        session_start();
        if (!isset($_SESSION['user_id']) or
            (($element_type === 'user' or $element_type === 'loan') and
                $_SESSION['user_role'] !== 'librarian'))
                    {header('Location: login.php'); exit;}
        
        $title = "GABi | Detalhamento de " .
            match ($element_type){
                'user' => 'Leitor',
                'classroom' => 'Turma',
                'writer' => 'Autor',
                'opus' => 'Obra',
                'edition' => 'Edição',
                'bookcopy' => 'Exemplar',
                'loan' => 'Empréstimo',
            };
        InterfaceManager::echo_html_head($title, self::PAGE_TYPE);
        echo InterfaceManager::system_logo(self::PAGE_TYPE);
        echo "<div id='element_detail'>";
        echo $this -> detail_element($this -> get_element($element_type));
        echo $this -> data_table($this -> get_element($element_type));
        echo InterfaceManager::back_to_menu_button();
        echo "</div>";
        InterfaceManager::echo_html_tail();
        exit;
    }

}
      

?>