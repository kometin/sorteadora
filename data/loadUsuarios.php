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
    $sql = "select * FROM usuarios "
            . "where id is not null   $and";

    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    $temp = "";
    $x=0;
    foreach($data as $d){
        if($temp != $d['id']){
            $x++;
            if($d["id"]=='')
                $d["id"]=0;
            $temp = $d['id'];
            print "<row id = '" . $d["id"] . "'>";
            print "<cell>" . $x. "</cell>";

            if($d['Estatus']==1){
                print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Edit(\'' .$d['id'] . '\')"></li>') . "</cell>";        
                print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Del(\'' . $d['id'] . '\')"></li>') . "</cell>";        
            }else {
                print "<cell></cell><cell></cell>";
            }
            print "<cell>" . $d["Nombre"] ." ". $d["Paterno"]." ". $d["Materno"] . "</cell>";		
         //   print "<cell>" . $d["Rol"] . "</cell>";
            print "<cell>" . $d["Correo"] . "</cell>";
            print "<cell>" . SimpleDate($d["updated_at"]) . "</cell>";


            print "</row>";
        }
    }
    print "</rows>";    
?>
