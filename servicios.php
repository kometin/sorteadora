<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    $context->title = "Lista de servicios";
    
    $context->params[] = array("Header" => "#", "Width" => "40", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
  //  $context->params[] = array("Header" => "Ver", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Editar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Borrar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Servicio", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Tipo medición", "Width" => "120", "Attach" => "cmb", "Align" => "left", "Sort" => "str", "Type" => "ed");

    $context->params[] = array("Header" => "Alta/Baja", "Width" => "120*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    
    RenderTemplate('templates/servicios.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "form" || $action == "ver"){
    $context->ver=0;
    if($id){
        $context->id=$id;
        $sql="SELECT * FROM servicios WHERE id=$id";
        $context->data=$db->getArray($sql);
    }
    if($action=='ver')
        $context->ver=1;
    RenderTemplate('templates/servicios.form.php', $context);
    
}elseif($action == "save"){
    
    if($id){
        $sql = "UPDATE servicios set "
                . "Servicio = '$Servicio', "
                . "updated_at = NOW(), "
                . "updated_by = '{$_SESSION['SORTUSER']}'"
                . " WHERE id=$id ";        
    }else{
        $sql = "insert into servicios set "
                . "Servicio = '$Servicio', "
                . "Tipo_Medicion  = '$Tipo_Medicion', "
                . "updated_at = NOW(), "
                . "updated_by = '{$_SESSION['SORTUSER']}' ";
    }
    $db->execute($sql);
    sleep(1);

}elseif($action == "del"){
    if($id)
        $db->execute ("UPDATE servicios SET Activo=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE id=$id");
    
}elseif($action == "ckr"){
    if($id)
        $and=" AND id!='$id'";
    if($rfc)
       $elemento=$db->getOne("SELECT * FROM servicios WHERE Servicio='$Servicio' AND Activo=1 $and ");
    if($elemento)
        echo $elemento;
    
}