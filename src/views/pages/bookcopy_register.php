<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');
require_once(__DIR__ . '/register.php');

final class BookRegister extends Register{
    const REGISTER_TYPE = 'edition';
    
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
                    <fieldset><legend>Dados da Obra</legend>
                        <input type='text' name='title' placeholder='Título' value='".(htmlspecialchars($form_data['title']) ?? '')."' required/></br>".
                        ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_title']) : '') ."

                        <input type='number' name='original_year' placeholder='Ano original da obra' value='".(htmlspecialchars($form_data['original_year']) ?? '')."' required/></br>".
                        ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_original_year']) : '') ."

                        <input type='text' name='ddc' placeholder='CDD' value='".(htmlspecialchars($form_data['ddc']) ?? '')."'/></br>".
                        ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_asset_code']) : '') ."

                        <input type='text' name='alternative_url' placeholder='Weblink (http://example.com/file.pdf)' value='".(htmlspecialchars($form_data['alternative_url']) ?? '')."'/></br>".
                        ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_alternative_url']) : '') ."
                    </fieldset>
                    <fieldset><legend>Dados do Autor</legend>
                        <input type='text' name='author_name' placeholder='Nome dos autores' value='".(htmlspecialchars($form_data['author_name']) ?? '')."' /></br>".
                        InterfaceManager::search_input_disclaimer('Separe diferentes autores com vírgulas!') .
                        ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_author_name']) : '') ."

                        <input type='text' name='author_birth_year' placeholder='Nascimento dos autores' value='".(htmlspecialchars($form_data['author_birth_year']) ?? '')."' /></br>".
                        InterfaceManager::search_input_disclaimer('Separe diferentes autores com vírgulas!') .
                        ((!empty($errors)) ? InterfaceManager::search_input_disclaimer($errors['invalid_author_birth_year']) : '') ."
                    </fieldset>
                    <fieldset><legend>Dados da Edição</legend>
                        
                    </fieldset>
                    <fieldset><legend>Dados do Exemplar</legend>".
                        InterfaceManager::input_checkbox_single('multiple_ordered_asset_codes', 'Criar múltiplos exemplares', '', false).
                        InterfaceManager::search_input_disclaimer('Esta seleção cadastra exemplares com patrimônios gerados ordenadamente a partir do informado abaixo!')."
                        <input type='text' name='asset_code' placeholder='Patrimônio' value='".(htmlspecialchars($form_data['asset_code']) ?? '')."' required/></br>".
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