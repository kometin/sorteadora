<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');
require_once('lib/uploader.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    
    $context->title = "Opciones del sistema";
    
    $context->params = getParams();
    
    RenderTemplate('templates/config.tpl.php', $context, 'templates/base.php');
    
}elseif ($action == "save") {
    
    if(!empty($_FILES)){
        $uploader = new Uploader($_FILES, "uploads/", Uploader::SAME_NAME);
        if($uploader->Upload()){
            $param[] = $uploader->getUploaded(0)->PATH;
        }
    }else{
        $param[] = "";
    }
    
    foreach($param as $i => $p){
        $sql = "update parametros set Valor = '$p' where ID_Param = " . ($i + 1);
        $db->execute($sql);
    }
    
    sleep(1);
    
}

