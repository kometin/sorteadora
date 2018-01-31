<?php

require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    
    $context->title = "Reporte de nómina";
    
    $context->params[] = array("Header" => "#", "Width" => "40", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "RFC", "Width" => "80", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Operador", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Fecha", "Width" => "100", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Entrada", "Width" => "60", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Salida", "Width" => "60", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Horas", "Width" => "70", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed", "Sum" => true);
    $context->params[] = array("Header" => "Pago Hr.", "Width" => "70", "Attach" => "txt", "Align" => "left", "Sort" => "int", "Type" => "price");
    $context->params[] = array("Header" => "Hrs. extras dobles", "Width" => "70", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed", "Sum" => true);
    $context->params[] = array("Header" => "Pago Hr. Doble", "Width" => "70", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Hrs. extras triples", "Width" => "70", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Pago Hr. Triple", "Width" => "70", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Hrs. extras festivos", "Width" => "70", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Pago Hr. Festivos", "Width" => "70", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Pago Diario", "Width" => "70", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    
    RenderTemplate('templates/repnomina.tpl.php', $context, 'templates/base.php');
    
}
