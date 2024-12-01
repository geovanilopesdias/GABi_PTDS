<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class BookcopyRegister extends Register{
    const REGISTER_TYPE = 'bookcopy';
    
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
                <form class='register' action='".self::REGISTER_TYPE."_register_manager.php' method='post'>".
                    InterfaceManager::edition_selector()."
                    
                    <input type='number' min='0' pattern='\d+'
                        name='quantity' placeholder='Quantidade (vazio = 1)' value='".
                            (htmlspecialchars($form_data['quantity']) ?? '')."'></br>".
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_quantity']) : '') .
                            
                    InterfaceManager::input_checkbox_single('ordered_assets', 'cadastrar patrimônios ordenadamente', '', false)."<br>".
                    InterfaceManager::search_input_disclaimer("Para cadastro ordenado (ex.: 123, 124...), marque acima e informa abaixo o primeiro:") ."
                    <input type='text' name='asset_code' placeholder='Patrimônio(s)' value='".(htmlspecialchars($form_data['asset_code']) ?? '')."' required/>".
                    
                    InterfaceManager::search_input_disclaimer('Se desmarcada opção acima, separa os patrimônios com vírgula.').
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_asset_code']) : '') .
                    ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['incoerent_quantity_asset_list']) : '') .
                    
                    InterfaceManager::register_button()."
                </form>
            </div>
        ";
    }
    
}

$search = new BookcopyRegister();
$search -> echo_structure();

?>