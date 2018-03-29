<?php
    require_once('lib/secure.php');
    require_once('lib/templates.php');
    require_once('lib/DBConn.php');
    require_once('lib/ext.php');

    $context = new Context();
    $db = new DBConn();

    if(!$action){
        $context->title = "Datos generales / General information";
        
        $sql = "select * from clientes where id = $_SESSION[SORTCLIENT]";
        $context->client = $db->getObject($sql);
        
        $sql ="select * from contactos where Activo = 1 and cliente_id = $_SESSION[SORTCLIENT]";
        $context->contacts = $db->getArray($sql);
        
        $sql = "select * from clientes_cuentas where Activo = 1 and cliente_id = $_SESSION[SORTCLIENT]";
        $context->banks = $db->getArray($sql);
        
        RenderTemplate('templates/account.tpl.php', $context, 'templates/base.php');

    }elseif($action == "password"){
        
        if($new_pwd && $confirm_pwd && $new_pwd == $confirm_pwd){
            $sql = "update contactos set Password = MD5('$new_pwd') where id = $_SESSION[SORTUSER]";
            $db->execute($sql);
        }else{
            die("Contraseñas no coinciden / Passwords does not match");
        }
        sleep(1);
    }