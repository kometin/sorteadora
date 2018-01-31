<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    
    $context->title = "Opciones del sistema";
    
    $context->params = getParams();
    
    RenderTemplate('templates/config.tpl.php', $context, 'templates/base.php');
    
}elseif ($action == "save") {
    
    foreach($param as $i => $p){
        $sql = "update parametros set Valor = '$p' where ID_Param = " . ($i + 1);
        $db->execute($sql);
    }
    
    sleep(1);
    
}

