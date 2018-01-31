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
    
//    $sql = "update asistencia_validacion set Estatus = 6 where Estatus = 4 and Inicio = '" . $start . "'";
//    $db->execute($sql);
            
    $sql = "select e.ID_Emp, NumEmp, Empleado, Denominacion, Direccion, NombreUR, "
            . "SUM(IF (Tipo_Just = 'R', Dias, 0)) as R, "
            . "SUM(IF (Tipo_Just = 'RR', Dias, 0)) as RR, "
            . "SUM(IF (Tipo_Just = 'O', Dias, 0)) as O, "
            . "SUM(IF (Tipo_Just = 'RA', Dias, 0)) as RA, "
            . "SUM(IF (Tipo_Just = 'F', Dias, 0)) as F "
            . "from estructura_operativa e "
            . "left join asistencia_justificaciones j on j.ID_Emp = e.ID_Emp and j.Fecha_Inicio >= '" . $start . "' and j.Fecha_Fin <= '" . $end . "' and j.Estatus < 6 "
            . "where Tipo_Pos = 'BASE' and NumEmp is not null "
            . "and e.ID_Emp not in (select ID_Emp from asistencia_ex where FechaDelete is null) "
            . "group by NumEmp "
            . "order by UR, Direccion, Empleado";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        $desctos = $d['RR'] + $d['O'] + $d['RA'] + $d['F'] + (floor($d['R'] / 3));
        print "<row id = '" . $d["ID_Emp"] . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell>" . htmlspecialchars($d["NumEmp"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Empleado"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Denominacion"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Direccion"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["NombreUR"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["R"]) . "</cell>";	
        print "<cell>" . htmlspecialchars($d["RR"]) . "</cell>";	
        print "<cell>" . htmlspecialchars($d["O"]) . "</cell>";	
        print "<cell>" . htmlspecialchars($d["RA"]) . "</cell>";	
        print "<cell>" . htmlspecialchars($d["F"]) . "</cell>";	
        print "<cell>" . htmlspecialchars($desctos) . "</cell>";	
        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-search-plus" onclick="Desctos('. $d['ID_Emp'] . ')"></li>') . "</cell>";       
        print "</row>";
    }
    print "</rows>";    
?>
