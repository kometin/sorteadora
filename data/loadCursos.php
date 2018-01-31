<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "Select ID_Curso, Curso, Clave, Transversal, Fecha_Inicio, Fecha_Fin, Duracion, Mecanismo, Estatus "
            . "from cursos c "
            . "join cat_cursos cat on cat.ID_Cur = c.ID_Cat "
            . "where c.Activo = 1 and YEAR(Fecha_Inicio) = " . $year . " "
            . "order by Fecha_Inicio";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        switch($d['Estatus']){
            case 1:
                if(strtotime($d['Fecha_Fin']) < strtotime(Date('Y-m-d'))){
                    $stat = "Concluido";
                    $color = "#2AB917";
                }else{
                    $stat = "En proceso";
                    $color = "#C00ED4";
                }
                break;
            case 2:
                $stat = "Cerrado";
                $color = "#4361EB";
                break;
        }
        print "<row id = '" . $d["ID_Curso"] . "-" . $cont . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell>" . htmlspecialchars('<i class="fa  fa-2x fa-trash-o" onclick="Delete(' .$d["ID_Curso"]. ')"></li>') . "</cell>";       
        print "<cell>" . htmlspecialchars($d["Curso"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Clave"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Transversal"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Fecha_Inicio"]) . "</cell>";		
        print "<cell>" . SimpleDate($d["Fecha_Fin"]) . "</cell>";		
        print "<cell>" . htmlspecialchars($d["Duracion"]) . " hrs.</cell>";
        print "<cell>" . htmlspecialchars($d["Mecanismo"]) . "</cell>";
        print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'background:" . $color . "' onclick='Edit(" .$d["ID_Curso"]. ")'>" . $stat . "</div>") . "</cell>";
        print "</row>";
    }
    print "</rows>";    
?>
