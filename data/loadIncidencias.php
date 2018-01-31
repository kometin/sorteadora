<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $start = reset(explode("|", $period));
    $end = end(explode("|", $period));
    $sql = "select e.ID_Emp, NumEmp, Empleado, Denominacion, Direccion, COUNT(ID_Just) as Incidencias, "
            . "(select COUNT(*) from asistencia_justificaciones where Estatus = 3 and ID_Emp = e.ID_Emp "
            . "and Fecha_Inicio >= '" . $start . "' and Fecha_Fin <= '" . $end . "') as Pending "
            . "from asistencia_justificaciones j "
            . "join estructura_operativa e on e.ID_Emp = j.ID_Emp "
            . "where j.Fecha_Inicio >= '" . $start . "' and j.Fecha_Fin <= '" . $end . "' "
            . "and j.Estatus in (2, 3, 5, 6) "
            . "group by NumEmp "
            . "order by Pending DESC, j.Fecha_Inicio, Empleado";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        switch($d['Estatus']){
            case 3: $color = "#3A8AB3"; break;
            case 4: $color = "gray"; break;
            case 5: $color = "#EB5075"; break;
            case 6: $color = "#0FA316"; break;
        }
        print "<row id = '" . $d["ID_Emp"] . "'>";
        print "<cell>" . $cont++ . "</cell>";		
        print "<cell>" . htmlspecialchars($d["NumEmp"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Empleado"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Denominacion"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Direccion"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Incidencias"]) . "</cell>";	
        print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'background:" . ($d['Pending']?"gray":"#6666FF") . "' onclick='Incidence(" .$d["ID_Emp"]. ")'>" . ($d["Pending"]?"PENDIENTE":"VALIDADO") . "</div>") . "</cell>";
        print "</row>";
    }
    print "</rows>";    
?>
