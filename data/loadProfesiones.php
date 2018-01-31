<?php

    require_once('../lib/secure.php');
    require_once('../lib/DBConn.php');

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();

    if(!$_GET['all'] || $_GET['all']==0)
		$and="WHERE p.Activo=1";
			
    $sql = "SELECT P.*, Grado  FROM cat_profesiones P 
			INNER JOIN cat_gradosacademicos GA ON P.ID_Grado=GA.ID_Grado 
			$and
";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    $temp = "";
    foreach($data as $d){
        if($temp != $d['ID_Profesion']){
	
            $temp = $d['ID_Profesion'];
            print "<row id = '" . $d["ID_Profesion"] . "'>";
          //  print "<cell>" . $cont++ . "</cell>";		
         if($d['Activo']==1){
           print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Edit(\'' .$d["ID_Profesion"]. '\')"></li>') . "</cell>";        
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Del(\'' . $d["ID_Profesion"] . '\')"></li>') . "</cell>";        
        }else{
            print "<cell align=\"center\" ></cell>";        
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-recycle" onclick="Res(\'' . $d["ID_Profesion"] . '\')"></li>') . "</cell>";        
            
        }         

            print "<cell>" . $d["Profesion"] . "</cell>";
			print "<cell>" . $d["Grado"] . "</cell>";
            print "<cell>";
					$rsIns=$db->getArray("SELECT Institucion FROM cat_instituciones I 
							INNER JOIN rel_prefesioninstitucion RPI ON RPI.ID_Institucion=I.ID_Institucion
							WHERE RPI.ID_Profesion = ".$d['ID_Profesion']);
				if(count($rsIns)>0){
					foreach($rsIns as $rowIns)
						print $rowIns['Institucion']."<br/>";
				}
			print "</cell>";		
            
            print "</row>";
        }
    }
    print "</rows>";    
?>
