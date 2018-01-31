<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "select c.ID_Cap, u.Nombre as UR, Fecha_Solicitud, CONCAT_WS(' ', e.Nombre, Apellido_Paterno) as Nombre, Tema, Fecha_Inicio, Fecha_Termino, st.Estatus as Stage, c.Estatus "
            . "from capacitacion_ur c "
            . "join estatus_capacitacion st on st.ID_Estatus = c.Estatus "
            . "join cat_unidadesresponsables u on u.ID_UR = c.ID_UR "
            . "join empleados e on e.ID_Emp = c.ID_Solicita "
            . "left join capacitacion_part p on p.ID_Cap = c.ID_Cap "
            . "where c.Activo = 1 and (YEAR(Fecha_Inicio) = " . $year . " or YEAR(Fecha_Termino) = " . $year . ") ";
            if(getPermiso(array(23, 22))){
                $sql .= "";
            }elseif(getPermiso(24)){
                $sql .= "and c.ID_UR = " . $_SESSION['RHUR'];
            }else{
                $sql .= "and p.ID_Emp = " . $_SESSION['RHID'];
            }
            $sql .= " order by c.ID_Cap DESC";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        switch ($d['Estatus']){
            case 1: $color = "#999999"; break;
            case 2: $color = "#3366FF"; break;
            case 3: $color = "#F34486"; break;
            case 4: $color = "#33CCFF"; break;
            case 5: $color = "#F3355E"; break;
            case 6: $color = "#00CC33"; break;
            case 7: $color = "#FF3333"; break;
            case 8: $color = "#009999"; break;
        }
        print "<row id = '" . $d["ID_Cap"] . "'>";
        print "<cell>" . $cont++ . "</cell>";		
        if(in_array($d['Estatus'], array(1, 2, 3, 5))){
            print "<cell>" . htmlspecialchars('<i class="fa fa-2x fa-trash-o" onclick="Delete(' . $d["ID_Cap"] . ')"></i>') . "</cell>";
        }else{
            print "<cell></cell>";
        }
        print "<cell>" . htmlspecialchars($d["UR"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Fecha_Solicitud"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Nombre"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Tema"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Fecha_Inicio"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Fecha_Termino"]) . "</cell>";		
        print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'background: " . $color . "' onclick='Process(" . $d["ID_Cap"] . ")'>" . $d["Stage"] . "</div>") . "</cell>";		
//        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-folder-open" onclick="Process(' . $d["ID_Cap"] . ')"></i>') . "</cell>";       
        print "</row>";
    }
    print "</rows>";    
?>
