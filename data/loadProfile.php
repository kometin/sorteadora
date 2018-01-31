<?php

    require_once('../lib/DBConn.php');
    session_start();

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    if(!$_GET['all'] || $_GET['all']==0)
		$and=" WHERE Activo=1";
	
		
    $sql = "select * from perfiles $and order by perfil";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($data as $d){
            print "<row id = '" . $d["ID_Perfil"] . "'>";
           // print "<cell>" . $cont++ . "</cell>";	
		    if($d['Activo']==1){	
          		print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-search-plus" onclick="View(\'' . $d["ID_Perfil"] . '\')"></li>') . "</cell>";        
		   
	        	print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Edit(\'' .$d["ID_Perfil"]. '\')"></li>') . "</cell>";        
            	print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Del(\'' . $d["ID_Perfil"] . '\')"></li>') . "</cell>";        
			}else
				print "<cell></cell><cell></cell><cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-recycle" onclick="Res(\'' . $d['ID_Perfil'] . '\')"></li>') . "</cell>";        


					   

            print "<cell>" . $d["Perfil"] . "</cell>";		
            print "</row>";
    }
    print "</rows>";    
?>
