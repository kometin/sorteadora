<?php
require_once('lib/secure.php');
require_once('lib/ext.php');
require_once('lib/DBConn.php');
require_once('lib/templates.php');

$context = new Context();
$db = new DBConn();

if(!$action){
    
    $context->title = "Captura de nómina";
    $context->params = getParams();
    
    RenderTemplate('templates/nomina.tpl.php', $context, 'templates/base.php');
    
}elseif($action == "load"){
    
    $context->d1 = SimpleDate($d1);
    $context->d2 = SimpleDate($d2);
    
    $sql = "select * from jornadas where Fecha between '" . SimpleDate($d1) . "' and '" . SimpleDate($d2) . "' order by Fecha, operador_id";
    $data = $db->getArray($sql);
    $sql = "select id, CONCAT_WS(' ', Nombre, Paterno, Materno) as Operador, RFC from operadores where Estatus = 1 order by Operador";
    $oper = $db->getArray($sql);
    
    $work = array();
    foreach($data as $d){
        $work[ $d[Fecha] ][ $d[operador_id] ]['in'] = $d['Entrada'];
        $work[ $d[Fecha] ][ $d[operador_id] ]['out'] = $d['Entrada'];
    }
    
    $master = array();
    
    foreach($oper as $i => $o){
        $day = ManageDate($context->d1);
        $finish = ManageDate($context->d2);
        
        $master[ $i ]['id'] = $o[id];
        $master[ $i ]['name'] = $o[Operador];
        $master[ $i ]['rfc'] = $o[RFC];
        while($day <= $finish){
            
//            $master[ $o[id] ]['dates'][$day] = $day;
            $master[ $i ]['dates'][ SimpleDate($day) ]['in'] = $work[ $day ][ $o[id] ]['out'];
            $master[ $i ]['dates'][ SimpleDate($day) ]['out'] = $work[ $day ][ $o[id] ]['out'];
            $day = ManageDate($day, 0, 0, 1);
            
        }   
        
    }
    
    
    $context->data = json_encode($master);
    
    
    RenderTemplate('templates/nomina.load.php', $context);
    
}elseif($action == "save"){
    
    if($LT > $LD){
    
        foreach($_POST as $k => $v){
            $exp = explode("|", $k);
            $id = $exp[0];
            $date = SimpleDate($exp[1]);
            $type = $exp[2];

            if($v && !validateTime($v))
                die("Error en formato de hora para el día " . SimpleDate($date) . " ($v) ");

            if(!in_array($date, $master[$id]['dates']))
                $master[$id]['dates'][] = $date;
            $master[$id][$date][$type] = $v;
        }

    //    var_export($master);
    //    exit;
    //    echo "<p>";

        foreach($master as $k => $v){

            foreach($v[dates] as $d){
                
                if($v[$d][in] && $v[$d][out]){
                
                    $init_turn = date('Y-m-d H:i', strtotime($d." ".$v[$d][in]));
                    $end_turn = date('Y-m-d H:i', strtotime($d." ".$v[$d][out]));
                    $diff = DateDiff($end_turn, $init_turn, 'HORAS', true);

        //            echo $init_turn . "/" . $end_turn . " = " . $diff ."<br>";

                    if($rec = $db->exist("id", "jornadas", "operador_id = $k and Fecha = '$d'")) {
                        $sql = "update jornadas set "
                                . "Entrada = '" . $v[$d][in] . "', "
                                . "Salida = '" . $v[$d][out] . "', "
                                . "Horas = '$diff', "
                                . "LimiteDobles = '$LD', "
                                . "LimiteTriples = '$LT', "
                                . "PagoNormal = '$PN', "
                                . "PagoDobles = '$PD', "
                                . "PagoTriples = '$PT', "
                                . "updated_at = NOW(), "
                                . "updated_by = $_SESSION[SORTUSER] "
                                . "where id = $rec";
                    }else{
                        $sql = "insert into jornadas values(NULL, "
                                . "$k, "
                                . "'$d', "
                                . "'" . $v[$d][in] . "', "
                                . "'" . $v[$d][out] . "', "
                                . "'$diff', "
                                . "'$LD', "
                                . "'$LT', "
                                . "'$PN', "
                                . "'$PD', "
                                . "'$PT', "
                                . "NOW(), "
                                . "'$_SESSION[SORTUSER]')";
                    }

                    $db->execute($sql);
                }
            }

        }
    }else{
        die("El Límite de horas triples debe ser mayor al doble en cada caso");
    }
    
}