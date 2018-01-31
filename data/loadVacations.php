<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    DEFINE('DAYS_ANTIQUE', 180);
    
    $sql = "select Valor from parametros where ID_Param = 1";
    $begin = $db->getOne($sql);
    
    $sql = "select ID_Vac, v.ID_Emp, NumEmp, Empleado, Direccion, Denominacion, "
            . "IFNULL(v.Estatus, 5) as Step, IFNULL(st.Estatus, 'Sin enviar') as Stat "
            . "from estructura_operativa e "
            . "left join vacaciones v on v.ID_Emp = e.ID_Emp and Periodo = " . $period . " "
            . "left join estatus_vacaciones st on st.ID_Estatus = v.Estatus "
            . "where e.ID_Emp is not null and Tipo_Pos = 'BASE' and DATEDIFF('" . $begin . "', Fecha_Alta) > " . DAYS_ANTIQUE . " "
            . "order by Step, Empleado";
            
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        switch($d['Step']){
            case "0": $color = "#6699FF"; break;
            case "1": $color = "#6666FF"; break;
            case "2": $color = "#DE8425"; break;
            case "3": $color = "#FF6666"; break;
            case "4": $color = "#00CC00"; break;
            case "5": $color = "#999999"; break;
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
