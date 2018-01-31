<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "select ID_Pro, ID_Envia, Tipo_Pro, Impacto, Propuesta, Beneficios, p.Estatus, e.Estatus as Stage "
            . "from propuesta_mejora p "
            . "join estatus_propuesta e on e.ID_Estatus = p.Estatus "
            . "where Activo = 1 ";
    if($view == 1)
        $sql .= " and ID_Envia = " . $_SESSION['RHID'];
    elseif($view == 2)
        $sql .= " and ID_Resp = " . $_SESSION['RHID'];
    elseif(!getPermiso(array(32, 33)))
        $sql .= " and Estatus = 5";
    $sql .= " order by ID_Pro";
    
    $data = $db->getArray($sql);
   
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        switch($d['Estatus']){
            case 1:
                $color = "#9999FF";
                break;
            case 2:
                $color = "#FF6666";
                break;
            case 3: 
                $color = "#3399FF";
                break;
            case 4: 
                $color = "#CC66FF";
                break;
            case 5: 
                $color = "#00CC33";
                break;
        }
        
        print "<row id = '" . $d["ID_Pro"] . "'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell>" . htmlspecialchars('<i class="fa fa-2x fa-pencil" onclick="Edit('. $d['ID_Pro'] . ')"></li>') . "</cell>";       
        print "<cell>" . htmlspecialchars('<i class="fa fa-2x fa-trash-o" onclick="' . ($d['ID_Envia']==$_SESSION['RHID']?(in_array($d['Estatus'], array(1, 2))?"Erase(" . $d['ID_Pro'] . ")":"Not('No es posible borrar la propuesta en este momento')"):"Not('No es posible borrar propuestas no enviadas por mi')") . '"></li>') . "</cell>";       
        print "<cell>" . htmlspecialchars('<i class="fa fa-2x fa-search-plus" onclick="View('. $d['ID_Pro'] . ')"></li>') . "</cell>";       
        print "<cell>" . ($d["Tipo_Pro"]) . "</cell>";		
        print "<cell>" . ($d["Impacto"]) . "</cell>";		
        print "<cell>" . ($d["Propuesta"]) . "</cell>";		
        print "<cell>" . ($d["Beneficios"]) . "</cell>";		
        print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'background: " . $color . "'>" . $d['Stage'] . "</div>") . "</cell>";       
        print "</row>";
    }
    print "</rows>";    
?>
