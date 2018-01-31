<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    
    $sql = "select ID_CV, e.ID_Emp, Empleado, NumEmp, Denominacion, Direccion, Correo, Tipo_Pos, FechaUpdate, 'ACTIVO' as Estatus
            from estructura_oficial e 
            left join curriculums c on c.ID_Emp = e.ID_Emp 
            where e.ID_Emp is not null 

            UNION 

            select ID_CV, e.ID_Emp, CONCAT_WS(' ', Nombre, Apellido_Paterno, Apellido_Materno) as Empleado, 
            emp.Numero as NumEmp, Denominacion, Direccion, Correo, Tipo_Pos, cv.FechaUpdate, 'BAJA' as Estatus  
            from estructura e 
            join empleados emp on emp.ID_Emp = e.ID_Emp 
            join cat_puestos p on p.ID_Puesto = e.ID_Puesto 
            join cat_direcciones d on d.ID_Dir = e.ID_Dir 
            join curriculums cv on cv.ID_Emp = emp.ID_Emp 
            where emp.Activo = 0 and (e.ID_Origen is null or e.ID_Origen = 0)
            #and DATEDIFF(CURDATE(), e.Fecha_Fin) <= 90 

            order by Estatus, Empleado";
    
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        print "<row id = '" . $d['ID_Emp'] . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell type = '" . ($d['ID_CV']?"ch":"ro") . "'>" . ($d['ID_CV']?"1":"") . "</cell>";
        print "<cell>" . htmlspecialchars($d['ID_CV']?"<i class = 'fa fa-search-plus fa-2x' onclick = 'ViewCV(" . $d['ID_CV'] . ")'></i>":"") . "</cell>";
//        print "<cell>" . htmlspecialchars($d['ID_CV']?"<a href = 'document.php?doc=cv&id=" . $d['ID_Emp'] . "' target = '_blank'><i class = 'fa fa-file-pdf-o fa-2x'></i></a>":"") . "</cell>";
        print "<cell>" . $d['NumEmp'] . "</cell>";	
        print "<cell>" . $d['Empleado'] . "</cell>";			
        print "<cell>" . $d['Denominacion'] . "</cell>";		
        print "<cell>" . $d['Direccion'] . "</cell>";		
        print "<cell>" . $d['Correo'] . "</cell>";		
        print "<cell>" . $d['Tipo_Pos'] . "</cell>";		
        print "<cell>" . ($d['FechaUpdate']?SimpleDate($d['FechaUpdate']):"NUNCA") . "</cell>";
        print "<cell>" . $d['Estatus'] . "</cell>";		
        print "</row>";
    }
    print "</rows>";    
?>
