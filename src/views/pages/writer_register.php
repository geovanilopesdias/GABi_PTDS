<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class WriterRegister extends Register{
    const REGISTER_TYPE = 'writer';
    
    function __construct(){}
    
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
                <h2>Cadastro de Autores</h2>
                <form class='register' action='".self::REGISTER_TYPE."_register_manager.php' method='post'>
                    <input type='text' name='name' placeholder='Nome do autor(a)' value='".(htmlspecialchars($form_data['name']) ?? '')."' autofocus/></br>".
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_name']) : '') ."

                    <input type='number' name='birth_year' placeholder='Ano de nascimento' value='".(htmlspecialchars($form_data['birth_year']) ?? '')."' /></br>".
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_birth_year']) : '') .
                    InterfaceManager::register_button()."
                </form>
            </div>
        ";
    }
    
}

$search = new WriterRegister();
$search -> echo_structure();

?>