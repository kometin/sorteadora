<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "select e.ID_Emp, CONCAT_WS(' ', Nombre, Apellido_Paterno, Apellido_Materno) as Empleado, e.Numero, Fecha_Alta as Alta, Correo, Denominacion, Direccion, Tipo_Pos, st.Estatus "
//            . "IF((select Fecha_Fin from afectaciones where ID_Emp = e.ID_Emp order by ID_Afectacion DESC LIMIT 1) >= DATE(NOW()), 'Licencia', IF(e.Estatus = 1, 'Activo', 'Nuevo')) as Estatus "
            . "from empleados e "
            . "join estatus_empleado st on st.ID_Estatus = e.Estatus "
            . "join estructura est on est.ID_Emp = e.ID_Emp "
            . "join cat_puestos p on p.ID_Puesto = est.ID_Puesto "
            . "join cat_direcciones d on d.ID_Dir = est.ID_DIr "
            . "where (ID_Origen is null or ID_Origen > 0) and (" . ($active? "est.Estatus = 1 or Fecha_Fin >= CURDATE()":"est.Estatus = -1 and Fecha_Fin < CURDATE()") . ") "
//            . "where e.Activo = " . $active
            . "order by Empleado";
    $data = $db->getArray($sql);
   
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    $temp = "";
    foreach($data as $d){
        print "<row id = '" . $d["ID_Emp"] . "'>";
        print "<cell>" . $cont++ . "</cell>";		
        print "<cell>" . htmlentities('<i class="fa  fa-2x fa-search-plus" onclick="View('. ($d['ID_Emp']?$d['ID_Emp']:"0") . ', ' . ($d["ID_Emp"]?"0":$d['Numero']) . ')"></li>') . "</cell>";       
        print "<cell>" . htmlspecialchars($d["Empleado"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Numero"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Correo"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Denominacion"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Direccion"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Alta"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Estatus"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Tipo_Pos"]) . "</cell>";	
        if($_SESSION['RHPROID'] == 1)
            print "<cell>" . htmlentities('<a href = "card.php?id=' . md5($d['ID_Emp']) . '" target = "_blank"><i class="fa  fa-2x fa-id-card"></li></a>') . "</cell>";       
        print "</row>";
    }
    print "</rows>";    
?>
