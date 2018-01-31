<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    
    $sql = "select IFNULL(IFNULL(e2.ID_Emp, e3.ID_Emp), 0) as ID, e.ID_Emp, e.NumEmp, e.Empleado, e.Direccion, e2.Empleado as Jefe, e3.Empleado as Suplente, "
            . "(select COUNT(*) from asistencia_justificaciones where Estatus = 1 and ID_Emp = e.ID_Emp  and Fecha_Fin <= '" . $end . "') as Conteo, "
            . "(select MIN(Fecha_Envia) from asistencia_justificaciones where Estatus = 1 and ID_Emp = e.ID_Emp and Fecha_Fin <= '" . $end . "') as Fecha "
            . "from estructura_operativa e "
            . "left join estructura_operativa e2 on e2.Secuencial = e.SecuencialPadre "
            . "left join asistencia_suplentes s on s.ID_Emp = e.ID_Emp  "
            . "left join estructura_operativa e3 on e3.ID_Emp = s.ID_Jefe "
            . "where e.Tipo_Pos = 'BASE' and e.ID_Emp is not null "
            . "and e.SecuencialPadre is not null and e.ID_Emp not in "
            . "(select ID_Emp from asistencia_ex where FechaDelete is null) "
            . "having Conteo > 0 "
            . "order by Jefe, e.Direccion, e.Empleado ";
            
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        print "<row id = '" . $cont . "-" . $d["ID"] . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell type = '" . ($d['ID']?"ch":"ro") . "'>" . ($d['ID']?"1":"") . "</cell>";		
        print "<cell>" . htmlspecialchars($d["NumEmp"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Empleado"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Direccion"]) . "</cell>";		
        print "<cell>" . htmlspecialchars( ($d["Jefe"]?$d['Jefe']:"<a class = 'btn btn-link btn-sm btn-sup' onclick = 'Suplent(" . $d['ID_Emp'] . ")'>" . ($d['Suplente']?$d['Suplente']." (suplente)":"Elegir suplente") . "</a>") ) . "</cell>";		
        print "<cell>" . $d['Conteo'] . "</cell>";
        print "<cell>" . SimpleDate($d['Fecha']) . "</cell>";
        print "</row>";
    }
    print "</rows>";    
?>
