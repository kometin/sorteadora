<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    $context->title = "Órdenes de servicios";
    
    $context->params[] = array("Header" => "#", "Width" => "40", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
  //  $context->params[] = array("Header" => "Ver", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Editar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Borrar", "Width" => "50", "Attach" => "", "Align" => "center", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Fecha orden ", "Width" => "100", "Attach" => "txt", "Align" => "center", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Empresa", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Servicio", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "No. Parte", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
//    $context->params[] = array("Header" => "Descripción", "Width" => "*", "Attach" => "txt", "Align" => "left", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Folio", "Width" => "80", "Attach" => "txt", "Align" => "right", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Total", "Width" => "80", "Attach" => "txt", "Align" => "right", "Sort" => "str", "Type" => "ed");
    $context->params[] = array("Header" => "Config.", "Width" => "60", "Attach" => "", "Align" => "right", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Result.", "Width" => "60", "Attach" => "", "Align" => "right", "Sort" => "str", "Type" => "ro");
    $context->params[] = array("Header" => "Estatus", "Width" => "100", "Attach" => "txt", "Align" => "center", "Sort" => "str", "Type" => "ro");

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
        
        /*$context->serviciosguardados=explode(",",$db->getOne("SELECT GROUP_CONCAT(s.id) FROM ordenes_servicios o
                 INNER JOIN servicios s ON s.id=o.servicio_id
                WHERE orden_id=$id GROUP BY orden_id "));
        */
        
    } else {
        $context->ur=array();   
    }
    if($action=='ver')
        $context->ver=1;
    RenderTemplate('templates/ordenes.form.php', $context);
}elseif($action == "fac" || $action == "ver"){
    $context->idOrden=$id;
    $context->Factores=$db->getArray("SELECT * FROM orden_factores WHERE orden_id=".$id." AND Activo=1");
    if($tipo==1)
        RenderTemplate('templates/ordenes.fact1.php', $context);
    elseif ($tipo==2)
        RenderTemplate('templates/ordenes.fact2.php', $context);
    
  
}elseif($action == "save"){
    
    if($id){
        $sql = "UPDATE ordenes set "
                ."cliente_id=$cliente_id, "
                . "Numero_Parte='$Numero_parte',"   
                . "Descripcion='$Descripcion', "
                . "Herramientas='$Herramientas', "
                . "servicio_id=$servicio_id, " 
                . "Medidores ='$Medidores', " 
                . "Quimicos='$Quimicos', " 
                . "Otros='$Otros', " 
                . "Tiempo_x_Parte='$Tiempo_x_parte'," 
                . "Total_Partes='$Total_partes',"
                . "updated_at = NOW(), "
                . "updated_by = '{$_SESSION['SORTUSER']}'"
                . " WHERE id=$id ";        
        $db->execute($sql);
        /*
        $sql="DELETE FROM ordenes_servicios WHERE orden_id=$id";
        $db->execute($sql);                
        foreach($Servicios as $ser){
            $sql="insert into ordenes_servicios(orden_id,servicio_id)VALUES($id,'$ser')";
            $db->execute($sql);
        }       
         
         */         
    }else{
        $Folio=$db->getOne("SELECT IFNULL(MAX(Folio),0)+1 from ordenes  order by id DESC limit 1");
        
        $sql = "insert into ordenes set "
                . "cliente_id = '$cliente_id'," 
                . "Fecha_Orden=now(), "                
                . "Numero_Parte='$Numero_parte',"   
                . "Descripcion='$Descripcion', "
                . "servicio_id=$servicio_id, "
                . "Folio='$Folio', " 
                . "Herramientas='$Herramientas', " 
                . "Medidores ='$Medidores', " 
                . "Quimicos='$Quimicos', " 
                . "Otros='$Otros', " 
                . "Tiempo_x_Parte='$Tiempo_x_parte'," 
                . "Total_Partes='$Total_partes',"
                . "updated_at = NOW(), "
                . "Estatus=1, "
                . "updated_by = '{$_SESSION['SORTUSER']}' ";
        $db->execute($sql);
        
        $sql = "update ordenes set Clave = MD5(CONCAT_WS('+', id, cliente_id)) where Clave is null and cliente_id = $cliente_id";
        $db->execute($sql);
     /*   $idOrden=$db->getOne("SELECT id from ordenes  order by id DESC limit 1");
       foreach($Servicios as $ser){
            $sql="insert into ordenes_servicios(orden_id,servicio_id)VALUES($idOrden,'$ser')";
            $db->execute($sql);
        }
      
      */
    }

    sleep(1);
}elseif($action == "savefact"){
    $x=0;
    foreach($Factor as $Fac){
        if($id[$x]!=''&& $idOrden!=''){
            $sql = "update orden_factores SET "
                   
                   ." Factor='$Fac',"
                                            
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' "
            . " WHERE id=$id[$x]";        
            $db->execute($sql);
        }elseif($Fac!='' && $idOrden!=''){
           $sql = "insert into orden_factores SET "
                   . "orden_id=$idOrden, "
                   ." Factor='$Fac',"
                   ." Activo=1,"            
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' ";        
            $db->execute($sql);
        }
        $x++;
    }
    sleep(1);     
}elseif($action == "del"){
    if($id){
        $db->execute ("UPDATE ordenes SET Estatus=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE id=$id");
        $db->execute ("UPDATE orden_factores SET Activo=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE orden_id=$id");
        $db->execute ("UPDATE resultados SET Activo=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE orden_id=$id");

    }
}elseif($action == "delfac"){
    if($id){
        $db->execute ("UPDATE orden_factores SET Activo=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE id=$id");
        $db->execute ("UPDATE resultados SET Activo=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE orden_id=(SELECT orden_id FROM orden_factores WHERE id=$id)");
  
    }
}elseif($action == "delres3"){
    if($id)
        $db->execute ("UPDATE resultados SET Activo=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE id=$id");
}elseif($action == "DelFacRes1"){
    if($id){
        $db->execute ("UPDATE resultados SET Activo=0, updated_at=now(),updated_by = '{$_SESSION['SORTUSER']}' WHERE id=$id");
        //$db->execute ("UPDATE resultado_factores SET Activo=0 WHERE resultado_id=$id");
       if($id)
            $db->execute("DELETE FROM resultado_factores WHERE resultado_id=".$id);
        
    }
}elseif($action == "res"){
    if($tipo==3){
        $context->idOrden=$id;
        if($id)
        $context->Resultados=$db->getArray("SELECT *
                                            FROM resultados 
                                            WHERE orden_id=".$id." AND Activo=1");
        RenderTemplate('templates/ordenes.res3.php', $context);    
    }else{
        $context->idOrden=$id;
        $context->Factores=$db->getArray("SELECT * FROM orden_factores WHERE orden_id=".$id." AND Activo=1");
        $context->Resultados=$db->getArray("SELECT *
                                            FROM resultados 
                                            WHERE orden_id=".$id." AND Activo=1 order BY Fecha_Lote ASC");
        $context->Total_Partes=$db->getOne("SELECT Total_Partes FROM ordenes WHERE id=".$id);

 
        $ResFac=$db->getArray("SELECT RF.factor_id, Valor,  RF.id, RES.id as resultado_id
                            FROM resultado_factores RF
                            INNER JOIN resultados RES on RES.id=RF.resultado_id
                            WHERE orden_id=".$id. " AND RES.Activo=1 AND RF.Activo=1 ");
        if(count($ResFac)>0){
            foreach($ResFac as $res){
                $ResultadosFac[$res['resultado_id']][$res['factor_id']][]=$res['Valor'];
                $idsFac[$res['resultado_id']][$res['factor_id']]=$res['id'];

            }    
        }
        $context->idsFac=$idsFac;
        $context->ResultadosFac=$ResultadosFac;
        $context->idsFac=$idsFac;
    }
    if($tipo==1){        
        RenderTemplate('templates/ordenes.res1.php', $context);
    }elseif ($tipo==2)
        RenderTemplate('templates/ordenes.res2.php', $context);

    
 }elseif($action == "savefacres"){
    $x=0;
    $xx=0;
    $Factores=$db->getArray("SELECT * FROM orden_factores WHERE orden_id=".$idOrden." AND Activo=1");
    
    foreach($Lote as $L){
        if($id[$x]!=''&& $idOrden!=''){
            $sql = "update resultados SET "
                   ." Lote='$L',"
                   . "Fecha_Lote ='".SimpleDate($Fecha[$x])."',"
                   . "Cantidad ='".$Cantidad[$x]."',"                       
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' "
            . " WHERE id=$id[$x]";   
            $db->execute($sql);
            if($id[$x])
                $db->execute("DELETE FROM resultado_factores WHERE resultado_id=".$id[$x]);
                foreach($Factores as $Fac){
                    if($Fac['id']){
                        $sql = "insert into resultado_factores SET "
                                . "resultado_id=$id[$x], "
                                ." factor_id='".$Fac['id']."',"
                                . "Valor ='".$Factor[$xx]."'";        
                         $db->execute($sql);  
                         $xx++;
                    }                
                }                
                /*
                 foreach($idFactor as $idd){    
                if($idd && $Factor[$xx]!=''){
                     $sql = "UPDATE  resultado_factores SET "
                            . "Valor ='".$Factor[$xx]."' "
                            . "WHERE id=".$idd;        
                     $db->execute($sql);   
                     $xx++;
                }else{                     
                    $sql = "insert into resultado_factores SET "
                        . "resultado_id=$id[$x], "
                        ." factor_id='".$Factores[$xx]['id']."',"
                        . "Valor ='".$Factor[$xx]."'";        
                    $db->execute($sql);  
                    
                }
              }
              */
                
                       
        }elseif($L!='' && $idOrden!=''){
           $sql = "insert into resultados SET "
                   . "orden_id=$idOrden, "
                   ." Lote='$L',"
                   . "Fecha_Lote ='".SimpleDate($Fecha[$x])."',"
                   . "Cantidad ='".$Cantidad[$x]."',"
                   ." Activo=1,"            
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' ";        
            $db->execute($sql);
            $resultado_id=$db->getOne("SELECT id from resultados ORDER BY id DESC LIMIT 1");
            
            foreach($Factores as $Fac){
                if($Fac['id']){
                    $sql = "insert into resultado_factores SET "
                            . "resultado_id=$resultado_id, "
                            ." factor_id='".$Fac['id']."',"
                            . "Valor ='".$Factor[$xx]."'";        
                     $db->execute($sql);  
                     $xx++;
                }                
            }
        }
        $x++;
    }
    
    $res=$db->getOne("SELECT Total_Partes-SUM(r.Cantidad) 
                                FROM ordenes o 
                                INNER JOIN resultados r on r.orden_id=o.id
                                WHERE o.id=$idOrden
                                GROUP BY o.id");
    
//    $resTotal=$db->getOne("SELECT SUM FROM ordenes WHERE id=$idOrden");    
    if($res>=1){
        $db->execute("UPDATE ordenes SET Estatus=2 WHERE id=$idOrden");
    }elseif($res==0){
        $db->execute("UPDATE ordenes SET Estatus=3 WHERE id=$idOrden");    
    }    
    sleep(1);      

}elseif($action == "savefact2"){
    $x=0;
    foreach($Factor as $Fac){
        if($id[$x]!=''&& $idOrden!=''){
            $sql = "update orden_factores SET "                   
                   ." Factor='$Fac',"
                   ." Regla='$Regla[$x]'," 
                   ." Especificacion='$Especificacion[$x]',"
                    ." Tolerancia='$Tolerancia[$x]',"                                      
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' "
            . " WHERE id=$id[$x]";        
            $db->execute($sql);
        }elseif($Fac!='' && $idOrden!=''){
           $sql = "insert into orden_factores SET "
                   . "orden_id=$idOrden, "
                   ." Factor='$Fac',"
                   ." Regla='$Regla[$x]'," 
                   ." Especificacion='$Especificacion[$x]',"
                    ." Tolerancia='$Tolerancia[$x]',"
                   ." Activo=1,"            
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' ";        
            $db->execute($sql);
        }
        $x++;
    }
    sleep(1);     
}elseif($action=='savefacres2'){
  //  echo 1;
    $contadorElemento=0;
    $x=0;
    $xx=0;
    $IDD='';
    $Factores=$db->getArray("SELECT * FROM orden_factores WHERE orden_id=".$idOrden." AND Activo=1");
    foreach($Lote as $L){   
        if($id[$x])
            $db->execute("DELETE FROM resultado_factores WHERE resultado_id=".$id[$x]);

        if($id[$x]!='' && $idOrden!=''){
            $sql = "update resultados SET "
                   ." Lote='$L',"
                   . "Fecha_Lote ='".SimpleDate($Fecha[$x])."',"
                   . "Muestra ='".$Muestra[$x]."',"                    
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' "
            . " WHERE id=$id[$x]";        
            $db->execute($sql);
          //  $db->execute("update resultado_factores SET Activo=0 WHERE resultado_id=".$id[$x]);
            foreach($Factores as $Fac){
                $IDD=$idd[$Muestra[$x]*count($Factores )-1];
                if($IDD==0)               
                    $Elem=$_POST['Resultados'.$Fac['id']];
                else
                    $Elem=$_POST['Resultados'.$id[$x].$Fac['id']];
                    foreach($Elem as $Res){
                         $sql = "insert into resultado_factores SET "
                                . "resultado_id=$id[$x], "
                                ." factor_id='".$Fac['id']."',"
                                . "Valor ='".$Res."'";        
                        $db->execute($sql);  
                    $contadorElemento++;    
                }                
            }
                if($idd && $Factor[$xx]!=''){
                /*else{                    
                    $sql = "insert into resultado_factores SET "
                        . "resultado_id=$id[$x], "
                        ." factor_id='".$idFactor[$xx]."',"
                        . "Valor ='".$Factor[$xx]."'";        
                    $db->execute($sql);  
                    
                }*/
                
            }            
        }elseif($L!='' && $idOrden!=''){
           $sql = "insert into resultados SET "
                   . "orden_id=$idOrden, "
                   ." Lote='$L',"
                   . "Fecha_Lote ='".SimpleDate($Fecha[$x])."',"
                   . "Muestra ='".$Muestra[$x]."',"
                   ." Activo=1,"            
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' ";        
            $db->execute($sql);
            $resultado_id=$db->getOne("SELECT id from resultados ORDER BY id DESC LIMIT 1");
            $indiceFactor=0;
            foreach($Factores as $Fac){
                //echo $contadorElemento."--";
                //$IDD=$idd[$Muestra[$x]*count($Factores )-1];
                $IDD=$idd[$contadorElemento];
                if($IDD==0){ 
                    $Elem=$_POST['Resultados'.$Fac['id']];
                }else{
                    $Elem=$_POST['Resultados'.$IDD.$Fac['id']];
                }
                //echo $IDD.$Fac['id']."--";
                    foreach($Elem as $Res){
                         $sql = "insert into resultado_factores SET "
                                . "resultado_id=$resultado_id, "
                                ." factor_id='".$Fac['id']."',"
                                . "Valor ='".$Res."'";        
                         $db->execute($sql);        
                         $contadorElemento++;
                }
            }
        }
        $x++;
    }
    $res=$db->getOne("SELECT Total_Partes-SUM(rf.Valor) 
                                FROM ordenes o 
                                INNER JOIN resultados r on r.orden_id=o.id
                                INNER JOIN resultado_factores rf on rf.resultado_id=r.id
                                WHERE o.id=$idOrden
                                GROUP BY o.id");
    
//    $resTotal=$db->getOne("SELECT SUM FROM ordenes WHERE id=$idOrden");    
    if($res>=1){
        $db->execute("UPDATE ordenes SET Estatus=2 WHERE id=$idOrden");
    }elseif($res==0){
        $db->execute("UPDATE ordenes SET Estatus=3 WHERE id=$idOrden");    
    }      
    //sleep(1);      
    
}elseif($action == "saveres3"){
    $x=0;
    foreach($Locacion as $Loc){
        if($id[$x]!='' && $idOrden!=''){
            $sql = "update resultados SET "                   
                   . "orden_id=$idOrden, "
                   ." Locacion='$Loc',"
                   ." Dia='$Dia[$x]'," 
                   ." Informacion='$Informacion[$x]',"                                       
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' "
            . " WHERE id=$id[$x]";        
            $db->execute($sql);
        }elseif($Loc!='' && $idOrden!=''){
           $sql = "insert into resultados SET "
                   . "orden_id=$idOrden, "
                   ." Locacion='$Loc',"
                   ." Dia='$Dia[$x]'," 
                   ." Informacion='$Informacion[$x]',"                    
                   ." Activo=1,"            
            . "updated_at = NOW(), "
            . "updated_by = '{$_SESSION['SORTUSER']}' ";        
            $db->execute($sql);
        }
        $x++;
    }
    $res=$db->getOne("SELECT Total_Partes-SUM(r.Cantidad) 
                                FROM ordenes o 
                                INNER JOIN resultados r on r.orden_id=o.id
                                WHERE o.id=$idOrden
                                GROUP BY o.id");
    
//    $resTotal=$db->getOne("SELECT SUM FROM ordenes WHERE id=$idOrden");    
    if($res>=1){
        $db->execute("UPDATE ordenes SET Estatus=2 WHERE id=$idOrden");
    }elseif($res==0){
        $db->execute("UPDATE ordenes SET Estatus=3 WHERE id=$idOrden");    
    }      
    sleep(1);     
    
}elseif($action == "manage"){
    $sql = "select o.id, o.cliente_id, Servicio, Descripcion, Numero_Parte, Total_Partes, "
            . "auth_at, o.Estatus, Folio, Empresa, Clave, "
            . "IFNULL(c.Nombre, CONCAT_WS(' ', u.Nombre, u.Paterno, u.Materno)) as auth "
            . "from ordenes o "
            . "join servicios s on s.id = o.servicio_id "
            . "join clientes cli on cli.id = o.cliente_id "
            . "left join contactos c on c.id = o.auth_by "
            . "left join usuarios u on u.id = o.auth_by "
            . "where o.id = $id";
    $data = $db->getObject($sql);
    switch($data->Estatus){
        case 1:
            $context->data = $data;
            $sql = "select * from contactos where Activo = 1 and Tipo = 'Quality' and cliente_id = $data->cliente_id";
            $context->contacts = $db->getArray($sql);
            $context->mails = getMails();
            RenderTemplate('templates/ordenes.confirm.php', $context);
            break;
    }
}elseif($action == "confirm"){
    $sql = "update ordenes set Estatus = 2, auth_at = NOW() where Estatus = 1 and id = $id";
    $db->execute($sql);
    sleep(1);
}
