<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "select ID_Cap, Tipo_Cap, IFNULL(Curso, Tema) as Capacitacion, Inicio, Termino, Lugar_URL, Modalidad, c.Estatus, e.Estatus as Stage "
            . "from capacitaciones c "
            . "join estatus_cap e on e.ID_Estatus = c.Estatus "
            . "left join cat_cursos cu on cu.ID_Cur = c.ID_Curso "
            . "order by ID_Cap";
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
//        if(in_array($d['Estatus'], array(1, 2, 3, 5))){
            print "<cell>" . htmlspecialchars('<i class="fa fa-2x fa-trash-o" onclick="Delete(' . $d["ID_Cap"] . ')"></i>') . "</cell>";
//        }else{
//            print "<cell></cell>";
//        }
        print "<cell>" . htmlspecialchars($d["Tipo_Cap"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Capacitacion"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Inicio"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Termino"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Lugar_URL"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Modalidad"]) . "</cell>";		
        print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'background: " . $color . "' onclick='Process(" . $d["ID_Cap"] . ")'>" . $d["Stage"] . "</div>") . "</cell>";		
        print "</row>";
    }
    print "</rows>";    
?>
