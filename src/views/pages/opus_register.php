<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class OpusRegister extends Register{
    const REGISTER_TYPE = 'opus';
    
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
                <form class='register' action='".self::REGISTER_TYPE."_register_manager.php' method='post'>".
                    InterfaceManager::writer_selector(true)."<br>

                    <input type='text' name='title' placeholder='Título' value='".(htmlspecialchars($form_data['title']) ?? '')."' required/></br>".
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_title']) : '') ."

                    <input type='number' name='original_year' placeholder='Ano original da obra' value='".(htmlspecialchars($form_data['original_year']) ?? '')."' required/></br>".
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_original_year']) : '') ."

                    <input type='text' name='ddc' placeholder='CDD' value='".(htmlspecialchars($form_data['ddc']) ?? '')."'/></br>".
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_asset_code']) : '') ."

                    <input type='text' name='alternative_url' placeholder='Weblink (http://example.com/file.pdf)' value='".(htmlspecialchars($form_data['alternative_url']) ?? '')."'/></br>".
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_alternative_url']) : '') .
                    InterfaceManager::register_button()."
                </form>
            </div>
        ";
    }
    
}

$search = new OpusRegister();
$search -> echo_structure();

?>