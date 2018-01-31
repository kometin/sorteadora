<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();

if(!$action){
    if(!empty($_POST)){
        
        $_SESSION[SORTUSER] = 1;
        
        Header('location: ' . getDomain());
    }
    
    RenderTemplate('templates/login.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "logout"){
    session_unset();
    session_destroy();
    
    Header('location: ' . getDomain());
}

