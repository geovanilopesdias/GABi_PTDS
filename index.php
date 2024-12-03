<?php

require_once('src/managers/interface_mng.php');

InterfaceManager::echo_html_head('GABI', 'index');
echo "
    <div id='index'>
        <div id='gabi_logo'>
            <img id='gabi_logo_index' class='gabi_logo' src='src/views/images/gabi_logo.png'/>
        </div>
        <div id='gabi_slogan'>
            <h1>Ideal para <em>biblios</em> pequenas;</h1>
            <h1>Ótimo à sua <em>grande</em> escola!</h1>
        </div>
        
        <div id='door'>
            <a href='src/views/pages/login.php'>
                <div id='letters'>Acessar</div>
                <div id='handle'>⚪</div>
            </a>
        </div>
    </div>
";
InterfaceManager::echo_html_tail();

?>