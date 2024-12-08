<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/../../controllers/people_dao.php');
require_once(__DIR__ . '/../../controllers/book_dao.php');
require_once(__DIR__ . '/form_manager.php');

final class DeleteManager extends FormManager{
    const FAIL_TITLE = 'Exclusão recusada';
    const ERROR_WARNING = 'Algo deu errado com sua tentativa de exclusão!';
    protected string $register_type;

    public function __construct() {
        $this -> register_type = htmlspecialchars($_POST['element_type']);
    }

    public function manage_post_variable(){
        parent::manage_post_variable();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $this -> handle_errors(); 
            if (empty($errors)){
                $step = htmlspecialchars($_POST['deletion_step'] ?? '');
                if ($step === 'confirmation')
                    {$this->show_confirmation();}
                else if ($step === 'implementation') {
                    $args = [
                    'register_type' => $this -> register_type,
                    'success_title' => 'Exclusão aceita',
                    'success_message' => 'Exclusão realizada com sucesso'
                    ];
                    $args['element_data'] = self::get_element();
                    $this->operation_succeed($args);
                }  
            } 

            else $this->operation_failed($errors);
        }
    }

    protected function get_element(): array {
        return match (htmlspecialchars($_POST['element_type'])) {
            'user' => PeopleDAO::fetch_reader_by_id(intval(htmlspecialchars($_POST['id'])), true) -> toArray(),
            'bookshelf' => BookDAO::fetch_bookcopy_essentially_by('id', intval(htmlspecialchars($_POST['id']))),
        };
    }

    protected function persist_post_to_session($errors) {
        $_SESSION['form_data'] = $_POST;
        $_SESSION['errors'] = $errors;
    }

    protected function operation_failed(
        array $errors,
        string $register_type = null,
        string $fail_title = self::FAIL_TITLE,
        string $error_warning = self::ERROR_WARNING) {
            $register_type = $register_type ?? $this -> register_type;
            parent::operation_failed($errors, $register_type, $fail_title, $error_warning);
    }

    protected function operation_succeed(&$args){
        try {
            if ($_POST['element_type'] == 'user') 
                {PeopleDAO::delete_reader(intval($_POST['id']), $_SESSION['user_id']);}
            else if ($_POST['element_type'] === 'bookcopy') 
                {BookDAO::delete_book_copy(intval($_POST['id']), $_SESSION['user_id']);}

            $args['success_body'] = $this -> unordered_register_data($args['element_data']);
            parent::operation_succeed($args);
        }
        catch (Exception $e){
            echo "Puxa vida, desculpe-nos. Houve um erro em nosso sistema!".
            error_log($e -> getMessage());
        }
    }

    protected function handle_errors() : array {
        $errors = array();
        if (empty(self::get_element()))
            {$errors['invalid_element'] = 'Entidade não encontrada.';}
        return $errors;
    }

    protected function show_confirmation() {
        InterfaceManager::echo_html_head("GABi | Confirmação de exclusão", 'manager');
        echo InterfaceManager::system_logo('manager');

        $element = $this->get_element();
        echo "
        <div id='deletion'>    
            <div id='deletion_data'>
                <h2>Confirmação de Exclusão</h2>
                " . $this->unordered_register_data($element) . "
                <p>Efetivar?</p>
            </div>
            <div id='deletion_form'>
                <form method='post' action='delete_manager.php'>
                    <input type='hidden' name='id' value='" . htmlspecialchars($_POST['id']) . "'>
                    <input type='hidden' name='element_type' value='" . htmlspecialchars($_POST['element_type']) . "'>
                    <input type='hidden' name='deletion_step' value='implementation'>
                    <button id='delete_element_button' class='back_buttons' type='submit'>&#128077; | SIM</button>
                </form>
            </div>
            <div id='cancel_form'>
                <form method='post' action='".$_POST['element_type']."_element_detail.php'>
                    <input type='hidden' name='id' value='".$_POST['id']."'>
                    <input 
                        id='cancel_button' 
                        class='back_buttons'
                        type='submit' 
                        value='&#10060; | CANCELAR'>
                </form>
            </div>
            <div id='back_menu'>
        ";
            echo InterfaceManager::back_to_menu_button();
        echo "</div></div>";
        InterfaceManager::echo_html_tail();
        // exit;
    }
    

    protected function unordered_register_data(array $element_data): string {
        $element_name = match (htmlspecialchars($_POST['element_type'])){
            'user' => 'Leitor',
            'bookshelf' => 'Exemplar',
        };
        $complement = htmlspecialchars($_POST['deletion_step']) === 'confirmation' ? '<em>a ser</em>' : '';

        $fields = ['name' => 'Nome', 'phone' => 'Telefone', 'patr.' => 'Patrimônio', 'título' => 'Título'];
        $list = "<p>$element_name $complement removido:</p><ul>";
        foreach($fields as $f => $v) {
            if (isset($element_data[$f]))
                {$list .= "<li><span class='data_header'>$v:</span> " . $element_data[$f] . "</li>";}
        }
           
        $list .= "</ul>";
        return $list;
    }
    
}

$management = new DeleteManager();
$management -> manage_post_variable();   

?>