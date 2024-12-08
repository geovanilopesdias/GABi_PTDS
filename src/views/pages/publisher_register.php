<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class PublisherRegister extends Register{
    const REGISTER_TYPE = 'publisher';
    
    public function __construct(){}
    
    public function echo_structure(
        string $register_type = self::REGISTER_TYPE){
            parent::echo_structure($register_type);
    }

    protected function echo_register_form(){
        $form_data = $_SESSION['form_data'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['form_data'], $_SESSION['errors']);
        echo "
            <div id='register_form'>
                <form class='register' action='".self::REGISTER_TYPE."_register_manager.php' method='post'>
                    <input type='text' name='name' placeholder='Nome da editora' value='".(htmlspecialchars($form_data['title'] ?? ''))."' required/></br>".
                    InterfaceManager::search_input_disclaimer("Apenas o nome, não escreva 'editora'.").
                    ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_name']) : '') .
                    InterfaceManager::register_button()."
                </form>
            </div>
        ";
    }    
}

$search = new PublisherRegister();
$search -> echo_structure();

?>