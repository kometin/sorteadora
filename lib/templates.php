<?

class Context{
    var $__excludedVariables = array('__excludedVariables');

    function __initialize__($arrayOfValues=NULL){
        if($arrayOfValues){
            foreach($arrayOfValues as $Variable => $Value)
                $this->$Variable = $Value;
        }
    }
    
    function GetVariables(){
        $Variables = array();
        foreach( get_object_vars($this) as $Variable => $Value ){
            if( !in_array($Variable, $this->__excludedVariables) )
                $Variables[$Variable] =$Value;
        }
        return $Variables;
    }
}

function RenderTemplate($templatePath, $context=NULL, $masterTemplatePath=NULL){   
    Header('Expires:Mon, 26 Jul 1997 05:00:00 GMT');
    Header('Cache-Control: no-cache');
    Header('Pragma: no-cache');
//    if($context ){
//        foreach( get_object_vars($context) as $var => $value ){
//            if(is_object($value)){
//                foreach(get_object_vars($value) as $k => $v)
//                    $context->$var->$k = htmlspecialchars($v);
//            }elseif(is_array($value)){
//                 $array = array();
//                 foreach($value as $i => $row){
//                    if(is_array($row)){
//                        foreach($row as $k => $v){
//                            $array[$i][$k] = htmlspecialchars($v);
//                        }
//                    }else{
//                        $array[$i] = htmlspecialchars($row);
//                    }
//                 }
//                 $context->$var = $array;
//            }else{
//                $context->$var = htmlspecialchars($value);
//            }
//        }
////        var_dump($context);
//    }
    if($masterTemplatePath){
        include($templatePath);
        include($masterTemplatePath);
    }else{
        include($templatePath);
    }
    
}

?>
