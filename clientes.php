<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    $context->title = "Lista de clientes";
    
    $context->params[] = array("Header" => "#", "Width" => "40", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Editar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Borrar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Nombre", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Empresa", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "RFC", "Width" => "150", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Correo", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Teléfono", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Dirección", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    
    RenderTemplate('templates/clientes.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "form" || $action == "ver"){
    $context->ver=0;
    if($id){
        $context->id=$id;
        $sql="SELECT * FROM clientes WHERE id=$id";
        $context->data=$db->getArray($sql);
    }
    if($action=='ver')
        $context->ver=1;
    RenderTemplate('templates/clientes.form.php', $context);
    
}elseif($action == "save"){
    
    if($id){
        if($Password)
            $pwd="Password='".Encrypt($Password)."',";
        $sql = "UPDATE clientes set "
                . "Nombre = '$Nombre', "
                 . "RFC = '$RFC', "
                . "Empresa = '$Empresa', " 
                . "Correo = '$Correo', "
                 . "Direccion = '$Direccion', "
                . "Telefono = '$Telefono', "
                 . $pwd
                . "updated_at = NOW(), "
                . "updated_by = '{$_SESSION['ID_Usuario']}'"
                . " WHERE id=$id ";        
    }else{
        $PWD= Encrypt($Password);
        $sql = "insert into clientes set "
                . "Nombre = '$Nombre', "
                 . "RFC = '$RFC', "
                . "Empresa = '$Empresa', " 
                . "Correo = '$Correo', "
                 . "Direccion = '$Direccion', "
                 . "Password ='$PWD',"
                . "Telefono = '$Telefono', "
                . "Estatus=1,   "
                . "Fecha_Alta = NOW(), "
                . "updated_by = '{$_SESSION['ID_Usuario']}' ";
    }
    $db->execute($sql);
    sleep(1);

}elseif($action == "del"){
    if($id)
        $db->execute ("UPDATE clientes SET Estatus=0 WHERE id=$id");
    
}elseif($action == "ckr"){
    if($id)
        $and=" AND id!='$id'";
    if($rfc)
       $existeRFC=$db->getOne("SELECT * FROM clientes WHERE RFC='$rfc' AND Estatus=1 $and");
    if($cor)
       $existeCorreo=$db->getOne("SELECT * FROM clientes WHERE Correo='$cor' AND Estatus=1 $and ");
    
    if($existeCorreo!='' && $existeRFC!='')
        echo "El correo y el RFC ya existen en otro cliente";
    else if($existeCorreo!='' && $existeRFC=='')
            echo "El correo ya existen en otro cliente";
    elseif($existeCorreo=='' && $existeRFC!='')
            echo "El RFC ya existen en otro cliente";
    
}