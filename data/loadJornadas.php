<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "select * from cat_jornadas where Activo = 1";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        print "<row id = '" . $d["ID_Jornada"] . "-" . $cont . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-pencil" onclick="Edit(' .$d["ID_Jornada"]. ')"></li>') . "</cell>";       
        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-trash-o" onclick="Delete(' .$d["ID_Jornada"]. ')"></li>') . "</cell>";       
        print "<cell>" . htmlspecialchars($d["Jornada"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Entrada"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Salida"]) . "</cell>";		
        print "</row>";
    }
    print "</rows>";    
?>
