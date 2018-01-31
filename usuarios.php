<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    $context->title = "Lista de usuarios";
    
    $context->params[] = array("Header" => "#", "Width" => "40", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Editar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Borrar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Nombre", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
   // $context->params[] = array("Header" => "Tipo", "Width" => "100", "Attach" => "cmb", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Correo", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    
    RenderTemplate('templates/usuarios.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "form"){
    
    if($id){
        $context->id=$id;
        $sql="SELECT * FROM usuarios WHERE id=$id";
        $context->data=$db->getArray($sql);
    }
    RenderTemplate('templates/usuarios.form.php', $context);
    
}elseif($action == "save"){
    
    if($id){
        if($Password)
            $pwd="Password='".Encrypt($Password)."',";
        $sql = "UPDATE usuarios set "
                . "Nombre = '$name', "
                . "Paterno = '$patern', "
                . "Materno = '$matern', "
                . "Fecha_Alta = NOW(), "
                . "Rol = '$rol', "
                . "Correo = '$mail', "
                 . $pwd
                . "updated_at = NOW(), "
                . "updated_by = '{$_SESSION['ID_Usuario']}'"
                . " WHERE id=$id ";              
    }else{
        $PWD= Encrypt($Password);        
        $sql = "insert into usuarios set "
                . "Nombre = '$name', "
                . "Paterno = '$patern', "
                . "Materno = '$matern', "
                . "Fecha_Alta = NOW(), "
                . "Rol = '$rol', "
                . "Correo = '$mail', "
                . "Password = '" . $PWD . "', "
                . "Estatus = 1, "
                . "updated_at = NOW(), "
                . "Updated_by = '$_SESSION[SORTUSER]'";
    }
    $db->execute($sql);
    sleep(1);
}elseif($action == "del"){
    if($id)
        $db->execute ("UPDATE usuarios SET Estatus=0 WHERE id=$id");
    
}elseif($action == "ckc"){   
    if($id)
        $and=" AND id!='$id'";    
    if($cor)
       $elemento=$db->getOne("SELECT * FROM usuarios WHERE Correo='$cor' AND Estatus=1 $and ");
    if($elemento)
        echo $elemento;    
}

