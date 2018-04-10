<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    if(!empty($_POST)){
        $sql = "select id as ID, Rol, CONCAT_WS(' ', Nombre, Paterno, Materno) as Nombre "
                . "from usuarios where Estatus = 1 "
                . "and Correo = '$email' and Password = MD5('$pwd')";
        if($data = $db->getObject($sql)){
            $_SESSION[SORTUSER] = $data->ID;
            $_SESSION[SORTNAME] = $data->Nombre;
            $_SESSION[SORTROLE] = $data->Rol;
        }else{
            
            $sql = "select c.id as CLIENT, c.Empresa, con.id as ID, con.Nombre "
                . "from contactos con "
                . "join clientes c on c.id = con.cliente_id "
                . "where c.Estatus = 1 and con.Activo = 1 and "
                . "Correo = '$email' and Password = MD5('$pwd')";
            if($data = $db->getObject($sql)){
                
                $_SESSION[SORTUSER] = $data->ID;
                $_SESSION[SORTCLIENT] = $data->CLIENT;
                $_SESSION[SORTCOMP] = $data->Empresa;
                $_SESSION[SORTNAME] = $data->Nombre;
            }
        }
        Header('location: ' . getDomain());
    }
    
    RenderTemplate('templates/login.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "logout"){
    session_unset();
    session_destroy();
    
    Header('location: ' . getDomain());
    
}elseif($action == "setsession"){
    $sql = "select c.id as CLIENT, c.Empresa, con.id as ID, con.Nombre "
        . "from contactos con "
        . "join clientes c on c.id = con.cliente_id "
        . "where c.Estatus = 1 and con.Activo = 1 and "
        . "Correo = '$email' and Password = MD5('$pwd')";
    if($data = $db->getObject($sql)){

        $_SESSION[SORTUSER] = $data->ID;
        $_SESSION[SORTCLIENT] = $data->CLIENT;
        $_SESSION[SORTCOMP] = $data->Empresa;
        $_SESSION[SORTNAME] = $data->Nombre;
    }
}

