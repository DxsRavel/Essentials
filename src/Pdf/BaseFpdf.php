<?php namespace DxsRavel\Essentials\Pdf;

use Anouar\Fpdf\Fpdf;

class BaseFpdf extends Fpdf {
	protected $lh; //Line Height
	protected $pln = 0.6; //percent line height
	public function SetFont($family, $style='', $size=0, $save = true){
		if($save) $this->lh = $size*$this->pln;
		return parent::SetFont($family,$style,$size);
	}	
	public function Font($family, $style='', $size=0,$save = true){ return $this->SetFont($family,$style,$size,$save); }
	public function FontFamily($family,$save = true){ return $this->SetFont($family,'',0,$ave); }
	public function FontStyle($style,$save = true){ return $this->SetFont($this->FontFamily,$style,0,$save); }
	public function FontSize($size,$save = true){ return $this->SetFont($this->FontFamily,'',$size,$save); }

	function titleCenter($txt){
		$this->SetFont('Arial','B',10);
		$this->Cell($this->w - $this->lMargin - $this->rMargin,8, utf8_decode($txt) ,0,0,'C');
		$this->Ln();
	}
	protected function FirstFooter(){

	}
	public function Footer(){
		if( $this->PageNo()==1 ){ $this->FirstFooter(); }
	}	
	
	function Code39($xpos, $ypos, $code, $baseline=0.5, $height=5){

		$wide = $baseline;
		$narrow = $baseline / 3 ;
		$gap = $narrow;
	
		$barChar['0'] = 'nnnwwnwnn';
		$barChar['1'] = 'wnnwnnnnw';
		$barChar['2'] = 'nnwwnnnnw';
		$barChar['3'] = 'wnwwnnnnn';
		$barChar['4'] = 'nnnwwnnnw';
		$barChar['5'] = 'wnnwwnnnn';
		$barChar['6'] = 'nnwwwnnnn';
		$barChar['7'] = 'nnnwnnwnw';
		$barChar['8'] = 'wnnwnnwnn';
		$barChar['9'] = 'nnwwnnwnn';
		$barChar['A'] = 'wnnnnwnnw';
		$barChar['B'] = 'nnwnnwnnw';
		$barChar['C'] = 'wnwnnwnnn';
		$barChar['D'] = 'nnnnwwnnw';
		$barChar['E'] = 'wnnnwwnnn';
		$barChar['F'] = 'nnwnwwnnn';
		$barChar['G'] = 'nnnnnwwnw';
		$barChar['H'] = 'wnnnnwwnn';
		$barChar['I'] = 'nnwnnwwnn';
		$barChar['J'] = 'nnnnwwwnn';
		$barChar['K'] = 'wnnnnnnww';
		$barChar['L'] = 'nnwnnnnww';
		$barChar['M'] = 'wnwnnnnwn';
		$barChar['N'] = 'nnnnwnnww';
		$barChar['O'] = 'wnnnwnnwn';
		$barChar['P'] = 'nnwnwnnwn';
		$barChar['Q'] = 'nnnnnnwww';
		$barChar['R'] = 'wnnnnnwwn';
		$barChar['S'] = 'nnwnnnwwn';
		$barChar['T'] = 'nnnnwnwwn';
		$barChar['U'] = 'wwnnnnnnw';
		$barChar['V'] = 'nwwnnnnnw';
		$barChar['W'] = 'wwwnnnnnn';
		$barChar['X'] = 'nwnnwnnnw';
		$barChar['Y'] = 'wwnnwnnnn';
		$barChar['Z'] = 'nwwnwnnnn';
		$barChar['-'] = 'nwnnnnwnw';
		$barChar['.'] = 'wwnnnnwnn';
		$barChar[' '] = 'nwwnnnwnn';
		$barChar['*'] = 'nwnnwnwnn';
		$barChar['$'] = 'nwnwnwnnn';
		$barChar['/'] = 'nwnwnnnwn';
		$barChar['+'] = 'nwnnnwnwn';
		$barChar['%'] = 'nnnwnwnwn';
	
		$this->SetFont('Arial','',10);
		$this->Text($xpos, $ypos + $height + 4, $code);
		$this->SetFillColor(0);
	
		$code = '*'.strtoupper($code).'*';
		for($i=0; $i<strlen($code); $i++){
			$char = $code[$i];
			if(!isset($barChar[$char])){
				$this->Error('Invalid character in barcode: '.$char);
			}
			$seq = $barChar[$char];
			for($bar=0; $bar<9; $bar++){
				$lineWidth = $wide;
				if($seq[$bar] == 'n')
					$lineWidth = $narrow;
				if($bar % 2 == 0){
					$this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
				}
				$xpos += $lineWidth;
			}
			$xpos += $gap;
		}
	}
	public function CellUTF8($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link=''){
		$this->Cell($w,$h,utf8_decode($txt),$border,$ln,$align,$fill,$link);
	}
	public function CellText($w,$txt,$border=0, $ln=0, $align='', $fill=false, $link=''){
		$this->Cell($w,$this->lh,utf8_decode($txt),$border,$ln,$align,$fill,$link);
	}
	public function CellTextB($w,$txt,$align = ''){
		$this->Cell($w,$this->lh,utf8_decode($txt),1,0,$align);	
	}

