<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "select ID_Emp, NumEmp, Empleado, Direccion, Nivel "
            . "from estructura_operativa e "
            . "where Tipo_Pos = 'BASE'  and ID_Emp is not null "
//            . "and ID_Emp not in "
//            . "(select ID_Emp from asistencia_ex where FechaDelete is null) "
            . "order by Direccion, Empleado";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        print "<row id = '" . $d["ID_Emp"] . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell></cell>";		
        print "<cell>" . htmlspecialchars($d["NumEmp"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Empleado"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Direccion"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Nivel"]) . "</cell>";		
        print "</row>";
    }
    print "</rows>";    
?>
