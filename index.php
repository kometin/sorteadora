<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();

if(!$action){
    
    $context->title = "Bienvenido";
    
    RenderTemplate('templates/index.tpl.php', $context, 'templates/base.php');
    
}