<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    
    $sql = "select ID_Solicitud, Tipo_Solicitud, Tipo_Pos, Fecha_Solicitud, Fecha_Valida, s.Estatus, "
            . "CONCAT_WS('/', Folio, YEAR(Fecha_Solicitud)) as Folio, "
            . "CONCAT_WS(' ', e.Nombre, e.Apellido_Paterno, e.Apellido_Materno) as Nombre, "
            . "Secuencial, Denominacion, UR, "
            . "CASE s.Estatus "
            . "when 0 then 'Pendiente' "
            . "when 1 then 'Revisada' "
            . "when 2 then 'Rechazada' "
            . "when 3 then 'Aprobada' "
            . "when 4 then 'Declinada' "
            . "END as Leyenda "
            . "from solicitudes_estructura s "
            . "join estructura r on r.ID_Est = s.ID_Estructura "
            . "join cat_puestos p on p.ID_Puesto = r.ID_Puesto "
            . "join cat_unidadesresponsables u on u.ID_UR = r.ID_UR "
            . "join empleados e on e.ID_Emp = s.ID_Solicita "
            . (!getPermiso(2)?"where r.ID_UR = " . $_SESSION['RHUR']:"") . " "
            . "order by Fecha_Solicitud DESC";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        switch($d['Estatus']){
            case 0: $color = "gray"; break;
            case 1: $color = "#26A532"; break;
            case 2: $color = "red"; break;
            case 3: $color = "#495DF4"; break;
            case 4: $color = "black"; break;
        }
        print "<row id = '" . $d["ID_Solicitud"] . "'>";
        print "<cell>" . $cont++ . "</cell>";		 
        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-pencil" onclick="Edit(' .$d["ID_Solicitud"]. ', \'' . $d['Tipo_Solicitud'] . '\', ' . $d['Estatus'] . ')"></li>') . "</cell>"; 
        print "<cell>" . $d["Tipo_Solicitud"] . "</cell>";	
        print "<cell>" . $d["Tipo_Pos"] . "</cell>";	
        print "<cell>" . $d["Folio"] . "</cell>";	
//        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-search-plus" onclick="View(' . $d["ID_Solicitud"] . ')"></li>') . "</cell>";        
        print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'background-color: " . $color . "' onclick='View(" . $d["ID_Solicitud"] . ")'>" . ($d['Leyenda']) . "</div>") . "</cell>";		
        print "<cell>" . SimpleDate($d["Fecha_Solicitud"]) . "</cell>";	
        print "<cell>" . $d["Nombre"] . "</cell>";		
        print "<cell>" . $d["Secuencial"] . "</cell>";	
        print "<cell>" . $d["Denominacion"] . "</cell>";	
        print "<cell>" . $d["UR"] . "</cell>";	
        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-file-archive-o" onclick="Docs(' . $d["ID_Solicitud"] . ')"></li>') . "</cell>";        
        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-comments" onclick="Obs(' . $d["ID_Solicitud"] . ')"></li>') . "</cell>";        
        print "</row>";
    }
    print "</rows>";    
?>
