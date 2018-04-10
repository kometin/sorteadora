<?
    require_once('ext.php');
    
    session_start();
    
    $allowed = array("login.php", "confirm.php");
//    
    if(!$_SESSION[SORTUSER] && !in_array(getModule(), $allowed))
        Header ('location: login.php');
    
//    if(!empty($_GET) || !empty($_POST)){
        $db = new DBConn();
        $conn = $db->Connect();
        
        foreach($_GET as $k => $v){
            $var = str_replace("-", "_", $k);
            if(is_array($v)){
                    $array = array();
                    foreach($v as $val)
                            $array[] = mysqli_escape_string($conn, trim(($val)));
                    $_GET[$k] = $array;
                    $$var = $array;
            }else{
                    $_GET[$k] = mysqli_escape_string($conn, trim(($v)));
                    $$var = mysqli_escape_string($conn, trim(($v)));
            }
        }

        foreach($_POST as $k => $v){
            $var = str_replace("-", "_", $k);
            if(is_array($v)){
                    $array = array();
                    foreach($v as $val)
                            $array[] = mysqli_escape_string($conn, trim(ClearString($val)));
                    $_POST[$k] = $array;
                    $$var = $array;
            }else{
                    $_POST[$k] = mysqli_escape_string($conn, trim(ClearString($v)));
                    $$var = mysqli_escape_string($conn, trim(ClearString($v)));
            }
        }
//    }
?>
