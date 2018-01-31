<?
switch($d['ID_Etapa']){
				
				case NULL: 
					$color = "#63C54F";
			 	    $etapa='Solicitar cobertura'; 
					if($d['Modalidad']=='SCC')
						$funcion="Solicita('". hideVar($d['ID_Est'])."')";
		 	    break;
				case '1': 
					$color = "#E6507F"; 
						$funcion="Validar('". hideVar($d['ID_Est'])."', '". hideVar($d['ID_Sol'])."')";		
						
						
				break;
				case 2: $color = "red"; 
						$funcion="Solicita('". hideVar($d['ID_Est'])."')";
				
				break;
				
				case '3': 
					$color = "#0FD8AD"; 			 	    
					$funcion="Reactivos('". hideVar($d['ID_Sol'])."')";			
				break;
				case '9': 
					$color = "#CE1C46"; 			 	    
					$funcion="Reactivos('". hideVar($d['ID_Sol'])."')";			
				break;	
				case '4': 
					$color = "#DA6DBE"; 
						$funcion="Comite('". hideVar($d['ID_Sol'])."')";			
				break;	
				case '5': 
					$color = "#FFB200"; 	
						$funcion="AcuerdoFechas('". hideVar($d['ID_Est'])."', '". hideVar($d['ID_Sol'])."')";			
				break;	
				case '6': 
					$color = "#8CDEF9"; 	
						$funcion="CandidadosSCC('". hideVar($d['ID_Sol'])."')";			
				break;	
				case '7': 
					$color = "#BCBC47";
						$funcion="Entrevistas('". hideVar($d['ID_Sol'])."')";			
				break;	
				case '26': 
					$color = "#33A0D6";
						$funcion="ValidaEntre('". hideVar($d['ID_Sol'])."')";			
				break;																					
				case '27': 
					$color = "#A50E0E";
						$funcion="CierraS('". hideVar($d['ID_Sol'])."', ".$d['ID_Etapa'].")";			
				break;	
				case '8': 
					$color = "#A50E0E";
						$funcion="CierraS('". hideVar($d['ID_Sol'])."', ".$d['ID_Etapa'].")";			
				break;								
				default:
				   $color = "#C0C0C0";				
				break;																					

			}	


?>