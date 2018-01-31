<?php

    require_once('../lib/DBConn.php');
    require_once('../lib/secure.php');
    require_once('../lib/checador.php');
    

    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    $ch = new DBConn("UNIS", "SQLDRIVER");
    $checador = new Checador();
    $start = Date("Y-m-d", strtotime(SimpleDate($d1)));
    $end = Date("Y-m-d", strtotime(SimpleDate($d2)));
    $inhab = $checador->GetInhab($start, $end);
    
    $sql = "select ID_Emp, NumEmp, Empleado, Direccion, Denominacion, Entrada, Salida from estructura_operativa where "
            . ($id?"ID_Emp = " . $id : "Tipo_Pos = 'BASE' and NumEmp is not null "
            . "and ID_Emp not in (select ID_Emp from asistencia_ex where FechaDelete is null)") 
            . " order by Empleado"; 
    
    $people = $db->getArray($sql);
    
    if(!$rec){
        foreach($people as $p)
            $concessions[ $p['NumEmp'] ] = $checador->GetConcessions($p['ID_Emp'], $start, $end);
    }
    
    $sql = "select C_Date, MIN(C_Time) as Entrada, MAX(C_Time) as Salida, CAST(C_Unique as INT) as Numero from tEnter "
            . "where C_Date BETWEEN '" . str_replace("-", "", $start) . "' and '" . str_replace("-", "", $end) . "' "
            . "and CAST(C_Unique as INT) " . ($id ? " = " . $people[0]['NumEmp'] : " <> 0" ) . " "
            . ($rec ? "and C_Time BETWEEN '" . str_replace(":", "", $t1) . "' and '" . str_replace(":", "", $t2) . "'" : "") . " "
            . "group by CAST(C_Unique as INT), C_Date "
            . "order by C_Date";
    
    foreach($ch->getArray($sql) as $c)
        $check[ $c['C_Date'] ][ $c['Numero'] ] = $c;
    
    
    print  "<?xml version='1.0' encoding='UTF-8'?>";
    print  "<rows pos='0'>";
    $cont = 1;
    $date = $start;
    while($date <= $end){
        if(!in_array($date, $inhab)){
            $d = str_replace("-", "", $date);
            foreach($people as $p){
                $in = $check[ $d ][ $p['NumEmp'] ]['Entrada'];
                $out = $check[ $d ][ $p['NumEmp'] ]['Salida'];
                if(!$rec){
                    $perm = false;
                    $just = false;
                    $entry = array();
                    if($in)
                        $entry[]['C_Time'] = $in;
                    if($out && $out != $in)
                        $entry[]['C_Time'] = $out;
                    $record = $checador->AnalyzeEntries($date, $entry, $p['Entrada'], $p['Salida']);
                    if($inc && $inc != $record->key)
                        continue;
                    elseif($concessions[ $p['NumEmp'] ][$date])
                         $perm = $concessions[ $p['NumEmp'] ][$date]['D'];
                    elseif($record->key != "OK")
                        $just = $db->exist ("Estatus", "asistencia_justificaciones", "ID_Emp = " . $p['ID_Emp'] . " and '" . $date . "' between Fecha_Inicio and Fecha_Fin");
                }
                if($rec || $record->key == "OK")
                    $incidence = "";
                elseif($perm && in_array("C", explode(",", $x)) )
                    $incidence = "<div class = 'flag-grid' style = 'background: #6666FF'>" . $record->key  . "</div>";
                elseif($perm && !in_array("C", explode(",", $x)) )
                    continue;
                elseif($just == 6 && in_array("A", explode(",", $x)) )
                    $incidence = "<div class = 'flag-grid' style = 'background: #00CC33'>" . $record->key  . "</div>";
                elseif($just == 6 && !in_array("A", explode(",", $x)) )
                    continue;
                elseif($just && in_array("P", explode(",", $x)) )
                    $incidence = "<div class = 'flag-grid' style = 'background: #CCCC00'>" . $record->key  . "</div>";
                elseif($just && !in_array("P", explode(",", $x)) )
                    continue;
                elseif(in_array("N", explode(",", $x)))
                    $incidence = "<div class = 'flag-grid' style = 'background: #FF6666'>" . $record->key  . "</div>";
                else
                    continue;
                
                print "<row id = '" . $cont . "'>";
                print "<cell>" . $cont++ . "</cell>";
                print "<cell>" . SimpleDate($date) . "</cell>";		
                print "<cell>" . $p['Empleado'] . "</cell>";		
                print "<cell>" . $p['NumEmp'] . "</cell>";		
                print "<cell>" . $p['Denominacion'] . "</cell>";		
                print "<cell>" . $p['Direccion'] . "</cell>";
//                print "<cell>" . htmlspecialchars("<div class = 'flag-grid' style = 'background: " . ($perm?"#6666FF":($just?($just=="6"?"#00CC33":"#CCCC00"):"#FF6666")) . "'>" . $record->key  . "</div>") . "</cell>";
                print "<cell>" . htmlspecialchars($incidence) . "</cell>";
                print "<cell>" . ($in?Date('H:i:s', strtotime($in)):"") . ($in != $out && !$rec?  " - " .  Date('H:i:s', strtotime($out)) : "") . "</cell>";
                print "</row>";
            }
        }
        $date = ManageDate($date, 0, 0, 1);
    }
    print "</rows>";    
?>
