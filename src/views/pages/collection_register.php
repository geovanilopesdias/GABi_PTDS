<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class CollectionRegister extends Register{
    const REGISTER_TYPE = 'collection';
    
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
                    InterfaceManager::publisher_selector(true)."<br>
                    <input type='text' name='name' placeholder='Nome' value='".(htmlspecialchars($form_data['name'] ?? ''))."' required/></br>".
                    ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_name']) : '') .
                    InterfaceManager::register_button()."
                </form>
            </div>
        ";
    }
    
}

$search = new CollectionRegister();
$search -> echo_structure();

?>