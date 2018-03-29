<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "select o.id, Fecha_Orden, Servicio, Empresa, Numero_Parte, Folio, Descripcion, "
            . "Total_Partes, o.Estatus, e.Estatus as Stage, Clave "
            . "from ordenes o "
            . "join servicios s on s.id = o.servicio_id "
            . "join clientes c on c.id = o.cliente_id "
            . "join ordenes_estatus e on e.id = o.Estatus "
            . "where cliente_id = $_SESSION[SORTCLIENT] "
            . "order by Fecha_Orden DESC";
    
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        switch($d[Estatus]){
            case 1: $class = "default"; break;
            case 2: $class = "warning"; break;
            case 2: $class = "info"; break;
            case 4: $class = "success"; break;
            default: $class = "danger"; break;
        }
        
        print "<row id = '" . $d["id"] ."'>";
        print "<cell>" . $cont++ . "</cell>";
        print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-search-plus" onclick="Details(\'' .$d['id'] . '\')"></li>') . "</cell>";        
        
        print "<cell>" . SimpleDate($d["Fecha_Orden"])."</cell>";		
        print "<cell>" . substr($d['Empresa'], 0, 3) . format($d["Folio"], 3, "0") . "</cell>";
        print "<cell>" . ($d["Servicio"])."</cell>";		
        
        print "<cell>" . ($d["Numero_Parte"])."</cell>";		
        print "<cell>" . ($d["Descripcion"]) . "</cell>";
        print "<cell>" . ($d["Total_Partes"]) . "</cell>";
        print "<cell>" . htmlspecialchars("<div class = 'label label-$class label-grid'>" . $d["Stage"] . "</div>") . "</cell>";
        
        print "<cell align=\"center\" >" . htmlentities('<a href = "results.php?order=' . $d[Clave]. '"><i class="fa fa-2x fa-line-chart"></a>') . "</cell>";        
       print "</row>";
    }
    print "</rows>";    
?>
