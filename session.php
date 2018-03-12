<?
	session_start();
	foreach($_SESSION as $key => $val){
		echo "[ " . $key . " ] => ";
                if(is_array($val)){
                    foreach($val as $k => $v)
                        echo $k . " -> " . $v . ", ";
                }else
                    echo $val;
                echo "<br>";
	}
?>