	public function TextUTF8($x,$y,$txt){
		$this->Text($x,$y,utf8_decode($txt));
	}
	public function TextCenter($y,$txt){
		$w = $this->w - $this->lMargin - $this->rMargin;
		$l = strlen($y);
		$x = ($w-$l)/2;
		$this->Text($x,$y,$txt);
	}
	public function hr(){
		$this->Cell($this->w - $this->lMargin - $this->rMargin,1, '' ,'T',1);
	}
	public function hr2(){
		$this->Cell($this->w - $this->lMargin - $this->rMargin,1, '' ,'TB',1);
	}
	public function diasArr(){
		return [
					'1'=>'LUNES'
					,'2'=>'MARTES'
					,'3'=>'MIERCOLES'
					,'4'=>'JUEVES'
					,'5'=>'VIERNES'
					,'6'=>'SABADO'
					,'7'=>'DOMINGO'
			   ];
	}
	public function cardinal($n){
		$n = (int) $n;
		if($n == 1 || $n == 3) return 'er';
		if($n == 2) return 'do';		
		if($n >= 4 && $n <= 6 ) return 'to';
		if($n == 7 || $n == 10) return 'mo';
		if($n == 8) return 'vo';
		if($n == 9) return 'no';
	}
	public function ordinal($n){
		$n = (int) $n;
		if($n == 1 || $n == 3) return 'ro';
		if($n == 2) return 'do';		
		if($n >= 4 && $n <= 6 ) return 'to';
		if($n == 7 || $n == 10) return 'mo';
		if($n == 8) return 'vo';
		if($n == 9) return 'no';
	}	
	function ordinalN($k){
		$arr = [
				1 => 'PRIMER',
				2 => 'SEGUNDO',
				3 => 'TERCER',
				4 => 'CUARTO',
				5 => 'QUINTO',
				6 => 'SEXTO',
				7 => 'SEPTIMO',
				8 => 'OCTAVO',
				9 => 'NOVENO',
				10 => 'DECIMO'
			   ];
		return $arr[$k];
	}
	public function mes($m){
		$meses = [
					'01'=>'Enero',
					'02'=>'Febrero',
					'03'=>'Marzo',
					'04'=>'Abril',
					'05'=>'Mayo',
					'06'=>'Junio',
					'07'=>'Julio',
					'08'=>'Agosto',
					'09'=>'Septiembre',
					'10'=>'Octubre',
					'11'=>'Noviembre',
					'12'=>'Diciembre'
				];
		return @$meses[$m];
	}
}	
?>