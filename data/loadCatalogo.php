<?php

    require_once('../lib/secure.php');
    require_once('../lib/DBConn.php');
    require_once('../lib/catalogos.php');
	$cat=new catalogos();	
	//$datosCat=$cat->datos('dependencia');

	$activo = $_GET['cat'];
	
	$datosCat=$cat->datos($activo);
    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    if(!$_GET['all'] || $_GET['all']==0)
		$and="WHERE Activo=1";
    $db = new DBConn();
	if(isset($datosCat->orderBy))
		$order=$datosCat->orderBy;
    $sql = "SELECT * FROM
			".$datosCat->tabla."  
			$and
			 $order";

    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    foreach($data as $d){
        print  "<row id = '" . $d[$datosCat->identificador] . "'>";
        if($d['Activo']==1){
            print "<cell align=\"center\" >" . htmlentities('<i class="fa fa-2x fa-pencil" onclick="Edit(\'' .$d[$datosCat->identificador] . '\')"></li>') . "</cell>";        
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-trash-o" onclick="Del(\'' . $d[$datosCat->identificador] . '\')"></li>') . "</cell>";        
        }else{
            print "<cell align=\"center\" ></cell>";        
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-recycle" onclick="Res(\'' . $d[$datosCat->identificador] . '\')"></li>') . "</cell>";        
            
        }
		$datosCat->tipoDato;
		$f=0;
		foreach($datosCat->campos as $campo){
			if($datosCat->tipoDato[$f]=='numeric money')
				print  "<cell>$" . number_format($d[$campo],2) . "</cell>";
			else
				print  "<cell>" . $d[$campo] . "</cell>";

			$f++;
		}        
        print  "</row>";
    }
    print  "</rows>";
?>
