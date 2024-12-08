<?php

require_once(__DIR__ . '/../../managers/interface_mng.php');

abstract class FormManager{
    const PAGE_TYPE = 'manager';

    protected abstract function persist_post_to_session($errors);
    protected abstract function handle_errors();
    
    public function manage_post_variable(){
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    protected function operation_failed(
        array $errors, string $register_type, string $fail_title, string $error_warning){
        
        if (isset($_POST['action'])) 
            {header("refresh:5; url=loan_element_detail.php");}
        else if (isset($_POST['login']))
            {header("refresh:5; url=login.php");}
        else
            {header("refresh:5; url=$register_type"."_register.php");}
        
        $register_type = match ($register_type) {
            'user' => 'leitor',
            'classroom' => 'turma',
            'loan' => 'empréstimo',
            'opus' => 'obra',
            'edition' => 'edição',
            'book', 'bookcopy' => 'exemplar',
            'publisher' => 'editora',
            'writer' => 'autor',
            'login' => 'acesso',
        };

        InterfaceManager::echo_html_head("GABi | $fail_title", self::PAGE_TYPE);
        echo InterfaceManager::system_logo(self::PAGE_TYPE);
            echo "<div id'failed'>";
                echo "<h2>$error_warning</h2>";
                echo "<p>O que houve: cadastro de $register_type recusado pelos motivos abaixo:</p>";
                echo "<ul>";
                    foreach($errors as $error) echo "<li>$error</li>";
                echo "</ul>";
                echo "<p>Em instantes, serás redirecionado para tentar novamente!</p>";
            echo "</div>";
        InterfaceManager::echo_html_tail();

        $this -> persist_post_to_session($errors);
        exit();
    }

    protected function operation_succeed(mixed &$args) {
        InterfaceManager::echo_html_head("GABi | ".$args['success_title'], self::PAGE_TYPE);
        echo InterfaceManager::system_logo(self::PAGE_TYPE);
        echo "<div id'success'>";
        echo "<h2>".$args['success_message']."</h2>";
        echo $args['success_body'] ?? '';
        if(empty($_POST['action']) and empty($_POST['deletion_step']))
            {echo InterfaceManager::back_to_register_button($args['register_type']);}
        echo InterfaceManager::back_to_menu_button();
        echo "</div>";
        InterfaceManager::echo_html_tail();
        exit;
    }
}
      

?>