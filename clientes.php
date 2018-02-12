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
   // $context->params[] = array("Header" => "Nombre", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Empresa", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Razón social", "Width" => "150", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");

    $context->params[] = array("Header" => "RFC", "Width" => "150", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
   // $context->params[] = array("Header" => "Correo", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Dirección", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");

    $context->params[] = array("Header" => "Teléfono", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
   // $context->params[] = array("Header" => "Alta/Baja", "Width" => "120*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Contactos", "Width" => "80", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    
    RenderTemplate('templates/clientes.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "contact"){
    if($id){
        $context->id=$id;
        $sql="SELECT * FROM contactos WHERE cliente_id=$id AND Activo=1";
        $context->contactos=$db->getArray($sql);
    }

    RenderTemplate('templates/contactos.php', $context);
    
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
        $sql = "UPDATE clientes set "
                 . "RFC = '$RFC', "
                . "Empresa = '$Empresa', " 
                . "RazonSocial = '$RazonSocial', "
                 . "Direccion = '$Direccion', "
                . "Telefono = '$Telefono', "
                . "updated_at = NOW(), "
                . "updated_by = '{$_SESSION['SORTUSER']}'"
                . " WHERE id=$id ";        
    }else{
        $sql = "insert into clientes set "
                 . "RFC = '$RFC', "
                . "Empresa = '$Empresa', " 
                . "RazonSocial = '$RazonSocial', "
                . "Direccion = '$Direccion', "
                . "Telefono = '$Telefono', "
                . "Estatus=1,   "
                . "Fecha_Alta = NOW(), "
                . "updated_by = '{$_SESSION['SORTUSER']}' ";
    }
    $db->execute($sql);
    sleep(1);

}elseif($action == "savecontact"){
    $x=0;
    foreach($Nombre as $Nom){
        
        $PWD='';
        if($Password[$x]=='')       
            $PWD="Password = NULL, ";
        elseif($Password[$x]!=0)       
            $PWD="Password = '". md5($Password[$x])."' , ";
        
        if($id[$x]!=''){
            $sql = "update contactos set "
            . "Tipo = '$Tipo[$x]', "
             . "Nombre = '$Nom', "
            . "Correo = '$Correo[$x]', " 
            . $PWD
             . "Principal = '$Principal[$x]', "                
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' "
            . " WHERE id=$id[$x]";        
            $db->execute($sql);
        }elseif($Nom!=''){
           $sql = "insert into contactos set "
            . "Tipo = '$Tipo[$x]', "
             . "Nombre = '$Nom', "
            . "Correo = '$Correo[$x]', " 
            .$PWD
             . " Principal = '$Principal[$x]', " 
             . "cliente_id=$cid, "

            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' ";        
            $db->execute($sql);
        }
        $x++;
    }
    sleep(1);    
}elseif($action == "del"){
    if($id)
        $db->execute ("UPDATE clientes SET Estatus=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE id=$id");
}elseif($action == "delc"){
       if($id)
        $db->execute ("UPDATE contactos SET Activo=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE id=$id");
 
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
    
}elseif($action == "checacontacto"){
    foreach($Correo as $cor){
        if($cor){
           $correo=$db->getOne("SELECT Correo FROM contactos WHERE Correo='$cor' AND Activo=1 AND cliente_id!='$cid' ");    
        }
        if($correo!='')
            $existeCorreo[]=$correo;
    }
    if(count($existeCorreo)>0){
        $existeCorreo = array_unique($existeCorreo);
       if(count($existeCorreo)==1)
           echo "El correo ".$existeCorreo[0]." esta en uso en otro cliente";
       else
           echo "Los correos ".implode(',',$existeCorreo)." estan en uso  en otro cliente";
           
    }else{
        
        $temp='';
        foreach($Correo as $cor){
            if(contar_valores($Correo,$cor)>1)
                $existeCorreo[]=$cor;                       
        }
        if(count($existeCorreo)>0){
            $existeCorreo = array_unique($existeCorreo);
           if(count($existeCorreo)==1)
               echo "El correo ".$existeCorreo[0]." esta duplicado";
           else
               echo "Los correos".implode(',',$existeCorreo)." estan duplicados ";

        }        
    }
}
function contar_valores($a,$buscado)
 {
  if(!is_array($a)) return NULL;
  $i=0;
  foreach($a as $v)
   if($buscado===$v)
    $i++;
  return $i;
 }