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
    
    
    }elseif($activo == 'administrar'){
        
        
         $sql = "SELECT * FROM tramites WHERE Activo=1;";

    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    foreach($data as $d){
        print  "<row id = '" . $d['idTramite'] . "'>";
        print  "<cell>" . $d['Nombre'] . "</cell>";  
         
            print "<cell align=\"center\">" . htmlentities('<a href = "#" onclick="Ver_documento(\'' . $d["idTramite"] . '\')">Ver documento</a></li>') . "</cell>"; 
        
        print  "</row>";
    }
    print  "</rows>";
    
    
        
    }
    
    
?>
