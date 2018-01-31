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
    $begin_vacations = $db->getOne($sql);
    
    $sql = "select ID_Emp, NumEmp, Empleado, Direccion, Tipo_Pos, Fecha_Alta, DATEDIFF('" . $begin_vacations . "', Fecha_Alta) as Diff
            from estructura_operativa where ID_Emp is not null and 
            (DATEDIFF('" . $begin_vacations . "', Fecha_Alta) <= " . DAYS_ANTIQUE . " or Tipo_Pos <> 'BASE')
            order by Direccion, Empleado ";
            
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        print "<row id = '" . $d["ID_Emp"] . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell>" . ($d["NumEmp"]) . "</cell>";		
        print "<cell>" . ($d["Empleado"]) . "</cell>";		
        print "<cell>" . ($d["Direccion"]) . "</cell>";		
        print "<cell>" . ($d["Tipo_Pos"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Fecha_Alta"]) . "</cell>";		
        print "</row>";
    }
    print "</rows>";    
?>
