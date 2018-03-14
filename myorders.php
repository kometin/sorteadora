<?php

require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    $context->title = "Lista de servicios / Order list";
    
    $context->params[] = array("Header" => "#", "Width" => "40", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Details", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Fecha/Date", "Width" => "100", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Numero parte/Part Number", "Width" => "100", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Folio/Order ID", "Width" => "100", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Descripción/Description", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Total partes/Total pieces", "Width" => "120", "Attach" => "txt", "Align" => "right", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Estatus", "Width" => "100", "Attach" => "txt", "Align" => "center", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Show", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    
    RenderTemplate('templates/myorders.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "details"){
    
    $sql = "select * from ordenes o "
            . "join clientes c on c.id = o.cliente_id "
            . "join servicios s on s.id = o.servicio_id "
            . "join orden_factores f on f.orden_id = o.id "
            . "where o.id = $id";
    $context->data = $db->getArray($sql);
    
    RenderTemplate('templates/myorders.details.php', $context);
    
}