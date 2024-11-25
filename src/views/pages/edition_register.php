<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class BookRegister extends Register{
    const REGISTER_TYPE = 'book';
    
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
                <form class='register' action='".self::REGISTER_TYPE."_register_manager.php' method='post'>
                    <fieldset><legend>Dados do Exemplar</legend>
                        <input type='number' name='asset_code' placeholder='Patrimônio' value='".(htmlspecialchars($form_data['asset_code']) ?? '')."' required/></br>".
                        InterfaceManager::search_input_disclaimer('Para vários números distintos, separe-os por vírgula').
                        ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_asset_code']) : '') ."
                    </fieldset>".
                    InterfaceManager::register_button().
                "</form>
            </div>
        ";
    }
    
}

$search = new BookRegister();
$search -> echo_structure();

?>