<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/../../controllers/loan_dao.php');

abstract class ElementDetail{
    const PAGE_TYPE = 'element_detail';

    protected abstract function detail_element($element): string;

    protected function get_element(string $element_type): mixed{
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['id'])) {
                echo "ID received: " . htmlspecialchars($_POST['id']);
            } else {
                echo "ID not received in POST.";
            }
        } else {
            echo "Invalid request method. Expected POST.";
        }

        $id = htmlspecialchars($_POST['id']);
        return match($element_type){
            'user' => PeopleDAO::fetch_reader_by_id($id, true),
            'classroom' => PeopleDAO::fetch_classroom_by_id($id),
            'opus' => BookDAO::fetch_opus_by_id($id),
            'edition' => BookDAO::fetch_edition_by_id($id),
            'bookcopy' => BookDAO::fetch_bookcopy_by_id($id),
        };
    }

    public function echo_structure(string $element_type){
        session_start();
        if (!isset($_SESSION['user_id']) or
            ($element_type === 'user' and $_SESSION['user_role'] !== 'librarian'))
                header('Location: login.php'); exit;
        
        $page_title = "GABi | Detalhamento de " .
            match ($element_type){
                'user' => 'Leitor',
                'classroom' => 'Turma',
                'writer' => 'Autor',
                'opus' => 'Obra',
                'edition' => 'Edição',
                'bookcopy' => 'Exemplar',
            };
        echo 'here';
        InterfaceManager::echo_html_head($page_title, self::PAGE_TYPE);
        echo InterfaceManager::system_logo(self::PAGE_TYPE);
        echo $this -> detail_element($this -> get_element($element_type));
        echo InterfaceManager::back_to_menu_button();
        InterfaceManager::echo_html_tail();
        exit;
    }

}
      

?>