<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class UserRegister extends Register{
    const REGISTER_TYPE = 'user';
    
    function __construct(){}
    
    public function echo_structure(
        string $search_type = self::REGISTER_TYPE){
            parent::echo_structure($search_type);
    }

    protected function echo_register_form(){
        $radio_options = [
            ['id' => 'stu', 'content' => 'Estudante'],
            ['id' => 'tea', 'content' => 'Professor'],
        ];

        echo "
            <div id='register_form'>
                <form class='register' action='".self::REGISTER_TYPE."_register_manager.php' method='post'>".
                    InterfaceManager::input_radio_group(
                        $radio_options, 'role', 'Cadastro de:', 'radio')."</br>".
                    InterfaceManager::input_checkbox_single('can_loan', 'Poderá emprestar (válido apenas para professores)', '', false)."</br>
                    <input type='text' name='name' placeholder='Nome' autofocus required/><br>
                    <input type='text' name='phone' placeholder='Telefone' required/><br>".
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