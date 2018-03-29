<?php

require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    $context->title = "Resultados / Order results";
    $sql = "select o.id, Tipo_Medicion from servicios s join ordenes o on o.servicio_id = s.id where o.Clave = '$order'" . ($_SESSION[SORCLIENT] ? " and cliente_id = " . $_SESSION['SORTCLIENT']: "");
    if($data = $db->getObject($sql)){
        $context->order = $order;
        $context->params[] = array("Header" => "#", "Width" => "40", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
        switch($data->Tipo_Medicion){
            case 1:
                $sql = "select Factor from orden_factores where Activo = 1 and orden_id = $data->id";
                $factors = $db->getArray($sql);
                $context->params[] = array("Header" => "Lote/Lote", "Width" => (count($factors) > 5 ? 120 : "*"), "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                $context->params[] = array("Header" => "Fecha/Date", "Width" => (count($factors) > 5 ? 100 : "*"), "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                $context->params[] = array("Header" => "Cantidad/Quantity", "Width" => "100", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                foreach($factors as $f)
                    $context->params[] = array("Header" => $f[Factor], "Width" => "100", "Attach" => "txt", "Align" => "right", "Sort" => "int", "Type" => "ed");
                $context->params[] = array("Header" => "Rejected", "Width" => "100", "Attach" => "txt", "Align" => "right", "Sort" => "int", "Type" => "ed");
                $context->params[] = array("Header" => "Total OK", "Width" => "100", "Attach" => "txt", "Align" => "right", "Sort" => "int", "Type" => "ed");

                break;
            case 2:
                $sql = "select Factor from orden_factores where Activo = 1 and orden_id = $data->id";
                $factors = $db->getArray($sql);
                $context->params[] = array("Header" => "Lote/Lote", "Width" => (count($factors) > 5 ? 120 : "*"), "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                $context->params[] = array("Header" => "Fecha/Date", "Width" => (count($factors) > 5 ? 100 : "*"), "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                $context->params[] = array("Header" => "Muestra/Sample", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                foreach($factors as $f)
                    $context->params[] = array("Header" => $f[Factor], "Width" => "100", "Attach" => "txt", "Align" => "right", "Sort" => "int", "Type" => "ed");
                $context->params[] = array("Header" => "Regla/Rule", "Width" => (count($factors) > 5 ? 100 : "*"), "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                $context->params[] = array("Header" => "Specification", "Width" => (count($factors) > 5 ? 100 : "*"), "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                $context->params[] = array("Header" => "Result", "Width" => "100", "Attach" => "txt", "Align" => "right", "Sort" => "int", "Type" => "ed");
                $context->params[] = array("Header" => "Judgement", "Width" => "100", "Attach" => "cmb", "Align" => "left", "Sort" => "str", "Type" => "ed");
                break;
            case 3:
                $context->params[] = array("Header" => "Location", "Width" => "200", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                $context->params[] = array("Header" => "Día/Day", "Width" => "100", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                $context->params[] = array("Header" => "General information", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
                break;
        }
    }
    
    RenderTemplate('templates/results.tpl.php', $context, 'templates/base.php');
}