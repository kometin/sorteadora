<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');
require_once('lib/uploader.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    
    $context->title = "Detalles de servicio / Order details";
    
    $sql = "select o.*, Empresa, Servicio, f.* from ordenes o "
            . "join clientes c on c.id = o.cliente_id "
            . "join servicios s on s.id = o.servicio_id "
            . "left join orden_factores f on f.orden_id = o.id and f.Activo = 1 "
            . "where o.Clave = '$order'";
    $context->data = $db->getArray($sql);

    if(!$context->data)
        Header('location: login.php');

    RenderTemplate('templates/confirm.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "continue"){
    
    $sql = "update ordenes set Estatus = 2, auth_at = NOW() where Estatus = 1 and Clave = '$order'";
    $db->execute($sql);
    
    Header('location: confirm.php?order='.$order);
}