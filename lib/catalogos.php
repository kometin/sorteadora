<?
class catalogos{
	function datos($catalogo=''){
		switch ($catalogo){			
			case $catalogo=='grados':
				$this->tabla="cat_gradosacademicos";
				$this->titulo="Grado Acad&eacute;mico";
				$this->campos=array("Grado");
				$this->titulos=array("Grado");
				$this->identificador="ID_Grado";
				$this->anchosColum=array("*");
				$this->align=array("left");
				$this->tipoDato=array("");				
				return $this;
			break;    
			case $catalogo=='institucion':
				$this->tabla="cat_instituciones";
				$this->titulo="Institucion educativa";
				$this->campos=array("Institucion");
				$this->titulos=array("Nombre de la instituci&oacute;n");
				$this->identificador="ID_Institucion";
				$this->anchosColum=array("*","100");
				$this->align=array("left");
				$this->tipoDato=array("");				
				return $this;
			break;    
			case $catalogo=='jornadas':
				$this->tabla="cat_jornadas";
				$this->titulo="Jornada laboral";
				$this->campos=array("Jornada", "Entrada", "Salida");
				$this->titulos=array("Jornada laboral");
				$this->identificador="ID_Jornada";
				$this->anchosColum=array("*","200", "200");
				$this->align=array("left");
				$this->tipoDato=array("");
				return $this;
			break;    
			case $catalogo=='idiomas':
				$this->tabla="cat_idiomas";
				$this->titulo="Idioma";
				$this->campos=array("Idioma");
				$this->titulos=array("Idioma");
				$this->identificador="ID_Idioma";
				$this->anchosColum=array("*","100");
				$this->align=array("left");
				$this->tipoDato=array("");				
				return $this;
			break;  
			case $catalogo=='Tabulador':
				$this->tabla="cat_tabulador";
				$this->titulo="Nivel tabular";
				$this->campos=array("Nivel","Descripcion", "ClaveFuncional");
				$this->titulos=array("Nivel","Descripción", "Clave funcional");
				$this->identificador="ID_Tab";
				$this->anchosColum=array("100","*","150");
				$this->align=array("center","left", "left");
				$this->tipoDato=array("numeric","", "");	
				$this->orderBy=" order by Nivel DESC ";			
				return $this;
			break;  	
			case $catalogo=='Dir':
				$this->tabla="cat_direcciones";
				$this->titulo="Direcciones";
				$this->campos=array("Numero", "Direccion");
				$this->titulos=array("Número", "Dirección");
				$this->identificador="ID_Dir";
				$this->anchosColum=array("100", "*");
				$this->align=array("center", "left");
				$this->tipoDato=array("numeric", "");				
				return $this;
			break;  						  						
			
                                                            
                    
		}
	
	}
}

?>