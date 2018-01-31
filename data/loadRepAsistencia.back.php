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
            
    $sql = "select ID_Emp, NumEmp, Empleado, Direccion, Denominacion, Entrada, Salida from estructura_operativa where "
            . ($id?"ID_Emp = " . $id : "Tipo_Pos = 'BASE' and NumEmp is not null "
            . "and ID_Emp not in (select ID_Emp from asistencia_ex where FechaDelete is null)") 
            . " order by Empleado"; 
    foreach($db->getArray($sql) as $a){
        $data[ $a['NumEmp'] ] = $a;
        if($id && !$emp) $emp = $a['NumEmp'];
        if(!$rec)
            $concessions[ $a['NumEmp'] ] = $checador->GetConcessions($a['ID_Emp'], $start, $end);
    }
    
    
    $sql = "select C_Date, MIN(C_Time) as Entrada, MAX(C_Time) as Salida, CAST(C_Unique as INT) as Numero from tEnter "
            . "where C_Date BETWEEN '" . str_replace("-", "", $start) . "' and '" . str_replace("-", "", $end) . "' "
            . "and CAST(C_Unique as INT) " . ($id ? " = " . $emp : " <> 0" ) . " "
            . ($rec ? "and C_Time BETWEEN '" . str_replace(":", "", $t1) . "' and '" . str_replace(":", "", $t2) . "'" : "") . " "
            . "group by C_Unique, C_Date "
            . "order by C_Date";
    
    $check = $ch->getArray($sql);
    
    print  "<?xml version='1.0' encoding='UTF-8'?>";
    print  "<rows pos='0'>";
    $cont = 1;
    foreach($check as $c){
        if($data[ $c['Numero'] ]){
            $just = false;
            $date = Date('Y-m-d', strtotime($c['C_Date']));
            if(!$rec){
                $entry = array();
                if($c['Entrada'])
                    $entry[]['C_Time'] = $c['Entrada'];
                if($c['Salida'] && $c['Salida'] != $c['Entrada'])
                    $entry[]['C_Time'] = $c['Salida'];

                $record = $checador->AnalyzeEntries($date, $entry, $data[$c['Numero']]['Entrada'], $data[$c['Numero']]['Salida'], $concessions[$c['Numero']][$date] );
                if($inc && $inc != $record->key)
                    continue;
                elseif($record->key != "OK")
                    $just = $db->exist ("ID_Just", "asistencia_justificaciones", "ID_Emp = " . $data[$c['Numero']]['ID_Emp'] . " and '" . $date . "' between Fecha_Inicio and Fecha_Fin and Estatus = 6");
            }
            print "<row id = '" . $cont . "'>";
            print "<cell>" . $cont++ . "</cell>";
            print "<cell>" . SimpleDate($date) . "</cell>";		
            print "<cell>" . $data[ $c['Numero'] ]['Empleado'] . "</cell>";		
            print "<cell>" . $data[ $c['Numero'] ]['NumEmp'] . "</cell>";		
            print "<cell>" . $data[ $c['Numero'] ]['Denominacion'] . "</cell>";		
            print "<cell>" . $data[ $c['Numero'] ]['Direccion'] . "</cell>";
            print "<cell>" . htmlspecialchars($rec || $record->key == "OK" ? "" : "<div class = 'flag-grid' style = 'background: " . ($just?"#00CC33":"#FF6666") . "'>" . $record->key . "</div>") . "</cell>";
            print "<cell>" . Date('H:i:s', strtotime($c['Entrada'])) . ($c['Entrada'] != $c['Salida'] && !$rec?  " - " .  Date('H:i:s', strtotime($c['Salida'])) : "") . "</cell>";
            print "</row>";
        }
    }
    print "</rows>";    
?>
