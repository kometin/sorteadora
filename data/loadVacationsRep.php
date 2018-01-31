<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    
    $sql = "select v.ID_Vac, NumEmp, Empleado, Direccion, GROUP_CONCAT(Fecha) as Fechas, st.Estatus "
            . "from vacaciones v "
            . "join estatus_vacaciones st on st.ID_Estatus = v.Estatus "
            . "join estructura_operativa e on e.ID_Emp = v.ID_Emp "
            . "join vacaciones_detalles d on d.ID_Vac = v.ID_Vac "
            . "where v.Estatus = 4 and Periodo = " . $period . " "
            . "group by v.ID_Vac "
            . "order by Direccion, Empleado, Fechas";
            
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        print "<row id = '" . $d["ID_Vac"] . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell>" . ($d["NumEmp"]) . "</cell>";		
        print "<cell>" . ($d["Empleado"]) . "</cell>";		
        print "<cell>" . ($d["Direccion"]) . "</cell>";		
        print "<cell>" . ($d["Fechas"]) . "</cell>";		
        print "<cell>" . ($d["Estatus"]) . "</cell>";		
        print "</row>";
    }
    print "</rows>";    
?>
