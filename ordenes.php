<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    $context->title = "Ordenes de servicios";
    
    $context->params[] = array("Header" => "#", "Width" => "40", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
  //  $context->params[] = array("Header" => "Ver", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Editar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Borrar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Fecha orden ", "Width" => "100", "Attach" => "txt", "Align" => "center", "Sort" => "str", "Type" => "ed");

    $context->params[] = array("Header" => "Empresa", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");

    $context->params[] = array("Header" => "No partes", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Descripción", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Folio", "Width" => "*", "Attach" => "txt", "Align" => "right", "Sort" => "str", "Type" => "ed");
   // $context->params[] = array("Header" => "Fecha cierre", "Width" => "100", "Attach" => "txt", "Align" => "center", "Sort" => "str", "Type" => "ed");

   // $context->params[] = array("Header" => "Alta/Baja", "Width" => "120*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    
    RenderTemplate('templates/ordenes.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "form" || $action == "ver"){
    $context->ver=0;
    $sql="SELECT * FROM servicios "               
            . "WHERE Activo=1";
    $context->servicios=$db->getArray($sql);
    $sql="SELECT * FROM clientes "               
            . "WHERE Estatus=1";
    $context->clientes=$db->getArray($sql);    
    if($id){
        $context->id=$id;
        $sql="SELECT * FROM ordenes o"
                . " INNER JOIN clientes c ON c.id=o.cliente_id "               
                . " WHERE o.id=$id";
        $context->data=$db->getArray($sql);
        
        $context->serviciosguardados=explode(",",$db->getOne("SELECT GROUP_CONCAT(s.id) FROM ordenes_servicios o
                 INNER JOIN servicios s ON s.id=o.servicio_id
                WHERE orden_id=$id GROUP BY orden_id "));
        
        
    } else {
        $context->ur=array();   
    }
    if($action=='ver')
        $context->ver=1;
    RenderTemplate('templates/ordenes.form.php', $context);
    
}elseif($action == "save"){
    
    if($id){
        $sql = "UPDATE ordenes set "
                ."cliente_id=$cliente_id, "
                . "Numero_Parte='$Numero_parte',"   
                . "Descripcion='$Descripcion', "
                . "Herramientas='$Herramientas', " 
                . "Medidores ='$Medidores', " 
                . "Quimicos='$Quimicos', " 
                . "Otros='$Otros', " 
                . "Tiempo_x_Parte='$Tiempo_x_parte'," 
                . "Total_Partes='$Total_partes',"
                . "Fecha_Cierre='".SimpleDate($Fecha_Cierre)."',"                                     
                . "updated_at = NOW(), "
                . "updated_by = '{$_SESSION['SORTUSER']}'"
                . " WHERE id=$id ";        
        $db->execute($sql);
        
        $sql="DELETE FROM ordenes_servicios WHERE orden_id=$id";
        $db->execute($sql);                
        foreach($Servicios as $ser){
            $sql="insert into ordenes_servicios(orden_id,servicio_id)VALUES($id,'$ser')";
            $db->execute($sql);
        }                
    }else{
        $consecutivo=$db->getOne("SELECT Folio from ordenes  order by id DESC limit 1");
        if($consecutivo != 0)
            $consecutivo=$consecutivo+1;
        else
            $Folio=1;
        
        $sql = "insert into ordenes set "
                . "cliente_id = '$cliente_id'," 
                . "Fecha_Orden=now(), "                
                . "Numero_Parte='$Numero_parte',"   
                . "Descripcion='$Descripcion', "
                . "Folio='$Folio', " 
                . "Herramientas='$Herramientas', " 
                . "Medidores ='$Medidores', " 
                . "Quimicos='$Quimicos', " 
                . "Otros='$Otros', " 
                . "Tiempo_x_Parte='$Tiempo_x_parte'," 
                . "Total_Partes='$Total_partes',"
                . "Fecha_Cierre='".SimpleDate($Fecha_Cierre)."',"     
                . "updated_at = NOW(), "
                . "Estatus=1, "
                . "updated_by = '{$_SESSION['SORTUSER']}' ";
        $db->execute($sql);
        $idOrden=$db->getOne("SELECT id from ordenes  order by id DESC limit 1");
        foreach($Servicios as $ser){
            $sql="insert into ordenes_servicios(orden_id,servicio_id)VALUES($idOrden,'$ser')";
            $db->execute($sql);
        }
    }

    sleep(1);

}elseif($action == "del"){
    if($id)
        $db->execute ("UPDATE ordenes SET Estatus=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE id=$id");
    
}