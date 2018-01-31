<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "select u.ID_UR, UR, Nombre, GROUP_CONCAT(Programa) as Programas "
            . "from cat_unidadesresponsables u "
            . "left join cat_programas p on p.ID_UR = u.ID_UR and p.Activo = 1 "
            . "where u.Activo = 1 "
            . "group by u.ID_UR "
            . "order by Nombre";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    $temp = "";
    foreach($data as $d){
        print "<row id = '" . $d["ID_UR"] . "'>";
        print "<cell>" . $cont++ . "</cell>";		
        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-pencil" onclick="Edit(\'' .$d["ID_UR"]. '\')"></li>') . "</cell>";       
        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-trash-o" onclick="Delete(\'' .$d["ID_UR"]. '\')"></li>') . "</cell>";       
        print "<cell>" . htmlspecialchars($d["UR"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Nombre"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Programas"]) . "</cell>";		
        print "</row>";
    }
    print "</rows>";    
?>
