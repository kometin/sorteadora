<?
	class Random{
		var $seed; 
		var $array; 
		var $num;
		var $result;
		var $dim;
		
		function __construct($seed = null, $result = null){
			$this->seed = ($seed?$seed:$this->Generate());
			$this->array = array();			
			$this->num = 1000;			
			$this->Randomize();
			$this->result = ($result?$result:$this->array[rand(0, $this->dim - 1)]);
		}		
	
		public function isValid(){
			return (in_array($this->result, $this->array));
		}
		
		private function Randomize(){
			$this->dim = 0;
			$next = $this->seed;
			for($i=0; $i<$this->num; $i++){
				$this->array[] = $next;
				$pow = pow($next, 2);
				$aux = $this->CompletePar($pow);
				$next = substr($aux, (strlen($aux)/2)-2, 4);
				$this->dim++;
				if($next == 0)
					break;
			}
		}
		
		private function CompletePar($val){			
			while(strlen($val) % 2 != 0 && strlen($val) < 4)
				$val = "0" .  $val;			
			return $val;
		}
		
		private function Generate(){
			return rand(1001, 999999);
		}		
		
	}
?>
