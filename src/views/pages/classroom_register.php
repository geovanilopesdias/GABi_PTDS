<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class ClassroomRegister extends Register{
    const REGISTER_TYPE = 'classroom';
    
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
                <form class='register' action='".self::REGISTER_TYPE."_register_manager.php' method='post'>
                    <input type='text' name='names' placeholder='Nomes das turmas' value='".(htmlspecialchars($form_data['name']) ?? '')."' autofocus required/><br>".
                    InterfaceManager::search_input_disclaimer('Separe-as com vírgulas') .
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_name']) : '') ."
                    
                    <input type='number' id='year' name='year' placeholder='Ano' value='".date("Y")."' required/><br>".
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_year']) : '') .
                    InterfaceManager::register_button().
                "</form>
            </div>
        ";
    }
    
}

$search = new ClassroomRegister();
$search -> echo_structure();

?>