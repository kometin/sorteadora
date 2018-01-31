<?php

    require_once('../lib/secure.php');
    require_once('../lib/DBConn.php');
    require_once('../lib/catalogos.php');
	$cat=new catalogos();	
	//$datosCat=$cat->datos('dependencia');

	$activo = $_GET['cat'];
	
	
    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    
    if($activo == 'gafete'){
        
    $sql = "SELECT * FROM empleados WHERE Activo=1;";

    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    foreach($data as $d){
        print  "<row id = '" . $d['ID_Emp'] . "'>";
        print  "<cell>" . $d['Nombre'] . "</cell>";  
         
           print  "<cell>" . $d['Correo'] . "</cell>";  
            print  "<cell>" . $d['RFC'] . "</cell>";  
            print "<cell align=\"center\">" . htmlentities('<a href = "#" onclick="generar_gafete(\'' . $d["Correo"] . '\')">Ver</a></li>') . "</cell>"; 
        
        print  "</row>";
    }
    print  "</rows>";
    
    
    }elseif($activo == 'mis_solicitudes'){
        $uid = $_SESSION['RHID'];
        
    $sql = "SELECT * FROM tramitessolicitud where idEmpleado = $uid order by fechaInicio desc;";
   // echo $sql;

    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $i = 0;
    foreach($data as $d){
        
        $i++;
                
        $id_tramite = $d['idTramite'];
        
         $texto = "Ver";
         
         
        if($d['idEstatus']=='1'){
            $estatus = "Borrador";
            $texto = "Continuar captura";
            $color = 'c36f02';
            $fvar = 'continuar_solicitud_usuario';
            
        }
        if($d['idEstatus']=='2'){
            $estatus = "Solicitud enviada";
             $color = '307b56';
              $fvar = 'aprobar_prestacion_ver';
        }
        if($d['idEstatus']=='3'){
            $estatus = "Solicitud terminada";
             $color = '309afd';
              $fvar = 'aprobar_prestacion_ver';
        }
        if($d['idEstatus']=='4'){
            $estatus = "Solicitud rechazada";
             $color = 'fc6b6b';
              $fvar = 'continuar_solicitud_usuario';
        }
        
        $sql = "SELECT * FROM tramites where idTramite = $id_tramite;";
        $data2 = $db->getObject($sql);
        
        print  "<row id = '" . $d['idTramiteSolicitud'] . "'>";
        print  "<cell>" . $i . "</cell>";  
         if($estatus == 'Borrador' or $estatus == "Solicitud rechazada"){
                //  print "<cell align=\"center\">" . htmlspecialchars('<a href = "#" onclick="continuar_solicitud_usuario(\'' . $data2->idTramite . '\', \''.$d["idTramiteSolicitud"].'\', \''.$data2->Nombre.'\')">'.$texto.'</a></li>') . "</cell>"; 
          print "<cell align=\"center\" >" . htmlspecialchars('<i class="fa fa-2x fa-pencil" onclick="continuar_solicitud_usuario(\'' . $data2->idTramite . '\', \''.$d["idTramiteSolicitud"].'\', \''.$data2->Nombre.'\')"></li>') . "</cell>"; 
               }else{
                 print "<cell align=\"center\"></cell>"; 
        
            }
        print  "<cell>" . $data2->Nombre . "</cell>";  
          print  "<cell>" . SimpleDate($d['fechaInicio']) . "</cell>";  
           print "<cell>".SimpleDate($d['fechafin'])."</cell>";
        print  '<cell>'.htmlspecialchars('<div class="flag-grid" style="cursor:pointer; background-color: #'.$color.'" onclick="'.$fvar.'(\'' . $data2->idTramite . '\', \''.$d["idTramiteSolicitud"].'\', \''.$data2->Nombre.'\')" title="Resultados rechazados">'.$estatus.'</div>'
                . '')."</cell>";
         
         
           
         
           
        print  "</row>";
    }
    print  "</rows>";
    
    
    }elseif($activo == 'ver'){
        $sql = "SELECT * FROM tramitessolicitud where idEstatus != 1 and idEstatus != 3  and idEstatus != 4 order by fechaInicio desc;";
        $data = $db->getArray($sql);
        print "<?xml version='1.0' encoding='UTF-8'?>\n";
        print "<rows pos='0'>";
        $i = 0;
        foreach ($data as $d) {
              $i++;
              
            $id_tramite = $d['idTramite'];
            $id_empleado = $d['idEmpleado'];
            $sql = "SELECT * FROM tramites where idTramite = $id_tramite;";
            $data2 = $db->getObject($sql);
            $sql = "SELECT * FROM empleados where ID_Emp = $id_empleado;";
            $data3 = $db->getObject($sql);
            
            
            $sql = "select estructura.ID_Dir, estructura.ID_Emp, cat_direcciones.Direccion from estructura left join cat_direcciones on 
cat_direcciones.ID_Dir = estructura.ID_Dir where estructura.estatus = 1 and estructura.ID_Emp = 2";
            $r34 = $db->getObject($sql);
            
            
            print "<row id = '" . $d['idTramiteSolicitud'] . "'>";
             print "<cell>" . $i . "</cell>";
             
//             print "<cell align=\"center\" >" . htmlentities('<a href="prestaciones.php?task=seguimiento&id='.$d["idTramiteSolicitud"].'"><i class="fa fa-2x fa-search-plus"></i></a>') . "";        
//        print "</cell>"; 
      
              
          print "<cell>" . $r34->Direccion . "</cell>";
             
            print "<cell>" . $data2->Nombre . "</cell>";
            
             if($d['idEstatus'] == '2'){
                 $estado_r = "Pendiente de aprobación";
                 $color_r = 'ffc21e';
                 $action_r = 'aprobar_prestacion';
                 
             }
              if($d['idEstatus'] == '3'){
                 $estado_r = "Aprobado";
                  $color_r = '9eea87';
                   $action_r = 'ver_prestacion';
             }
             if($d['idEstatus'] == '4'){
                 $estado_r = "Rechazado";
                  $color_r = 'f4640d';
                   $action_r = 'aprobar_prestacion';
             }
             
              print  '<cell>'.htmlspecialchars('<div class="flag-grid" style="cursor:pointer; background-color: #'.$color_r.'" onclick="'.$action_r.'(\'' .$d["idTramiteSolicitud"]. '\')" title="'.$estado_r.'">'.$estado_r.'</div>'
                . '')."</cell>";
             
             
            print "<cell>" . $data3->Nombre." ".$data3->Apellido_Paterno." ".$data3->Apellido_Materno . "</cell>";
            print "<cell>".SimpleDate($d['fechaInicio'])."</cell>";
            print "<cell>".SimpleDate($d['fechafin'])."</cell>";
            
               
	
        
        
                 print "</row>";
        }
        print "</rows>";
    }elseif($activo == 'ver_completo'){
        $sql = "SELECT * FROM tramitessolicitud where idEstatus != 1 order by fechaInicio desc;";
        $data = $db->getArray($sql);
        print "<?xml version='1.0' encoding='UTF-8'?>\n";
        print "<rows pos='0'>";
        $i = 0;
        foreach ($data as $d) {
              $i++;
              
            $id_tramite = $d['idTramite'];
            $id_empleado = $d['idEmpleado'];
            $sql = "SELECT * FROM tramites where idTramite = $id_tramite;";
            $data2 = $db->getObject($sql);
            $sql = "SELECT * FROM empleados where ID_Emp = $id_empleado;";
            $data3 = $db->getObject($sql);
            
            
            $sql = "select estructura.ID_Dir, estructura.ID_Emp, cat_direcciones.Direccion from estructura left join cat_direcciones on 
cat_direcciones.ID_Dir = estructura.ID_Dir where estructura.estatus = 1 and estructura.ID_Emp = 2";
            $r34 = $db->getObject($sql);
            
            
            print "<row id = '" . $d['idTramiteSolicitud'] . "'>";
             print "<cell>" . $i . "</cell>";
             
//             print "<cell align=\"center\" >" . htmlentities('<a href="prestaciones.php?task=seguimiento&id='.$d["idTramiteSolicitud"].'"><i class="fa fa-2x fa-search-plus"></i></a>') . "";        
//        print "</cell>"; 
      
              
          print "<cell>" . $r34->Direccion . "</cell>";
             
            print "<cell>" . $data2->Nombre . "</cell>";
            
             if($d['idEstatus'] == '2'){
                 $estado_r = "Pendiente de aprobación";
                 $color_r = 'ffc21e';
                 $action_r = 'aprobar_prestacion';
                 
             }
              if($d['idEstatus'] == '3'){
                 $estado_r = "Aprobado";
                  $color_r = '9eea87';
                   $action_r = 'ver_prestacion';
             }
             if($d['idEstatus'] == '4'){
                 $estado_r = "Rechazado";
                  $color_r = 'f4640d';
                   $action_r = 'aprobar_prestacion';
             }
             
              print  '<cell>'.htmlspecialchars('<div class="flag-grid" style="cursor:pointer; background-color: #'.$color_r.'" onclick="'.$action_r.'(\'' .$d["idTramiteSolicitud"]. '\')" title="'.$estado_r.'">'.$estado_r.'</div>'
                . '')."</cell>";
             
             
            print "<cell>" . $data3->Nombre." ".$data3->Apellido_Paterno." ".$data3->Apellido_Materno . "</cell>";
            print "<cell>".SimpleDate($d['fechaInicio'])."</cell>";
            print "<cell>".SimpleDate($d['fechafin'])."</cell>";
            
               
	
        
        
                 print "</row>";
        }
        print "</rows>";
    }elseif($activo == 'administrar'){
        
        
         $sql = "SELECT * FROM tramites WHERE Activo=1;";

    $data = $db->getArray($sql);
    $i=0;
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    foreach($data as $d){
        $i++;
        print  "<row id = '" . $d['idTramite'] . "'>";
         print "<cell>" . $i . "</cell>";
        print  "<cell>" . $d['Nombre'] . "</cell>";  
        if($d['Activo'] == 1){
            $estatus = "Si";
            
        }else{
            $estatus = "No";
        }
       //  print  "<cell>" . htmlentities('<a href = "prestaciones.php?task=ver_documentos&tramite='.$d["idTramite"].'">Ver documentos</a>') . "</cell>";  
    if($d['idTramite'] != 6){
         print "<cell align=\"center\">" . htmlentities('<a href = "prestaciones.php?task=ver_documentos&tramite='.$d["idTramite"].'"><i class="fa fa-2x fa-search-plus"></a>') . "</cell>";        
	
    }else{
        print "<cell align=\"center\">"."</cell>";   
    }
       
         print "<cell align=\"center\">" . htmlentities('<a href = "prestaciones.php?task=ver_variables&tramite='.$d["idTramite"].'"><i class="fa fa-2x fa-search-plus"></a>') . "</cell>";        
		   
         //print "<cell align=\"center\">" . htmlentities('<a href = "#" onclick="Ver_prestacion(\'' . $d["idTramite"] . '\')">Ver documentos</a>&nbsp;&nbsp;&nbsp; <a href = "prestaciones.php?task=ver_variables&tramite='.$d["idTramite"].'">Ver variables</a></li>') . "</cell>"; 
    
          //  print "<cell align=\"center\">" . htmlentities('<a href = "prestaciones.php?task=ver_variables&tramite='.$d["idTramite"].'">Ver variables</a></li>') . "</cell>"; 
       // print "<cell align=\"center\">" . htmlentities('<a href = "#" onclick="Ver_prestacion(\'' . $d["idTramite"] . '\')">Editar</a>&nbsp;&nbsp;&nbsp; <a href = "prestaciones.php?task=ver_variables&tramite='.$d["idTramite"].'">Borrar</a>/li>') . "</cell>"; 
        
        print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Ver_prestacion(\'' .$d["idTramite"]. '\')"></i>') . "";        
        if($d['Borrable'] == 1){
            print "" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="borrar_prestacion(\'' . $d["idTramite"] . '\')"></i>') . "</cell>";        
	
       }else{
            print "" . "</cell>";        
	
       }
       	
        
        print  "</row>";
    }
    print  "</rows>";
    
    
        
    }elseif($activo == 'administrar_variables'){
        $id = $_GET['id'];
        
    $sql = "SELECT * FROM tramites_variables WHERE Activo=1 and idTramite = '".$id."';";

    $data = $db->getArray($sql);
    $i=0;
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    foreach($data as $d){
        $i++;
        print  "<row id = '" . $d['idVariable'] . "'>";
        print  "<cell>" . $i . "</cell>"; 
        print  "<cell>" . $d['Nombre'] . "</cell>";  
         print  "<cell>" . SimpleDate($d['fecha']) . "</cell>"; 
           print  "<cell>" . $d['Tipo'] . "</cell>";  
              print  "<cell>" . $d['Tamano'] . "</cell>";  
                print  "<cell>" . $d['Opciones'] . "</cell>";  
         //   print "<cell align=\"center\">" . htmlentities('<a onclick="Ver_variable(\''.$d["idVariabe"].'\')">Editar variable</a></li>') . "</cell>"; 
        
            
              print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Ver_variable(\'' .$d["idVariable"]. '\')"></i>') . "";        
        print "" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Borrar_variable(\'' . $d["idVariable"] . '\')"></i>') . "</cell>";        
		
        
        
        
        print  "</row>";
    }
    print  "</rows>";
    }elseif($activo == 'administrar_documentos'){
        $id = $_GET['id'];
        
    $sql = "select tramites_documentos_plantillas.idTramiteDocumento, tramites_documentos_plantillas.fecha, tramites_documentos_plantillas.idTramite, tramites_documentos_plantillas.Titulo, 
tramites_documentos_plantillas.Contenido, tramites.Nombre from tramites_documentos_plantillas 
left join tramites on tramites_documentos_plantillas.idTramite = tramites.idTramite 
where tramites_documentos_plantillas.Activo = 1 and tramites_documentos_plantillas.idTramite = '".$id."';";

    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $c1 = 1;
    foreach($data as $d){
        print  "<row id = '" . $d['idTramiteDocumento'] . "'>";
        print  "<cell>" . $c1 . "</cell>";  
        $c1++;
      
           print  "<cell>" . $d['Titulo'] . "</cell>";  
              
              print  "<cell>" . SimpleDate($d['fecha']) . "</cell>";    
          //  print "<cell align=\"center\">" . htmlentities('<a onclick="Editar_documento(\''.$id.'\',\''.$d["idTramiteDocumento"].'\')">Editar documento</a></li>') . "</cell>"; 
          print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Editar_documento(\''.$id.'\',\''.$d["idTramiteDocumento"].'\')"></i>') . "";        
        print "" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Borrar_documento(\'' . $d["idTramiteDocumento"] . '\')"></i>') . "</cell>";        
		
        
        
        print  "</row>";
    }
    print  "</rows>";
    }
?>
