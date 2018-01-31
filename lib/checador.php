<?php

require_once ('DBConn.php');
require_once ('ext.php');


/**
 * Description of checador
 *
 * @author christian
 */
class Checador {
    private $db;
    private $ch;
    private $dec;
    
    
    function __construct(){
        $this->db = new DBConn();
        $this->ch = new DBConn("UNIS", "SQLDRIVER");
    }
    
    public function GetEntry($emp, $date){
        $sql = "select C_Time from tEnter where CAST(C_Unique as INT) = " . $emp . " and C_Date = '" . str_replace("-", "", $date) . "' order by C_Time";
        return $this->ch->getArray($sql);
    }
    
    public function GetInhab($start, $end){
         $inhab = array();
         $sql = "select Fecha from dias_inhabiles where Activo = 1 and Fecha between '" . $start . "' and '" . ManageDate($end, 0, 1, 0) . "'";
         foreach ($this->db->getArray($sql) as $a)
            $inhab[] = $a['Fecha'];
         return $inhab;
    }

    public function GetConcessions($id, $start, $end, $target = null){
        $concession = array();
        $sql = "CALL GetConcession(" . $id . ", '" . $start . "', '" . $end . "')";
        foreach($this->db->getArray($sql) as $c){
            $today = Date('Y-m-d');
            $init = Date('Y-m-d', strtotime($c['Inicio']));
            $finish  = Date('Y-m-d', strtotime($c['Fin']));
            $key = reset(explode("=", $c['Horario']));
            $time = end(explode("=", $c['Horario']));
            $desc = $c['Descripcion'];
            $reach = $c['Alcance'];
            
            while($init <= $finish){
                if(array_key_exists($init, $concession)){
                    $k = $concession[$init]['K'];
                    $t = $concession[$init]['T'];
                    if($k != "F" && $key == "F"){
                        if($target && $init == $today) return true;
                        $concession[$init] = array("K" => $key, "T" => $time, "D" => $desc, "A" => $reach);
                    }elseif($k == "RR" && $key == "RR" && strtotime($t) < strtotime($time)){
                        if($target && $init == $today && strtotime($target) < strtotime($time)) return true;
                        $concession[$init] = array("K" => $key, "T" => $time, "D" => $desc, "A" => $reach);
                    }elseif($k == "RA" && $key == "RA" && strtotime($t) > strtotime($time)){
                        if($target && $init == $today && strtotime($target) > strtotime($time)) return true;
                        $concession[$init] = array("K" => $key, "T" => $time, "D" => $desc, "A" => $reach);
                    }elseif($k == "RR" && $key == "RA"){
                        if($target && $init == $today) return true;
                        $concession[$init] = array("K" => "M", "T" => $t . "|" . $time, "D" => "VARIAS CONCESIONES", "A" => $reach);
                    }elseif($k == "RA" && $key == "RR"){
                        if($target && $init == $today) return true;
                        $concession[$init] = array("K" => "M", "T" => $time . "|" . $t, "D" => "VARIAS CONCESIONES", "A" => $reach);
                    }elseif($k == "M"){
                        $i = reset(explode("|", $t));                    
                        $o = end(explode("|", $t));
                        if($key == "RR" && strtotime($i) < strtotime($time)){
                            $concession[$init]['T'] = $time . "|" . $o;
                        }elseif($key == "RA" && strtotime($o) > strtotime($time)){
                            $concession[$init]['T'] = $i . "|" . $time;
                        }
                    }
                }else{
                    if($target && $init == $today && ($key == "F" || ($key == "RR" && strtotime($target) < strtotime($time)) || ($key == "RA" && strtotime($target) > strtotime($time))))
                        return true;
                    $concession[$init] = array("K" => $key, "T" => $time, "D" => $desc, "A" => $reach);
                }
                $init = ManageDate($init, 0, 0, 1);
            }
        }
        
        $sql = "CALL GetVacations(" . $id . ", '" . $start . "', '" . $end . "')";
        foreach($this->db->getArray($sql) as $v){
            if($target) return true;
            $concession[$v['Fecha']] = array("K" => "F", "T" => "", "D" => "VACACIONES", "A" => "1,2,3,4,5");
        }
        
        if(!$target)
            return $concession;
    }
    
