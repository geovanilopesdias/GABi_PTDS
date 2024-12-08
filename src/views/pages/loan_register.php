<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class LoanRegister extends Register{
    const REGISTER_TYPE = 'loan';
    
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
                    InterfaceManager::reader_selector()."<br>".
                    ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['debtor_loaner']) : '') ."
                    
                    <input type='text' name='asset_code' placeholder='Patrimônios' value='".(htmlspecialchars($form_data['asset_code'] ?? ''))."' required/></br>".
                    InterfaceManager::search_input_disclaimer('Para vários exemplares, separe os valores com vírgulas.').
                    ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_asset_code']) : '') ."

                    <input type='date' name='loan_date' placeholder='Ano original da obra' value='".(htmlspecialchars($form_data['original_year'] ?? ''))."' required/></br>".
                    InterfaceManager::search_input_disclaimer('Seleciona acima o dia da retirada.').
                    ((!empty($errors)) ? InterfaceManager::error_input_disclaimer($errors['invalid_date']) : '') .

                    InterfaceManager::register_button()."
                </form>
            </div>
        ";
    }
    
}

$search = new LoanRegister();
$search -> echo_structure();

?>