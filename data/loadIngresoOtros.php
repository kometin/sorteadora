<?
    switch($d['ID_Etapa']){
        default: 
            $color = "#63C54F";
            $etapa='Solicitar cobertura'; 
            $funcion = "Solicita('" . hideVar($d['ID_Est']) . "')";
            break;
        case 10:
            $color = "#E6507F";
            $etapa='Requisición enviada'; 
            $funcion = "ValidaOtros('" . $d['ID_Sol'] . "')";
            break;
        case 11:
            $color = "red";
            $etapa='Requisición rechazada'; 
            $funcion = "Solicita('" . hideVar($d['ID_Est']) . "')";
            break;
        case 12:
            $color = "#3D6FF7";
            $etapa='Registro candidatos'; 
            $funcion = "CandidatosOtros('" . $d['ID_Sol'] . "')";
            break;
        case 13:
            $color = "red";
            $etapa='Registro declinado'; 
            $funcion = "CandidatosOtros('" . $d['ID_Sol'] . "')";
            break;
        case 14:
            $color = "#03C17F";
            $etapa='Evaluación técnica'; 
            $funcion = "Eval('" . $d['ID_Sol'] . "')";
            break;
        case 15:
            $color = "#55D6A9";
            $etapa='Evaluación psicométrica'; 
            $funcion = "Eval('" . $d['ID_Sol'] . "')";
            break;
        case 16:
            $color = "#FFB200";
            $etapa='Referencias laborales'; 
            $funcion = "Refer('" . $d['ID_Sol'] . "')";
            break;
        case 19:
            $color = "#AD59F7";
            $etapa='Entrevistas'; 
            $funcion = "Interview('" . $d['ID_Sol'] . "')";
            break;
        case 20:
            $color = "#4EB7F4";
            $etapa='Calificaciones'; 
            $funcion = "Calif('" . $d['ID_Sol'] . "')";
            break;
        case 21:
            $color = "#FF9400";
            $etapa='Vo.Bo. Contratación'; 
            $funcion = "VoBo('" . $d['ID_Sol'] . "')";
            break;
        case 22:
            $color = "#02CE98";
            $etapa='Cierre'; 
            $funcion = "Close('" . $d['ID_Sol'] . "')";
            break;
        case 23:
            $color = "#E6507F";
            $etapa='Requisición enviada'; 
            $funcion = "ValidaOtros('" . $d['ID_Sol'] . "')";
            break;
        case 24:
            $color = "red";
            $etapa='Requisición rechazada'; 
            $funcion = "Solicita('" . hideVar($d['ID_Est']) . "')";
            break;
        case 25:
            $color = "#02CE98";
            $etapa='Cierre'; 
            $funcion = "Close('" . $d['ID_Sol'] . "')";
            break;
    }
?>