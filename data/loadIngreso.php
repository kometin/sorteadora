<?    require_once('../lib/secure.php');
    require_once('../lib/DBConn.php');

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    
    
    if($closed){
        $sql = "SELECT s.ID_Est, s.ID_Sol, Denominacion, Secuencial,Nivel, UR.UR, UR.Nombre, Direccion, Modalidad, 
			(SELECT CONCAT_WS(\" \",Nombre, Apellido_Paterno, Apellido_Materno) FROM empleados WHERE ID_Emp =(SELECT ID_Emp FROM estructura WHERE Secuencial= E.SecuencialPadre AND Estatus=1  AND Activo=1 LIMIT 1)   AND Activo=1 LIMIT 1)As Jefe
                         from solicitudingreso s
                         join estructura e on e.ID_Est = s.ID_Est 
                         join cat_puestos p on p.ID_Puesto = e.ID_Puesto 
                         join cat_tabulador t on t.ID_Tab = e.ID_Tab 
                         join cat_unidadesresponsables UR on UR.ID_UR = e.ID_UR 
                         join cat_direcciones dir on dir.ID_Dir = e.ID_Dir 
                         where s.Activo = 2 "  . (!getPermiso(8, true)?"and e.ID_UR = " . $_SESSION['RHUR']:"") . "
                         order by s.FechaInsert DESC";
    }else{
        $sql = "SELECT E.ID_Est,SI.ID_Sol, SI.Directa, Denominacion, Secuencial,Nivel, UR.UR, UR.Nombre, Direccion ,
			(SELECT CONCAT_WS(\" \",Nombre, Apellido_Paterno, Apellido_Materno) FROM empleados WHERE ID_Emp =(SELECT ID_Emp FROM estructura WHERE Secuencial= E.SecuencialPadre AND Estatus=1  AND Activo=1 LIMIT 1)   AND Activo=1 LIMIT 1)As Jefe,
			 SIE.ID_Etapa, CEI.Etapa, Modalidad
			FROM estructura E
			INNER JOIN cat_puestos P ON P.ID_Puesto=E.ID_Puesto
			INNER JOIN cat_tabulador T ON T.ID_Tab=E.ID_Tab
			INNER JOIN cat_unidadesresponsables UR ON UR.ID_UR=E.ID_UR
			INNER JOIN cat_direcciones D ON D.ID_Dir=E.ID_Dir

			LEFT JOIN solicitudingreso SI ON (SI.ID_Est=E.ID_Est AND SI.Activo = 1)
			LEFT JOIN solicitudingresoestatus SIE ON SI.ID_Sol=SIE.ID_Sol
			LEFT JOIN cat_etapasingreso CEI ON CEI.ID_Etapa=SIE.ID_Etapa
			WHERE E.Tipo_Pos = 'BASE' and E.Estatus=1 AND E.ID_Emp IS NULL " . (!getPermiso(8, true)?"and E.ID_UR = " . $_SESSION['RHUR']:"") . "
                        order by E.Secuencial,  SIE.ID_Ing DESC";
    }
    $data = $db->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    $contador = 1;
    $temp = "";
    foreach($data as $d){
        if($tmp != $d['Secuencial']){
            $tmp = $d['Secuencial'];
            $etapa=$d['Etapa']; 
            if($d['Modalidad']=='SCC' && !$d['Directa']){
                    include('loadIngresoSCC.php');
            }else{
                    include('loadIngresoOtros.php');
            }
            print "<row id = '" . $contador . "'>";
            print "<cell>" . $contador++ . "</cell>";  
            print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-search-plus" onclick="Ver(\'' . hideVar($d['ID_Est']) . '\')"></li>') . "</cell>";        

            print "<cell>" . $d['Denominacion'] . "</cell>";  
            print "<cell>" . $d['Secuencial'] . "</cell>";  
            print "<cell>" . $d['Nivel'] . "</cell>";  
    //        print "<cell>" . $d['Denominacion'] . "</cell>";  
            print "<cell>" . $d['UR'] . "</cell>";  
            //print "<cell>" . $d['Nombre'] . "</cell>";  
            print "<cell>" . $d['Direccion'] . "</cell>";  
            print "<cell>" . $d['Jefe'] . "</cell>";  
            print "<cell>".$d['Modalidad']."</cell>";  
            if($closed){
                print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'cursor:pointer; background-color: #56BCF7;' onclick='Resumen(" . $d['ID_Sol'] . ")'>Resúmen</div>") . "</cell>";		
            }else{
                print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'cursor:pointer; background-color: " . $color . "' onclick=\"".$funcion."\"  \">$etapa</div>") . "</cell>";		
            }
            if($d['ID_Sol'])
                print "<cell align=\"center\">" . htmlentities('<i class="fa fa-2x fa-history" onclick="Observaciones(\'' . $d['ID_Sol'] . '\')"></li>') . "</cell>";        
            else
                print "<cell></cell>";  
            print "</row>";        
        }
    }
    print "</rows>";   
    
    
    
?>
