<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    if($_GET['all'] &&  $_GET['all']==1)
        $and=" AND Activo=0";       
    else
        $and=" AND Activo = 1";   
    $db = new DBConn();
    $sql = "Select * "
            . "from servicios  "
            . "where id is not null   $and";
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
        print "<cell>" . htmlspecialchars($d["Servicio"])."</cell>";
        //1 conteo de defectos
        //2 especificación técnica
        //3 Información general
        if($d["Tipo_Medicion"]==1)
            $tipoMeciion='Conteo de defectos';
        if($d["Tipo_Medicion"]==2)
            $tipoMeciion='Especificación técnica';
        if($d["Tipo_Medicion"]==3)
            $tipoMeciion='Información general';
        print "<cell>" . htmlspecialchars($tipoMedicion)."</cell>";		

        print "<cell>" . SimpleDate($d["updated_at"]) . "</cell>";
        
       print "</row>";
    }
    print "</rows>";    
?>
