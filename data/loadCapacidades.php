<?php

    require_once('../lib/secure.php');
    require_once('../lib/DBConn.php');

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();

    if(!$_GET['all'] || $_GET['all']==0)
		$and="WHERE Activo=1";
	
    $sql = "SELECT * FROM cat_capacidades $and
";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    $temp = "";
    foreach($data as $d){
        if($temp != $d['ID_Cap']){
	
            $temp = $d['ID_Cap'];
            print "<row id = '" . $d["ID_Profesion"] . "'>";
          //  print "<cell>" . $cont++ . "</cell>";		
		    if($d['Activo']==1){
            	print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Edit(\'' .$d["ID_Cap"]. '\')"></li>') . "</cell>";        
            	print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Del(\'' . $d["ID_Cap"] . '\')"></li>') . "</cell>";        
			}else{
            	print "<cell align=\"center\" ></cell>";        
           		print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-recycle" onclick="Res(\'' . $d['ID_Cap'] . '\')"></li>') . "</cell>";        
            
       		}
            print "<cell>" . $d["Capacidad"] . "</cell>";
			print "<cell>" ;
			
			if($d["Tipo"]=="gob")
				print "Gobierno";
			elseif($d["Tipo"]=="esp")
				print "Específicas";
			else
				print "Profesionales";				
			print   "</cell>";
            print "<cell>" . $d["Area"] . "</cell>";
            print "<cell>" . $d["Tema"] . "</cell>";
           // print "<cell>" . $d["Conocimiento"] . "</cell>";
            print "<cell>" . $d["Bibliografia"] . "</cell>";
            print "<cell>" . $d["Fundamento"] . "</cell>";

			
           	
            
            print "</row>";
        }
    }
    print "</rows>";    
?>
