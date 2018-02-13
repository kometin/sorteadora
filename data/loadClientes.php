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
            . "from clientes  "
            . "where id is not null   $and order by Empresa ASC";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        print "<row id = '" . $d["id"] ."'>";
        print "<cell>" . $cont++ . "</cell>";    
        if($d['Estatus']==1){
        
            print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Edit(\'' .$d['id'] . '\')"></li>') . "</cell>";        
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Del(\'' . $d['id'] . '\')"></li>') . "</cell>";          
        }else {
            print "<cell></cell><cell></cell>";
        }            
       // print "<cell>" . htmlspecialchars($d["Nombre"])."</cell>";		
        print "<cell>" . htmlspecialchars($d["Empresa"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["RazonSocial"]) . "</cell>";		

        print "<cell>" . htmlspecialchars($d["RFC"]) . "</cell>";		
        //print "<cell>" . htmlspecialchars($d["Correo"]) . "</cell>";		        
        print "<cell>" . htmlspecialchars($d["Direccion"]) . "</cell>";
        print "<cell>" . htmlspecialchars($d["Telefono"]) . "</cell>";
        //print "<cell>" . SimpleDate($d["updated_at"]) . "</cell>";
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-address-card" onclick="Contact(\'' . $d['id'] . '\')"></li>') . "</cell>";          
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-money" onclick="Cuentas(\'' . $d['id'] . '\')"></li>') . "</cell>";          
         
       print "</row>";
    }
    print "</rows>";    
?>
