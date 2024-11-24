<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class UserRegister extends Register{
    const REGISTER_TYPE = 'user';
    
    function __construct(){}
    
    public function echo_structure(
        string $register_type = self::REGISTER_TYPE){
            parent::echo_structure($register_type);
    }

    protected function echo_register_form(){
        $form_data = $_SESSION['form_data'] ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['form_data'], $_SESSION['errors']);
        
        $radio_options = [
            ['id' => 'stu', 'content' => 'Estudante'],
            ['id' => 'tea', 'content' => 'Professor'],
        ];

        echo "
            <div id='register_form'>
                <form class='register' action='".self::REGISTER_TYPE."_register_manager.php' method='post'>".
                    InterfaceManager::input_radio_group(
                        $radio_options, 'role', 'Cadastro de:', 'radio')."</br>".
                    InterfaceManager::input_checkbox_single('can_borrow', 'Poderá emprestar (válido apenas para professores)', '', false)."</br>
                    <input type='text' name='name' placeholder='Nome' value='".(htmlspecialchars($form_data['name']) ?? '')."' autofocus required/><br>".
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_name']) : '') ."
                    
                    <input type='text' id='phone' name='phone' placeholder='Telefone' value='".(htmlspecialchars($form_data['phone']) ?? '519')."' required/><br>".
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_phone']) : '') .
                    InterfaceManager::classroom_selector(false)."</br>".
                    InterfaceManager::register_button().
                "</form>
            </div>
        ";
    }
    
}

$search = new UserRegister();
$search -> echo_structure();

?>