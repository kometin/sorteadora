<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    require_once('../lib/ext.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    
    $sql = "select o.id, Tipo_Medicion from servicios s join ordenes o on o.servicio_id = s.id where o.Clave = '$order'";
    $info = $db->getObject($sql);
    $sql = "select res.id, res.Lote, res.Fecha_Lote, res.Cantidad, res.Muestra, res.Locacion, res.Dia, res.Informacion, fac.Factor, fac.Especificacion, fac.Tolerancia, fac.Regla,
            (select Valor from resultado_factores where factor_id = fac.id and resultado_id = res.id and Activo = 1) as Valor 
            from resultados res
            left join orden_factores fac on fac.orden_id = res.orden_id and fac.Activo = 1 
            where res.Activo = 1 and res.orden_id = $info->id 
            order by res.id";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    $temp = "";
    foreach($data as $i => $d){
        switch($info->Tipo_Medicion){
            case 1:
                if($temp != $d[id]){
                    if($temp){
                        print "<cell>" . number_format($rejected) . "</cell>";
                        print "<cell>" . number_format($d[Cantidad] - $rejected) . "</cell>";
                        print "</row>";
                    }
                    $temp = $d[id];
                    $rejected = 0;
                    print "<row id = '" . $cont ."'>";
                    print "<cell>" . ($cont++) . "</cell>";
                    print "<cell>" . $d[Lote] . "</cell>";
                    print "<cell>" . SimpleDate($d[Fecha_Lote]) . "</cell>";
                    print "<cell>" . number_format($d[Cantidad]) . "</cell>";
                }
                print "<cell>" . number_format($d[Valor]) . "</cell>";
                $rejected += $d[Valor];
                break;
            case 3:
                print "<row id = '" . $cont ."'>";
                print "<cell>" . ($cont++) . "</cell>";
                print "<cell>" . $d[Locacion] . "</cell>";
                print "<cell>" . $d[Dia] . "</cell>";
                print "<cell>" . $d[Informacion] . "</cell>";
                print "</row>";
                break;
        }
       
    }
    
    switch($info->Tipo_Medicion){
        case 1:
            print "<cell>" . number_format($rejected) . "</cell>";
            print "<cell>" . number_format($d[Cantidad] - $rejected) . "</cell>";
            print "</row>";
            break;
    }
    
    print "</rows>";    
?>
