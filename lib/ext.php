<?
//    ini_set('display_errors', 1);
//    require_once ('secure.php');
    require_once ('phpAES/AES.class.php');
    require_once ('class.phpmailer.php');
    require_once ('DBConn.php');
    
    date_default_timezone_set('America/Mexico_City');
    
    function getParams($id = null) {
        $db = new DBConn();
        $sql = "select * from parametros";
        foreach($db->getArray($sql) as $a)
            $params[ $a[ID_Param] ] = $a[Valor];
        if($id)
            return $params[$id];
        else
            return $params;
    }
    
    function CanBeHere(){
        foreach($_SESSION['TYSMENU'] as $m)
            if($m['link'] == getModule()) return true;
        Header('location: index.php');
    }
    
    function validateTime($time){
        $exp = explode(":", $time);
        if(count($exp) > 1){
            if(is_numeric($exp[0]) && $exp[0] >= 0 && $exp[0] <= 23){
                if(is_numeric($exp[1]) && $exp[1] >= 0 && $exp[1] <= 59){
                    if(!$exp[2] || (is_numeric($exp[2]) && $exp[2] >= 0 && $exp[2] <= 59))
                        return true;
                }
            }
        }
        return false;
    }
    
    function resizeImg($ruta, $nombre, $alto, $ancho, $nombreN, $extension, $calidad){
        $rutaImagenOriginal = $ruta.$nombre;
        if($extension == 'GIF' || $extension == 'gif'){
            $img_original = imagecreatefromgif($rutaImagenOriginal);
        }
        if($extension == 'jpg' || $extension == 'JPG'){
            $img_original = imagecreatefromjpeg($rutaImagenOriginal);
        }
        if($extension == 'png' || $extension == 'PNG'){
            $img_original = imagecreatefrompng($rutaImagenOriginal);
        }
        $max_ancho = $ancho;
        $max_alto = $alto;
        list($ancho,$alto)=getimagesize($rutaImagenOriginal);
        $x_ratio = $max_ancho / $ancho;
        $y_ratio = $max_alto / $alto;
        if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){//Si ancho
            $ancho_final = $ancho;
            $alto_final = $alto;
        } elseif (($x_ratio * $alto) < $max_alto){
            $alto_final = ceil($x_ratio * $alto);
            $ancho_final = $max_ancho;
        } else{
            $ancho_final = ceil($y_ratio * $ancho);
            $alto_final = $max_alto;
        }
        $tmp=imagecreatetruecolor($ancho_final,$alto_final);
        imagecopyresampled($tmp,$img_original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
        imagedestroy($img_original);
        return imagejpeg($tmp,$ruta.$nombreN,$calidad);
    }
    
    function UpperCase($str){
        $search = array("á", "é", "í", "ó", "ú", "ñ");
        $rep = array("Á", "É", "Í", "Ó", "Ú", "Ñ");
        $str = str_replace($search, $rep, $str);
        return strtoupper($str);
    }
    
    function LowerCase($str){
        $search = array("Á", "É", "Í", "Ó", "Ú");
        $rep = array("á", "é", "é", "ó", "ú");
        $str = str_replace($search, $rep, $str);
        return strtolower($str);
    }
    
    function getModule(){
        $pos = strrpos($_SERVER['SCRIPT_NAME'], "/");
        $module = substr($_SERVER['SCRIPT_NAME'], $pos + 1, strlen($_SERVER['SCRIPT_NAME']) - $pos);
        return $module;
    }
    
    function ValidateMail($mail){
        $first = explode("@", $mail);
        $second = explode(".", $first[1]);
        if(count($second) > 1){
            if(strlen($first[0]) == 0)
                return "El correo ingresado parece incorrecto";
        }else
            return "El correo ingresado parece incorrecto";
    }
   
    
    function quitarAcentos($text, $quitspace){
        $text = htmlentities($text, ENT_QUOTES, 'UTF-8');
        $text = strtolower($text);
        $patron = ($quitspace?"/[\, ]+/":"/[\,]+/");
        $patron = array (
                // Espacios, puntos y comas por guion
                 $patron => '_',

                // Vocales
                '/&agrave;/' => 'a',
                '/&egrave;/' => 'e',
                '/&igrave;/' => 'i',
                '/&ograve;/' => 'o',
                '/&ugrave;/' => 'u',

                '/&aacute;/' => 'a',
                '/&eacute;/' => 'e',
                '/&iacute;/' => 'i',
                '/&oacute;/' => 'o',
                '/&uacute;/' => 'u',

                '/&acirc;/' => 'a',
                '/&ecirc;/' => 'e',
                '/&icirc;/' => 'i',
                '/&ocirc;/' => 'o',
                '/&ucirc;/' => 'u',

                '/&atilde;/' => 'a',
                '/&etilde;/' => 'e',
                '/&itilde;/' => 'i',
                '/&otilde;/' => 'o',
                '/&utilde;/' => 'u',

                '/&auml;/' => 'a',
                '/&euml;/' => 'e',
                '/&iuml;/' => 'i',
                '/&ouml;/' => 'o',
                '/&uuml;/' => 'u',

                '/&auml;/' => 'a',
                '/&euml;/' => 'e',
                '/&iuml;/' => 'i',
                '/&ouml;/' => 'o',
                '/&uuml;/' => 'u',

                // Otras letras y caracteres especiales
                '/&aring;/' => 'a',
                '/&ntilde;/' => ($quitspace?'N':'Ñ')

                // Agregar aqui mas caracteres si es necesario

        );
        $text = preg_replace(array_keys($patron),array_values($patron),$text);
        return $text;
    }
    
    function setGrid($grid, $params, $paging = false, $multiline = false){
        $html = $grid . " = new dhtmlXGridObject('" . $grid . "');"
            . $grid . ".setImagePath('js/dhtmlx/imgs/');"
            . $grid . ".enableSmartRendering(true);"
            . $grid . ".setSkin('dhx_skyblue');"
            . $grid . ".setHeader('" . DisplayHeaderGrid($params, "Header") . "',null,[" . styleHeaderGrid(count($params)) . "]);"
            . $grid . ".setInitWidths('" . DisplayHeaderGrid($params, "Width") . "');"
            . $grid . ".attachHeader('" . DisplayHeaderGrid($params, "Attach") . "');"
            . $grid . ".setColAlign('" . DisplayHeaderGrid($params, "Align") . "');"
            . $grid . ".setColSorting('" . DisplayHeaderGrid($params, "Sort") . "');"
            . $grid . ".setColTypes('" . DisplayHeaderGrid($params, "Type") . "');"
            . $grid . ".enableMultiline(" . ($multiline?"true":"false") . ");";
        if($paging){
            $html .=  $grid . ".enablePaging(true,100,null,'pager_" . $grid . "',true,'infopage_" . $grid . "');"
                   . $grid . ".setPagingSkin( 'toolbar', 'dhx_skyblue' );";		 
        }
        $html .= $grid . ".init();"
               . $grid . ".attachEvent('onFilterEnd', function(){ Count(" . $grid . "); });";
        $footer = "Total,";
        for($i=1; $i<count($params); $i++)
            $footer .= ($params[$i][Sum] ? "#stat_total" : "#cspan") . ",";
        if(substr_count($footer, "stat_total"))
            $html.= $grid . ".attachFooter('" . substr($footer, 0, -1) . "');";
        echo $html;	
    }

    function styleHeaderGrid($num){
            $style=str_repeat('"text-align:center;font-size:8pt;font-weight:bold;vertical-align:middle;",',$num); 
            $style=substr($style,0,strlen($style)-1);
            return $style;
    }

    function DisplayHeaderGrid($array, $key){
            $val = "";
            for($i=0; $i<count($array)-1; $i++)
                    $val .= ParseFilter($array[$i][$key]) . ",";
            $val .= ParseFilter($array[count($array)-1][$key]);
            return $val;
    }

    function ParseFilter($val){
            switch($val){
                    case "txt": return "#text_filter"; break;
                    case "cmb": return "#select_filter"; break;
                    case "": return ""; break;
                    default: return $val; break;
            }
    }

    function DateFormat($date, $mode){
            $global = explode(" ", $date);
            $d = explode("-", $global[0]);		
            $t = explode(":", $global[1]);
            $time_format = $t[0] . ":" . $t[1];		
            switch($mode){
                    case 1:
                        switch($d[1]){
                                case "01":
                                        $month = "Enero";
                                break;
                                case "02":
                                        $month = "Febrero";
                                break;
                                case "03":
                                        $month = "Marzo";
                                break;
                                case "04":
                                        $month = "Abril";
                                break;
                                case "05":
                                        $month = "Mayo";
                                break;
                                case "06":
                                        $month = "Junio";
                                break;
                                case "07":
                                        $month = "Julio";
                                break;
                                case "08":
                                        $month = "Agosto";
                                break;
                                case "09":
                                        $month = "Septiembre";
                                break;
                                case "10":
                                        $month = "Octubre";
                                break;
                                case "11":
                                        $month = "Noviembre";
                                break;
                                case "12":
                                        $month = "Diciembre";
                                break;
                        }
                        $date_format = $d[2] . " de " . $month . " de " . $d[0];
                        return $date_format;
                    break;
                    case 2:
                            return $time_format;
                    break;
            }		
    }
    
    function getDomain(){
        $protocol = "http" . ($_SERVER['HTTPS']=="on"?"s":"") . "://";
        $domain = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];		
        $pos = strrpos($domain, "/");		
        return $protocol . substr($domain, 0, $pos) . "/";
    }

    function ManageDate($date, $year=0, $month=0, $day=0){
        return date("Y-m-d", strtotime($date . ($year>=0?" + ":" - ") . abs($year) ." year " . ($month>=0?" + ":" - ") . abs($month) . " month " . ($day>=0?" + ":" - ") . abs($day) . " days"));
    }
    
    function Encrypt($str){
         return sha1(md5($str));
    }

    function DayOfWeek($year, $month, $day, $format = 'str'){
         $day = date("w",mktime(0, 0, 0, $month, $day, $year));
         $week = array(1 => "Lunes", 
                       2 => "Martes", 
                       3 => "Miércoles", 
                       4 => "Jueves", 
                       5 => "Viernes", 
                       6 => "Sábado", 
                       7 => "Domingo");
         if($format == 'int')
             return $day;                  
         else
             return $week[$day];
    }
    
    function ClearString($str){
//        return str_replace("\"", "'", eregi_replace("[\n|\r|\n\r]", " ", $str));
//        $search = array("[\n]", "[\r]", "[\n\r]");
//        return eregi_replace($search, " ", $str);
        $buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
        return str_ireplace($buscar," ",$str);
    }
    
    function format($str, $total, $char){
        $add = "";
        for($i=0; $i<$total - strlen($str); $i++)
                $add .= $char;
        return $add . $str;
    }
    
    function RestarHoras($horaini,$horafin)
    {
	$horai=substr($horaini,0,2);
	$mini=substr($horaini,3,2);
	$segi=substr($horaini,6,2);
 
	$horaf=substr($horafin,0,2);
	$minf=substr($horafin,3,2);
	$segf=substr($horafin,6,2);
 
	$ini=((($horai*60)*60)+($mini*60)+$segi);
	$fin=((($horaf*60)*60)+($minf*60)+$segf);
 
	$dif=$fin-$ini;
 
	$difh=floor($dif/3600);
	$difm=floor(($dif-($difh*3600))/60);
	$difs=$dif-($difm*60)-($difh*3600);
	return date("H-i-s",mktime($difh,$difm,$difs));
    }

    function DateDiff($fecha_principal, $fecha_secundaria, $obtener = 'SEGUNDOS', $redondear = false){
        $f0 = strtotime($fecha_principal);
        $f1 = strtotime($fecha_secundaria);
        if ($f0 < $f1) { $tmp = $f1; $f1 = $f0; $f0 = $tmp; }
        $resultado = ($f0 - $f1);
        switch ($obtener) {
            default: break;
            case "MINUTOS"   :   $resultado = $resultado / 60;   break;
            case "HORAS"     :   $resultado = $resultado / 60 / 60;   break;
            case "DIAS"      :   $resultado = $resultado / 60 / 60 / 24;   break;
            case "SEMANAS"   :   $resultado = $resultado / 60 / 60 / 24 / 7;   break;
        }
        if($redondear) $resultado = round($resultado);
        return $resultado;
     }
     
     function SendMail($send, $subject, $name, $text, $attach = null){
            $from = "notificaciones_strc@guanajuato.gob.mx";
            $pass = "AdM1nM@1L001";	
            $mail = new PHPMailer();		
            $mail -> AddAddress ($send);
            $mail -> From = $from;
            $mail -> FromName = "Materiales";		
            $mail -> Subject = utf8_decode($subject);
            $mail -> Body = BodyMail($subject, $name, $text);
            $mail -> IsHTML(true);
            if($attach)
                $mail->AddAttachment($attach, $file);
            $mail->IsSMTP();
//            $mail->SMTPDebug  = 2;  //---->Esta linea es para hacer debug y ver los errores que se generan en el envio del mail.
            $mail->Host = 'ssl://smtp.gmail.com';
            $mail->Port = 465;
            $mail->SMTPAuth = true;
            $mail->Username = $from;
            $mail->Password = $pass;

            //Se verifica que se haya enviado el correo con el metodo Send().
            return $mail->Send();	
     }
     
     function BodyMail($subject, $name, $text){
            return utf8_decode("<table style = 'border: 2px outset #084773; border-collapse: collapse; font-size: 9pt; '>
                        <tr>
                            <td style = 'border: 2px outset #084773; padding: 5px; text-align: center; '>
                                <p><b>Secretaría de la Transparencia y Rendición de Cuentas </b></p>
                                <p><b>Gobierno del Estado de Guanajuato</b></p>
                            </td>
                        </tr>
                        <tr>
                            <td width = '700' style = 'border: 2px outset #084773; padding: 5px;'><center>" . $subject . "</center></td>
                        </tr>
                        <tr>
                            <td width = '700' style = 'border: 2px outset #084773; padding: 5px;'>
                                <p><b>Estimado(a): " . $name . "</b></p><p>" . $text . "</p>
                            </td>
                        </tr>
                        <tr>
                            <td style = 'color: red'><center><b>Favor de no responder este mensaje</b></center></td>
                        </tr>
                 </table>");
    }
    
    function Month($m){
        $array = array("01" => "Enero", 
                       "02" => "Febrero", 
                       "03" => "Marzo", 
                       "04" => "Abril", 
                       "05" => "Mayo", 
                       "06" => "Junio", 
                       "07" => "Julio", 
                       "08" => "Agosto", 
                       "09" => "Septiembre", 
                       "10" => "Octubre", 
                       "11" => "Noviembre", 
                       "12" => "Diciembre");
        return $array[$m];
    }
    
    function SimpleDate($datetime){
        $xplode = explode(" ", trim($datetime));
        $date = $xplode[0];
        $time = $xplode[1];
        $array = array(
            "01" => "Ene",
            "02" => "Feb", 
            "03" => "Mar", 
            "04" => "Abr", 
            "05" => "May", 
            "06" => "Jun", 
            "07" => "Jul", 
            "08" => "Ago", 
            "09" => "Sep", 
            "10" => "Oct", 
            "11" => "Nov", 
            "12" => "Dic"
        );
        if($date){
            $exp = explode("-", $date);
            if(is_numeric($exp[1]))
                return trim($exp[2] . "-" . $array[$exp[1]] . "-" . $exp[0] . " " . $time);
            else{
                $key = array_keys($array, $exp[1]);
                return trim($exp[2] . "-" . $key[0] . "-" . $exp[0] . " " . $time);
            }
        }
    }
    
    function lastDayMonth($year, $month){
        return date("d",(mktime(0,0,0,$month+1,1,$year)-1));
    }
    
    function ConvertTime($t, $addpoints = false){
        if(strlen($t) < 6)
            $t = format ($t, 6, "0");
        $s = substr($t, 4, 2);
        $m = substr($t, 2, 2);
        $h = substr($t, 0, 2);
        
        $seg = $s % 60;
        $min = ($m % 60) + floor($s / 60);
        $hour = ($h % 24) + floor($m / 60);
        
        return format($hour, 2, "0") . ($addpoints?":":"") .  format($min, 2, "0") . ($addpoints?":":"") . format($seg, 2, "0");
    }
    class hex2bin{
        /** convert
          * @access     public
          * @param      string  $hexNumber      convert a hex string to binary string
          * @return     string  binary string
          */
        function convert($hexString)
        {
                $hexLenght = strlen($hexString);
                // only hex numbers is allowed
                if ($hexLenght % 2 != 0 || preg_match("/[^\da-fA-F]/",$hexString)) return FALSE;

                unset($binString);
                for ($x = 1; $x <= $hexLenght/2; $x++)
                {
                        $binString .= chr(hexdec(substr($hexString,2 * $x - 2,2)));
                }

                return $binString;
        }
    }
?>