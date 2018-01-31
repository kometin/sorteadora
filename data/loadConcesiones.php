<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "select ID_Emp, Empleado from estructura_operativa order by ID_Emp";
    foreach($db->getArray($sql) as $a)
        $employ[ $a['ID_Emp'] ] = $a['Empleado'];
    
    $sql = "select ID_Con, Inicio, Fin, Horario, Descripcion, Aplicacion, Alcance, Documento, a.FechaUpdate, Empleado "
            . "from asistencia_concesiones a "
            . "join estructura_operativa e on e.ID_Emp = a.ID_User "
            . "where (YEAR(Inicio) = " . $year . " or YEAR(Fin) = " . $year . ") "
            . "and Activo = 1 "
            . "order by Inicio, Fin";
            
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        if($d['Aplicacion']){
            $applied = array();
            foreach(explode(",", $d['Aplicacion']) as $x){
                if(array_key_exists($x, $employ))
                    $applied[] = $employ[$x];
            }
        }
        if($d['Horario'] == "F")
            $schedule = "TODO EL DíA";
        elseif(substr_count($d['Horario'], "RR"))
            $schedule = "ENTRADA TARDE: " . end(explode ("=", $d['Horario']));
        elseif(substr_count($d['Horario'], "RA"))
            $schedule = "SALIDA TEMPRANO: " . end(explode ("=", $d['Horario']));
        
        if(count(explode(",", $d['Alcance'])) < 5){
            $search = array(1, 2, 3, 4, 5);
            $replace = array("Lún", "Mar", "Mie", "Jue", "Vie");
            $days = "(" . str_replace($search, $replace, $d['Alcance']) . ")";
        }else{
            $days = "";
        }
        
        print "<row id = '" . $d["ID_Con"] . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-trash-o" onclick="DeleteCon(' .$d["ID_Con"]. ')"></li>') . "</cell>";       
        print "<cell>" . SimpleDate($d["Inicio"]) . ($d['Inicio']!=$d['Fin']?" - ".SimpleDate($d['Fin']):"") . htmlspecialchars("<br>".$days) . "</cell>";
        print "<cell>" . $schedule . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Descripcion"]) . "</cell>";		
        print "<cell>" . ($d['Aplicacion']?implode(",", $applied):"TODO EL PERSONAL") . "</cell>";		
        print "<cell>" . ($d['Documento']? htmlspecialchars("<a href = 'file.php?url=" . $d['Documento'] . "&file=Concesion_" . $d['ID_Con'] . "." . end(explode(".", $d['Documento'])) . "'><i class = 'fa fa-file fa-2x'></i>") : "") . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Empleado"] . "<br>" . SimpleDate($d['FechaUpdate'])) . "</cell>";		
        print "</row>";
    }
    print "</rows>";    
?>
