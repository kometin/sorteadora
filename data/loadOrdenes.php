<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    if($_GET['all'] &&  $_GET['all']==1)
        $and=" AND o.Estatus=0";       
    else
        $and=" AND o.Estatus = 1";   
    $db = new DBConn();
    $sql = "SELECT o.*, c.Direccion,c.Empresa, s.Servicio, of.orden_id,of.id as id_orden_factor,s.Tipo_Medicion
                FROM ordenes o
                INNER JOIN clientes c ON c.id=o.cliente_id 
                INNER JOIN servicios s on o.servicio_id=s.id
                left join orden_factores of ON of.orden_id=o.id
                join ordenes_estatus e on e.id = o.Estatus 
                
            where o.id is not null   $and ORDER by o.id DESC";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
        print "<row id = '" . $d["id"] ."'>";
        print "<cell>" . $cont++ . "</cell>";
   //     print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-search-plus" onclick="Ver(\'' .$d['id'] . '\')"></li>') . "</cell>";        
        if($d['Estatus']==1){
        
            print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Edit(\'' .$d['id'] . '\')"></li>') . "</cell>";        
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Del(\'' . $d['id'] . '\')"></li>') . "</cell>";        
        }else {
            print "<cell></cell><cell></cell>";
        }
        print "<cell>" . htmlspecialchars(SimpleDate($d["Fecha_Orden"]))."</cell>";		
        print "<cell>" . htmlspecialchars($d["Empresa"])."</cell>";		
        print "<cell>" . htmlspecialchars($d["Servicio"])."</cell>";        
        print "<cell>" . htmlspecialchars($d["Numero_Parte"])."</cell>";		
        print "<cell>" . htmlspecialchars($d["Descripcion"])."</cell>";	
        if(strlen($d["Folio"])==1)
            $Folio="00".$d["Folio"];
        if(strlen($d["Folio"])==2)
            $Folio="0".$d["Folio"];

        print "<cell>" . htmlspecialchars(strtoupper(substr($d['Empresa'],0,3)).$Folio)."</cell>";	
        print "<cell>" . htmlspecialchars($d["Total_Partes"])."</cell>";
        if($d['Tipo_Medicion']==1 || $d['Tipo_Medicion']==2){    
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-cogs" onclick="Factores(\'' . $d['id'] . '\',\'' . $d['Tipo_Medicion'] . '\')"></li>') . "</cell>";        
            if($d['orden_id']!='')
                print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-list" onclick="Resultados(\'' . $d['orden_id'] . '\',\'' . $d['Tipo_Medicion'] . '\')"></li>') . "</cell>";        
            else
                print "<cell></cell>";
        }else{
            print "<cell></cell>";        
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-list" onclick="Resultados(\'' . $d['id'] . '\',\'' . $d['Tipo_Medicion'] . '\')"></li>') . "</cell>";        

        }
        switch($d[Estatus]){
            case 1: $class = "default"; break;
            case 2: $class = "warning"; break;
            case 2: $class = "info"; break;
            case 4: $class = "success"; break;
            default: $class = "danger"; break;
        }
        print "<cell>" . htmlspecialchars("<div class = 'label label-$class label-grid'>" . $d["Stage"] . "</div>") . "</cell>";
        
        /*
        $estatus=
         clas label 
         * <div class="label">
         * 1 default
         * 2 
         */
                
    //    print "<cell>" . htmlspecialchars(SimpleDate($d["Fecha_Cierre"]))."</cell>";		
    //    print "<cell>" . SimpleDate($d["updated_at"]) . "</cell>";
        
       print "</row>";
    }
    print "</rows>";    
?>
