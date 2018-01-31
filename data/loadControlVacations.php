<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    
    $sql = "";
            
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        switch($d['Estatus']){
            case "0": $color = "#999999"; break;
            case "1": $color = "#6666FF"; break;
            case "2": $color = "#DE8425"; break;
            case "3": $color = "#FF6666"; break;
            case "4": $color = "#00CC00"; break;
        }
        print "<row id = '" . $d["ID_Vac"] . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell>" . ($d["NumEmp"]) . "</cell>";		
        print "<cell>" . ($d["Empleado"]) . "</cell>";		
        print "<cell>" . ($d["Denominacion"]) . "</cell>";		
        print "<cell>" . ($d["Direccion"]) . "</cell>";		
        print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'background:" . $color . "' onclick='View(" .$d["ID_Vac"]. ", " . $d['ID_Emp'] . ")'>" . $d["Stat"] . "</div>") . "</cell>";
        print "</row>";
    }
    print "</rows>";    
?>
