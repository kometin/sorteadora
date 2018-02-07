<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    if($_GET['all'] &&  $_GET['all']==1)
        $and=" AND o.Estatus=0";       
    else
        $and=" AND o.Estatus = 1";   
    $db = new DBConn();
    $sql = "SELECT * FROM ordenes o"
                . " INNER JOIN clientes c ON c.id=o.cliente_id "               
            . "where o.id is not null   $and";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        print "<row id = '" . $d["id"] ."'>";
        print "<cell>" . $cont++ . "</cell>";
   //     print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-search-plus" onclick="Ver(\'' .$d['id'] . '\')"></li>') . "</cell>";        
        if($d['Activo']==1){
        
            print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Edit(\'' .$d['id'] . '\')"></li>') . "</cell>";        
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Del(\'' . $d['id'] . '\')"></li>') . "</cell>";        
        }else {
            print "<cell></cell><cell></cell>";
        }

        print "<cell>" . htmlspecialchars($d["Cliente"])."</cell>";		
        print "<cell>" . htmlspecialchars(SimpleDate($d["Fecha_orden"]))."</cell>";		
        print "<cell>" . htmlspecialchars($d["Numero_Parte"])."</cell>";		
        print "<cell>" . htmlspecialchars($d["Descripcion"])."</cell>";		
        print "<cell>" . htmlspecialchars($d["Folio"])."</cell>";		
        print "<cell>" . htmlspecialchars($d["Servicio"])."</cell>";		
        print "<cell>" . htmlspecialchars(SimpleDate($d["Fecha_cierre"]))."</cell>";		
        print "<cell>" . SimpleDate($d["updated_at"]) . "</cell>";
        
       print "</row>";
    }
    print "</rows>";    
?>