    public function AnalyzeEntries($date, $check, $in_time, $out_time, $concession = null){
        $delay1 = (15 * 60 + 59); // 15 min
        $delay2 = (30 * 60 + 59); // 30 min
        $days = array(0 => "Dom", 1 => "Lun", 2 => "Mar", 3 => "Mie", 4 => "Jue", 5 => "Vie", 6 => "Sab");
        $description = array("F" => "FALTA", "RR" => "REGISTRO DESPUES DE 30 MIN.", "O" => "OMISIÓN DE REGISTRO", "RA" => "REGISTRO ANTICIPADO", "R" => "RETARDO", "OK" => "CORRECTO");
        $week_day = Date('w', strtotime($date));
        $resp->date = $date;
        $resp->day = $days[$week_day];
        if($check){
            $first = reset($check);
            $check_in = Date('H:i:s', strtotime($first['C_Time']));
            if(count($check) > 1){
                $last = end($check);
                if($last['C_Time'] - $first['C_Time'] > 60)
                    $check_out = Date('H:i:s', strtotime($last['C_Time']));
            }
        }
        if($concession && $concession['K'] == 'F' && substr_count($concession['A'], $week_day)){
            $resp->key= "OK";
            $resp->class = "success";
            $resp->case = 1;
            if($check_in && $check_out){
                $resp->value = $check_in . " - " . $check_out;
            }else{
                $resp->value = $check_in;
            }
        }elseif($check){
            if($concession && $concession['K'] == 'M' && substr_count($concession['A'], $week_day)){
                $t1 = strtotime(reset(explode("|", $concession['T'])));
                $t2 = strtotime(end(explode("|", $concession['T'])));
                $in = Date('H:i:s', ($t1 + $delay1));
                $ret = Date('H:i:s', ($t1 + $delay2));
                $out = Date('H:i:s', $t2);
            }elseif($concession && $concession['K'] == 'RR' && substr_count($concession['A'], $week_day)){
                $time = strtotime($concession['T']);
                $in = Date('H:i:s', ($time + $delay1));
                $ret = Date('H:i:s', ($time + $delay2));
                $out = Date('H:i:s', strtotime($out_time));
            }elseif($concession && $concession['K'] == 'RA' && substr_count($concession['A'], $week_day)){
                $time = strtotime($concession['T']);
                $in = Date('H:i:s', strtotime($in_time) + $delay1);
                $ret = Date('H:i:s', strtotime($in_time) + $delay2);
                $out = Date('H:i:s', $time);
            }elseif($concession && $concession['K'] == 'F' && substr_count($concession['A'], $week_day)){
                $in = Date('H:i:s', strtotime('23:59:59'));
                $ret = Date('H:i:s', strtotime('23:59:59'));
                $out = Date('H:i:s', strtotime('00:00:00'));
            }else{
                $in = Date('H:i:s', (strtotime($in_time) + $delay1));
                $ret = Date('H:i:s', (strtotime($in_time) + $delay2));
                $out = Date('H:i:s', strtotime($out_time));
            }

            if($check_in && $check_out){
                if($check_in > $ret){
                    $resp->key = "RR";
                    $resp->class = "danger";
                    $resp->case = 2;
                }elseif($check_out < $out){
                    $resp->key = "RA";
                    $resp->class = "danger";
                    $resp->case = 4;
                }elseif($check_in > $in){
                    $resp->key ="R";
                    $resp->class = "warning";
                    $resp->case = 3;
                }else{
                    $resp->key = "OK";
                    $resp->class = "success";
                    $resp->case = 5;
                }
                $resp->value = $check_in . " - " . $check_out;
            }else{
                $only = $check_in;
                $resp->value = $only;
                $today = Date('Y-m-d');
                $hour = Date('H:i:s');
                if($date == $today){
                    if($hour < $out && $only <= $in){
                        $resp->key = "OK";
                        $resp->class = "success";
                        $resp->case = 6;
                    }elseif($hour < $out && $only > $ret){
                        $resp->key = "RR";
                        $resp->class = "danger";
                        $resp->case = 8;
                    }elseif($hour < $out && $only <= $ret){
                        $resp->key = "R";
                        $resp->class = "warning";
                        $resp->case = 7;
                    }else{
                        $resp->key = "O";
                        $resp->class = "danger";
                        $resp->case = 9;
                    }
                }else{
                    $resp->key = "O";
                    $resp->class = "danger";
                    $resp->case = 10;
                }
            }
        }else{
            $resp->key = "F";
            $resp->class = "danger";
            $resp->case = 11;
        }
        
        if($concession)
            $resp->label = $concession['D'];
        else
            $resp->label = $description[$resp->key];
        
        return $resp;
    }
    
    public function DataMatch($id, $emp, $in, $out, $start, $end){
        $inhab = $this->GetInhab($start, $end);
        $concession = $this->GetConcessions($id, $start, $end);
        
        $date = Date('Y-m-d', strtotime($start));
        $finish = Date('Y-m-d', strtotime($end));
        while($date <= $finish){
            if(!in_array($date, $inhab)){
                if(!$this->db->exist("ID_Just", "asistencia_justificaciones", "ID_Emp = " . $id . " and '" . $date . "' between Fecha_Inicio and Fecha_Fin")){
                    $check = $this->GetEntry($emp, $date);
                    $record = $this->AnalyzeEntries($date, $check, $in, $out, $concession[$date]);
                    if($record->key != "OK"){
                        $sql = "insert into asistencia_justificaciones (Fecha_Envia, ID_Emp, Fecha_Inicio, Fecha_Fin, Dias, Tipo_Just, Registro, Estatus) values("
                                . "NOW(), "
                                . "'" . $id . "', "
                                . "'" . $date . "', "
                                . "'" . $date . "', "
                                . "1, " // Dias
                                . "'" . $record->key . "', "
                                . "'" . $record->value . "', "
                                . "0)";
                        $this->db->execute($sql);
                    }
                }
            }
            $date = ManageDate($date, 0, 0, 1);
        }
    }
}