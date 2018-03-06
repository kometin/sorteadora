<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    if($_GET['all'] &&  $_GET['all']==1)
        $and=" AND Estatus=0";       
    else
        $and=" AND Estatus = 1";   
    $db = new DBConn();
    $sql = "Select * "
            . "from operadores  "
            . "where id is not null   $and";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        print "<row id = '" . $d["id"] ."'>";
        print "<cell>" . $cont++ . "</cell>";
   //     print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-search-plus" onclick="Ver(\'' .$d['id'] . '\')"></li>') . "</cell>";        
        if($d['Estatus']==1){
        
            print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Edit(\'' .$d['id'] . '\')"></li>') . "</cell>";        
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Del(\'' . $d['id'] . '\')"></li>') . "</cell>";        
        }else {
            print "<cell></cell><cell></cell>";
        }
        print "<cell>" . htmlspecialchars($d["Nombre"]." ".$d["Paterno"]." ".$d["Materno"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["RFC"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["NSS"]) . "</cell>";		

        print "<cell>" . htmlspecialchars($d["Telefono"]) . "</cell>";	
        if($d["Estatus"]==1)
            print "<cell>" . SimpleDate($d["Fecha_Alta"]) . "</cell>";
        else
            print "<cell>" . SimpleDate($d["Fecha_Baja"]) . "</cell>";

       print "</row>";
    }
    print "</rows>";    
?>
