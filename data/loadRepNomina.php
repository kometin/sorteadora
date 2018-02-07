<?php
    require_once('../lib/secure.php');
    require_once('../lib/DBConn.php');
    require_once('../lib/krumo-0.4/class.krumo.php');
    
    
    if (stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml"))
        header("Content-type: application/xhtml+xml"); 
    else
        header("Content-type: text/xml");
    
    $db = new DBConn();
    
    $sql = "select RFC, CONCAT_WS(' ', Nombre, Paterno, Materno) as Operador, Fecha, Entrada, Salida, WEEK(Fecha, 1) as Semana,
            Horas, LimiteDobles, LimiteTriples, PagoNormal, PagoDobles, PagoTriples
            from jornadas j  
            join operadores o on o.id = j.operador_id 
            where o.Estatus = 1 " . ($oper?" and operador_id = $oper":"") . "
            and Fecha between '" . SimpleDate($d1) . "' and '" . SimpleDate($d2) . "' 
            order by Fecha, RFC;";
    $data = $db->getArray($sql);
    
    $sql = "select Fecha from calendario where Activo = 1 and Fecha between '" . SimpleDate($d1) . "' and '" . SimpleDate($d2) . "'";
    $calendar = $db->getArray($sql);
    $inhab = $db->lists($calendar, 'Fecha');
    
    print  "<?xml version='1.0' encoding='UTF-8'?>\n";
    print  "<rows pos='0'>";
    
    foreach($data as $k => $d){
        $simples = 0;
        $dobles = 0;
        $triples = 0;
        $holidays = 0;
        if($d[Horas] > $d[LimiteTriples]){
            $triples = $d[Horas] - $d[LimiteTriples];
            $dobles =  $d[LimiteTriples] - $d[LimiteDobles];
            $simples = $d[LimiteDobles];
        }elseif ($d[Horas] > $d[LimiteDobles]) {
            if(in_array($d[Fecha], $inhab)){
                $holidays = $d[Horas] - $d[LimiteDobles];
            }else{
                $dobles = $d[Horas] - $d[LimiteDobles];
            }
            $simples = $d[LimiteDobles];
        }else{
            $simples = $d[Horas];
        }
        $payment = ($simples * $d[PagoNormal]) + ($dobles * $d[PagoDobles]) + ($triples * $d[PagoTriples]) + ($holidays * $d[PagoTriples]);
     
        if($mode == "det"){
            print "<row id = '$k'>";
            print "<cell>" . ($k+1) . "</cell>";    
            print "<cell>" . $d[RFC] . "</cell>";    
            print "<cell>" . $d[Operador] . "</cell>";    
            print "<cell>" . SimpleDate($d[Fecha]) . "</cell>";    
            print "<cell>" . $d[Entrada] . "</cell>";    
            print "<cell>" . $d[Salida] . "</cell>";    
            print "<cell>" . $simples . "</cell>";    
            print "<cell>" . $d[PagoNormal] . "</cell>";    
            print "<cell>" . $dobles . "</cell>";    
            print "<cell>" . $d[PagoDobles] . "</cell>";    
            print "<cell>" . $triples . "</cell>";    
            print "<cell>" . $d[PagoTriples] . "</cell>";    
            print "<cell>" . $holidays . "</cell>";    
            print "<cell>" . $d[PagoTriples] . "</cell>";    
            print "<cell>" . number_format($payment, 2) . "</cell>";    
            print "</row>";
        }else{
            $identities[ $d[RFC] ] = $d[Operador];

            $concentrade[ $d[RFC] ][$d[Semana]][ S ][] = $simples;
            $concentrade[ $d[RFC] ][$d[Semana]][ D ][] = $dobles;
            $concentrade[ $d[RFC] ][$d[Semana]][ T ][] = $triples;
            $concentrade[ $d[RFC] ][$d[Semana]][ H ][] = $holidays;
            $concentrade[ $d[RFC] ][$d[Semana]][ P1 ][] = $d[PagoNormal];
            $concentrade[ $d[RFC] ][$d[Semana]][ P2 ][] = $d[PagoDobles];
            $concentrade[ $d[RFC] ][$d[Semana]][ P3 ][] = $d[PagoTriples];
            $concentrade[ $d[RFC] ][$d[Semana]][ G ][] = $payment;
        }
        
    }
    
    if($mode == "con"){
//        krumo($concentrade);
        $cont = 1;
        foreach($concentrade as $oper => $week){
            
            foreach($week as $w => $v){
                print "<row id = '$cont'>";
                print "<cell>" . $cont++ . "</cell>";    
                print "<cell>" . $oper . "</cell>";    
                print "<cell>" . $identities[$oper] . "</cell>";    
                print "<cell>" . $w . "</cell>";    
                print "<cell>" . array_sum($v[S]) . "</cell>";    
                print "<cell>" . implode(",", array_unique($v[P1])) . "</cell>";    
                print "<cell>" . array_sum($v[D]) . "</cell>";    
                print "<cell>" . implode(",", array_unique($v[P2])) . "</cell>";    
                print "<cell>" . array_sum($v[T]) . "</cell>";    
                print "<cell>" . implode(",", array_unique($v[P3])) . "</cell>";    
                print "<cell>" . array_sum($v[H]) . "</cell>";    
                print "<cell>" . implode(",", array_unique($v[P3])) . "</cell>";    
                print "<cell>" . number_format(array_sum($v[G]), 2) . "</cell>";    
                print "</row>";
            }
            
        }
    }
    
    print "</rows>";    