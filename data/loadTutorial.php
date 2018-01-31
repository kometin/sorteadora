<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "update plan_tutorial set Estatus = 5 where Estatus = 4 and Fecha_Fin < DATE(NOW())";
    $db->execute($sql);
    
    $sql = "select p.ID_Plan, p.Fecha_Inicio, p.Fecha_Fin, u.Nombre, p.Estatus, est.Estatus as Leyenda, "
            . "CONCAT_WS(' ', e1.Nombre, e1.Apellido_Paterno, e1.Apellido_Materno) as Colab, "
            . "CONCAT_WS(' ', e2.Nombre, e2.Apellido_Paterno, e2.Apellido_Materno) as Tutor "
            . "from plan_tutorial p "
            . "join estatus_plan est on est.ID_Estatus = p.Estatus "
            . "join cat_unidadesresponsables u on u.ID_UR = p.ID_UR "
            . "join empleados e1 on e1.ID_Emp = p.ID_Emp "
            . "join empleados e2 on e2.ID_Emp = p.ID_Tutor "
            . "where p.Activo = 1 and (YEAR(p.Fecha_inicio) = " . $year . " or YEAR(p.Fecha_Fin) = " . $year . ") and ";
            if(getPermiso(22))
                $sql .= "true";
            elseif(getPermiso(25))
                $sql .= "(p.ID_Tutor = " . $_SESSION['RHID'] . " or p.ID_Emp in (select ID_Emp from estructura_operativa where Secuencial in (" . implode(",", getNodes($_SESSION['RHSEC'])) . ")))"; 
            else
                $sql .= "p.ID_Emp = " . $_SESSION['RHID'];
    $sql .= " order by Fecha_Inicio";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    $temp = "";
    foreach($data as $d){
        switch($d['Estatus']){
            case 1: $color = "gray"; break;
            case 2: $color = "#F53B9F"; break;
            case 3: $color = "#F44D4D"; break;
            case 4: $color = "#5D48F9"; break;
            case 5: $color = "#F44D4D"; break;
            case 6: $color = "#A90C3F"; break;
            case 7: $color = "#BBA600"; break;
            case 8: $color = "#024976"; break;
            case 9: $color = "#F0733E"; break;
            case 10: $color = "#E23EF0"; break;
            case 11: $color = "#2C2A2C"; break;
        }
        print "<row id = '" . $d["ID_Plan"] . "'>";
        print "<cell>" . $cont++ . "</cell>";		
        if(in_array($d['Estatus'], array(1, 3, 5)))
            print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-trash-o" onclick="DeletePlan(' .$d["ID_Plan"]. ')"></li>') . "</cell>";       
        else
            print "<cell></cell>";
        print "<cell>" . htmlspecialchars($d["Nombre"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Colab"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Tutor"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Fecha_Inicio"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Fecha_Fin"]) . "</cell>";		
        print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'background:" . $color . "' onclick='Process(" .$d["ID_Plan"]. ")'>" . $d["Leyenda"] . "</div>") . "</cell>";
//        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-folder-open" onclick="Process(' .$d["ID_Plan"]. ')"></li>') . "</cell>";       
//        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-print" onclick="Print(' .$d["ID_Plan"]. ')"></li>') . "</cell>";       
        print "</row>";
    }
    print "</rows>";    
?>
