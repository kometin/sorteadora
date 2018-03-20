<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    require_once('../lib/ext.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $id = Cipher($order, "dec");
    if(!is_numeric($id))
        die("ORDER INVALID");
    
    $sql = "select Tipo_Medicion from servicios s join ordenes o on o.servicio_id = s.id where o.id = $id";
    $type = $db->getOne($sql);
    $sql = "select res.*, fac.*, rf.* from resultados res
            left join orden_factores fac on fac.orden_id = res.orden_id and fac.Activo = 1 
            left join resultado_factores rf on rf.factor_id = fac.id 
            where res.Activo = 1 and  res.orden_id = $id
            order by res.id";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    $temp = "";
    foreach($data as $i => $d){
        print "<row id = '" . $i ."'>";
        print "<cell>" . ($i+1) . "</cell>";
        
        switch($type){
            case 1:
                if($temp != $d['id']){
                    $temp = $d[id];
                    print "<cell>" . $d[Lote] . "</cell>";
                    print "<cell>" . SimpleDate($d[Fecha_Lote]) . "</cell>";
                    print "<cell>" . number_forma($d[Cantidad]) . "</cell>";
                }
                print "<cell>" . number_forma($d[Valor]) . "</cell>";
//                print "<cell>" . number_forma($d[Valor]) . "</cell>";
                break;
        }
       
        print "</row>";
    }
    print "</rows>";    
?>
