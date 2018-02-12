<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    $context->title = "Lista de operadores";
    
    $context->params[] = array("Header" => "#", "Width" => "40", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
  //  $context->params[] = array("Header" => "Ver", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Editar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Borrar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Nombre", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "RFC", "Width" => "150", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "No.SS", "Width" => "150", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");

    $context->params[] = array("Header" => "Telefono", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");

    $context->params[] = array("Header" => "Alta/Baja", "Width" => "120*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    
    RenderTemplate('templates/operadores.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "form" || $action == "ver"){
    $context->ver=0;
    if($id){
        $context->id=$id;
        $sql="SELECT * FROM operadores WHERE id=$id";
        $context->data=$db->getArray($sql);
    }
    if($action=='ver')
        $context->ver=1;
    RenderTemplate('templates/operadores.form.php', $context);
    
}elseif($action == "save"){
    
    if($id){
        $sql = "UPDATE operadores set "
                . "Nombre = '$Nombre', "
                . "Paterno = '$Paterno', "
                . "Materno = '$Materno', "
                 . "RFC = '$RFC', "
                 . "NSS = '$NSS', "
                 . "CURP = '$CURP', "
                 . "Direccion = '$Direccion', "
                . "Telefono = '$Telefono', "
                . "updated_at = NOW(), "
                . "updated_by = '{$_SESSION['SORTUSER']}'"
                . " WHERE id=$id ";        
    }else{
        $sql = "insert into operadores set "
                . "Nombre = '$Nombre', "
                . "Paterno = '$Paterno', "
                . "Materno = '$Materno', "
                 . "RFC = '$RFC', "
                 . "NSS = '$NSS', "
                 . "CURP = '$CURP', "
                 . "Direccion = '$Direccion', "
                . "Telefono = '$Telefono', "
                . "Fecha_Alta = NOW(), "
                . "updated_by = '{$_SESSION['SORTUSER']}' ";
    }
    $db->execute($sql);
    sleep(1);

}elseif($action == "del"){
    if($id)
        $db->execute ("UPDATE operadores SET Estatus=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE id=$id");
    
}elseif($action == "ckr"){
    if($id)
        $and=" AND id!='$id'";
    if($rfc)
       $elemento=$db->getOne("SELECT * FROM operadores WHERE RFC='$rfc' AND Estatus=1 $and ");
    if($elemento)
        echo $elemento;
    
}