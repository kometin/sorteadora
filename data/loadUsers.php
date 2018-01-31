<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $sql = "select u.*, p.Perfil FROM empleados u LEFT JOIN perfiles p ON p.ID_Perfil=u.ID_Perfil where u.Activo = 1 order by RFC";
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $cont = 1;
    $temp = "";
    $x=0;
    foreach($data as $d){
        if($temp != $d['ID_Emp']){
            $x++;
            if($d["ID_Perfil"]=='')
                $d["ID_Perfil"]=0;
            $temp = $d['ID_Emp'];
            print "<row id = '" . $d["ID_Emp"] . "'>";
            print "<cell>" . $x. "</cell>";
            print "<cell>" . $d["Nombre"] ." ". $d["Apellido_Paterno"]." ". $d["Apellido_Materno"] . "</cell>";		
            print "<cell>" . $d["Correo"] . "</cell>";
            if($d["Perfil"]!='' || $d["Permisos"]!='')					
            	print "<cell>Si</cell>";
            else
                print "<cell>No</cell>";		
            if($d["Permisos"]!='')
                print "<cell>Personalizado</cell>";				
            else			
                print "<cell>" . $d["Perfil"] . "</cell>";						
            print "<cell align=\"center\" >" . htmlentities('<i class="fa  fa-2x fa-pencil" onclick="Edit(\'' .$d["ID_Emp"]. '\')"></li>') . "</cell>";        
            print "<cell align=\"center\" >" . htmlentities('<i class="fa  fa-2x fa-magic" onclick="Personalizar(\'' .$d["ID_Perfil"]. '\',\'' .hideVar($d["ID_Emp"]). '\')"></li>') . "</cell>";        
            print "<cell align=\"center\">" . htmlentities('<i class="fa  fa-2x fa-user-times" onclick="limpia(\'' .hideVar( $d["ID_Emp"] ). '\')"></li>') . "</cell>";        
            print "</row>";
        }
    }
    print "</rows>";    
?>
