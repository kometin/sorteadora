<?php
require('fpdf.php');

class PDF_MC_Table extends FPDF
{
var $widths;
var $aligns;
var $border = 'LR';

function __construct($orientation) {
   parent::__construct($orientation, "mm", "A4");
}

function Footer()
	   {
		  //Vai para 1.5 cm da parte inferior
			
		  $this->SetY(-10);
			
		  //Seleciona a fonte Arial itálico 8
			
		  $this->SetFont('Arial','I',8);
			
		  //Imprime o número da página corrente e o total de páginas
			
		  $this->Cell(0,10,'Hoja '.$this->PageNo().' de {total}',0,0,'R');
	   }
function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function SetBorder($b)
{
    $this->border = $b;
}

function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
        $border_bottom = ($this->border == 1 || substr_count(strtoupper($this->border), 'B'));
        $border_style = ($border_bottom ? is_numeric($this->border) ? 'LTR' : str_replace('B', '', $this->border) : $this->border);
        $min_x = 300;
        $max_y = 0;
        
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
                //Draw the border
//		$this->Rect($x,$y,$w,$h);
		//Print the text
		$this->MultiCell($w,5,utf8_decode($data[$i]),$border_style,$a);
                if($min_x > $x) $min_x = $x;
                if($max_y < $this->GetY()) $max_y = $this->GetY();
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
        if($border_bottom){
            $this->Line ($min_x, $max_y, ($x + $w), $max_y);
        }
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}
}
?>
