<?php
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDF.php';

class RConciliacionBancariaPDF extends  ReportePDF{
    var $datos ;
    var $ancho_hoja;
    var $numeracion;
    var $ancho_sin_totales;
    var $cantidad_columnas_estaticas;		   

    function Header(){
        $this->Ln(3);
		
        //cabecera del reporte
        $this->Image(dirname(__FILE__).'/../../lib/imagenes/logos/logo.jpg', 16,5,40,20);		
        $this->ln(3);		
        $this->SetMargins(15, 40, 5);        
        $this->SetFont('','B',11);		
        $this->Cell(215,5,"BOLIVIANA DE AVIACION",0,1,'C');
        $this->Cell(215,8,"CONCILIACIÓN BANCARIA",0,1,'C');
		foreach ($this->datos1 as $value) {													
				$nro_cuenta = $value['nro_cuenta'];
				$banco = $value['nombre_institucion'];
				$moneda = $value['moneda'];
				$denominacion = $value['denominacion'];			
		}
		foreach ($this->datos1 as $value) {			
				$periodo = $value['periodo'];
				$gestion = $value['gestion'];							
		}								
		$height = 2;
		$witdh  = 30;
		$this->Ln(5);
		$this->SetFont('','B',10);								
        $this->Cell($witdh, $height, 'Banco: ', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Cell(80, $height, $banco, 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Ln();
        $this->Cell($witdh, $height, 'Periodo o mes: ', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Cell(15, $height, $periodo.' de '.$gestion, 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Ln();
		$this->Cell($witdh, $height, 'Nro. Cta.Cte: ', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Cell($witdh, $height, $nro_cuenta, 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Cell(80, $height, 'Moneda: '.$moneda, 0, 0, 'R', false, '', 0, false, 'T', 'C');	
		$this->Ln();
		$this->Cell($witdh, $height, 'Denominación', 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Cell(80, $height, $denominacion, 0, 0, 'L', false, '', 0, false, 'T', 'C');
		$this->Ln();											
		
    }
    function setDatos($datos,$datos1) {

        $this->datos = $datos;
		$this->datos1 = $datos1;
        //var_dump($this->datos1);exit;
    }	
	function generarReporte () {
		
        $this->AddPage();        
        $this->SetMargins(12, 40, 12,true);        
		$this->Ln(8);
		$this->SetFont('','',9);
		$sum_debito = 0;
		$sum_abono = 0;
		$sum_cheque = 0;
		$sum_deposito = 0;
		$saldo_erp = 0;
		$total_sal_libro = 0;
		$n = 0;
		$n1 = 0;
		$n2 = 0;
		$n3 = 0;
		$cheque = 0;
		$deposito = 0;
		
	foreach ($this->datos as $value) {					
			//$n1 +=1;
			$sum_3ra_tabla += $value['saldo'];			
	}
	
	foreach ($this->datos1 as $value) {
		$value['tipo']=='cheque' && $cheque += $value['importe'];
		$value['tipo']=='deposito' && $deposito += $value['importe'];
		$value['tipo']=='transito' && $transito += $value['importe'];			
		$saldo_ext_banca = $value['sal_ext_ban'];
		$saldo_erp = $value['saldo'];
		$fecha_elaboracion = $value['fecha'];
		$observa = $value['observaciones'];
		$fun_elba = $value['fun_elab'];
		$fun_vb = $value['fun_vb'];			
	}



	$datos = array();
	$datos1 = array();
	switch (count($this->datos)) {
		case 1:$datos=$this->agree(4,$this->datos);break;
		case 2:$datos=$this->agree(3,$this->datos);break;
		case 3:$datos=$this->agree(2,$this->datos);break;
		case 4:$datos=$this->agree(1,$this->datos);break;
		default:$datos=$this->agree(5,null,null);break;
	}

	$datos1= $this->complete($this->datos1);

	foreach ($datos1 as $value) {		
		$value['tipo']=='cheque' && $n += 1; 
		$value['tipo']=='deposito' && $n3 +=1;
		$value['tipo']=='transito' && $n2 +=1;	
	}
	foreach ($datos as $value) {					
		$n1 +=1;				
	}
		
	$saldo_real1 = $saldo_ext_banca - $cheque + $deposito;
	$saldo_real2 = $saldo_erp - $sum_3ra_tabla + $transito;
					
	$con = 2 + $n;
	$con1 = 2 + $n1;
	$con2 = 1 + $n2;
	$con3 = 1 + $n3;
	
	$fecha_table = explode('-', $fecha_elaboracion);
	$dia = $fecha_table[2];
	$mes = $fecha_table[1];
	$anio = $fecha_table[0];
	//var_dump($datos1);exit;
	
		
$html = '<table border="1" cellpadding="2">
<tr>
<table border="1" cellpadding="2">
 <tr align="center">
  <th colspan="4" width="600">CONCEPTOS</th>
  <th width="80">IMPORTE</th>
 </tr>
 <tr>
  <td colspan="4" width="600" align="left"><b>1. SALDO SEGUN LIBROS</b><br />
  <b>Menos: Debitos registrados por el banco y no Contabilizados:</b></td>
  <td width="80" align="right" rowspan="'.$con.'"><b>'.number_format($saldo_ext_banca,2,',','.').'<br>'.number_format($cheque,2,',','.').'</b></td>
 </tr>  
 <tr align="center">
  <td width="80"><b>Fecha</b></td>
  <td width="340"><b>CONCEPTO</b></td>
  <td width="90"><b>Compbte.No.</b></td>
  <td width="90"><b>Importe</b></td>
 </tr>
 '; 
   foreach ($datos1 as $row){
	  	if ($row['tipo']=='cheque'){
	  		($row['importe']=='')?$importe=$row['importe']:$importe=number_format($row['importe'],2,',','.');	  		
	        $html.='<tr align="center" style="font-size:6;">
	        <td>'.$row['fecha_reg'].'</td>
	        <td align="left" >'.$row['concepto'].'</td>
	        <td>'.$row['nro_comprobante'].'</td>
	        <td align="right">'.$importe.'</td>
	        </tr>';
			$sum_debito += $row['importe'];		
			}
    	}    
 $html.='<tr align="center">
   <td colspan="3">TOTAL</td>
   <td align="right">'.number_format($sum_debito,2,',','.').'</td>
 </tr>
 <tr>
  <td colspan="4"><b>Más: Abonos registrados por el Banco y no Contabilizados:</b></td>
  <td align="right"><b>'.number_format($deposito,2,',','.').'</b></td>    
 </tr>
 <tr align="center">
  <td width="80"><b>Fecha</b></td>
  <td width="340"><b>CONCEPTO</b></td>
  <td width="90"><b>Compbte.No.</b></td>
  <td width="90"><b>Importe</b></td>
  <td width="80" align="right" rowspan="'.$con3.'"></td>
 </tr>';   
   foreach ($datos1 as $row){
	  	if ($row['tipo']=='deposito'){
	  		($row['importe']=='')?$importe=$row['importe']:$importe=number_format($row['importe'],2,',','.');
	        $html.='<tr align="center" style="font-size:6;">
	        <td>'.$row['fecha_reg'].'</td>
	        <td align="left" >'.$row['concepto'].'</td>
	        <td>'.$row['nro_comprobante'].'</td>
	        <td align="right">'.$importe.'</td>
	        </tr>';
			$sum_abono += $row['importe'];		
			}
	    }     
 $html.='<tr align="center">
   <td colspan="3">TOTAL</td>
   <td align="right">'.number_format($sum_abono,2,',','.').'</td>
 </tr>
 <tr align="center">
   <td colspan="4"><b>SALDO REAL</b></td>
   <td align="right"><b>'.number_format($saldo_real1,2,',','.').'</b></td>
 </tr> 
 <tr>
  <td colspan="4" width="600" align="left"><b>2. SALDO SEGUN EXTRACTO BANCARIO:</b><br />
  <b>Menos: Cheques Girados y no Cobrados(</b> y Otros conceptos no registrados por el Banco<b>):</b></td>
  <td width="80" align="right" rowspan="'.$con1.'"><b>'.number_format($saldo_erp,2,',','.').'<br>'.number_format($sum_3ra_tabla,2,',','.').'</b></td>
 </tr>  
 <tr nobr="true" align="center">
  <td width="80"><b>Fecha</b></td>
  <td width="340"><b>CONCEPTO</b></td>
  <td width="90"><b>Cheque No.</b></td>
  <td width="90"><b>Importe</b></td>
 </tr>';
   foreach ($datos as $row){
   	($row['saldo']=='')?$importe=$row['saldo']:$importe=number_format($row['saldo'],2,',','.');  	
        $html.='<tr align="center" style="font-size:6;">
        <td>'.$row['fecha'].'</td>
        <td align="left" >'.$row['concepto'].'</td>
        <td>'.$row['nro_cheque'].'</td>
        <td align="right">'.$importe.'</td>
        </tr>';
		$sum_cheque += $row['saldo'];		
    } 
 $html.='<tr align="center">
   <td colspan="3">TOTAL</td>
   <td align="right">'.number_format($sum_cheque,2,',','.').'</td>
 </tr>  
   <tr>
  <td colspan="4"><b>Más: Depósitos y otros conceptos no Registrados por el Banco:</b></td>
  <td align="right"><b>'.number_format($transito,2,',','.').'</b></td>  
 </tr>
 <tr align="center">
  <td width="80"><b>Fecha</b></td>
  <td width="340"><b>CONCEPTO</b></td>
  <td width="90"><b>Compbte.No.</b></td>
  <td width="90"><b>Importe</b></td>
  <td width="80" rowspan="'.$con2.'"></td>
 </tr>';
    foreach ($datos1 as $row){    	    
	  	if ($row['tipo']=='transito'){
	  		($row['importe']=='')?$importe=$row['importe']:$importe=number_format($row['importe'],2,',','.');
	        $html.='<tr align="center" style="font-size:6;">
	        <td>'.$row['fecha_reg'].'</td>
	        <td align="left" >'.$row['concepto'].'</td>
	        <td>'.$row['nro_comprobante'].'</td>
	        <td align="right">'.$importe.'</td>
	        </tr>';
			$sum_transito += $row['importe'];
			}
	    }     		    
 $html.=' 
 <tr align="center">
   <td colspan="3">TOTAL</td>
   <td align="right">'.number_format($sum_transito,2,',','.').'</td>
 </tr>
 <tr align="center">
   <td colspan="4"><b>SALDO REAL</b></td>
   <td align="right"><b>'.number_format($saldo_real2,2,',','.').'</b></td>
 </tr>
  <tr>
    <td colspan="1" width="150" ><br/><br/>
      <table border="1" style="border-right:none;">
       <tr align="center">
        <th colspan="3"><b>Fecha de Elaboración</b></th>         
       </tr>        
        <tr align="center">
          <td><b>DIA</b></td>
          <td><b>MES</b></td>
          <td><b>AÑO</b></td>
        </tr>
        <tr align="center">
          <td>'.$dia.'</td>
          <td>'.$mes.'</td>
          <td>'.$anio.'</td>
        </tr>        
      </table><br/>
    </td>
    <td colspan="1" width="270"><b>Elaborado por:</b> <br>'.$fun_elba.'</td>
    <td colspan="2"  width="260"><b>Vo.Bo: </b> <br>'. $fun_vb.'</td>
  </tr>
  <tr>
    <td colspan="4"><b>OBSERVACIONES: </b>'. $observa.'</td>
  </tr>
</table>
</tr>
</table>
';
$this->writeHTML($html,true, false, false, false, '');
	$diff = round($saldo_real1,2) - round($saldo_real2,2); 
	if($diff<0 || $diff>0){
		$this->SetFillColor(224, 235, 100);
		$this->SetTextColor(200,0,0);
		$this->SetFont('','B',12);
		$this->Cell(20, 5, 'DIFERENCIA DE SALDOS REALES: '.number_format($diff,2,',','.'), 0, 0, 'L', false, '', 0, false, 'T', 'C');
	 }
    }
    public function Footer() {
        $this->SetFontSize(5);
    			$this->setY(-10);
    			$ormargins = $this->getOriginalMargins();
    			$this->SetTextColor(0, 0, 0);
    			//set style for cell border
    			$line_width = 0.85 / $this->getScaleFactor();
    			$this->SetLineStyle(array('width' => $line_width, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
    			$ancho = round(($this->getPageWidth() - $ormargins['left'] - $ormargins['right']) / 3);
    			$this->Ln(2);
    			$cur_y = $this->GetY();    			
    			$this->Cell($ancho, 0, 'Usuario: '.$_SESSION['_LOGIN'], '', 0, 'L');
    			$pagenumtxt = 'Página'.' '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
    			$this->Cell($ancho, 0, $pagenumtxt, '', 0, 'C');
    			$this->Ln($line_width);
	}

	function complete($rows){
		
		$tab0 = array();
		$tab1 = array();
		$tab2 = array();				
					
		$datos1 = array();
		
		$table1 = array();	
		$table2 = array();
		$table3 = array();
		$t0 = 0;
		$t1 = 0;		
		$t2 = 0;
		foreach ($this->datos1 as $value) {
			$value['tipo']=='cheque' && $t0 +=1;
			$value['tipo']=='deposito' && $t1 +=1;
			$value['tipo']=='transito' && $t2 +=1;
		}				
		$cantidad = count($rows);
		
		for ($i=0; $i < $cantidad ; $i++) { 
			$rows[$i]['tipo']=='cheque' && array_push($tab0,$rows[$i]);
			$rows[$i]['tipo']=='deposito' && array_push($tab1,$rows[$i]);
			$rows[$i]['tipo']=='transito' && array_push($tab2,$rows[$i]);
		}
		switch ($t0) {
			case 1:$table1=$this->agree(4,$tab0,'t1');break;
			case 2:$table1=$this->agree(3,$tab0,'t1');break;
			case 3:$table1=$this->agree(2,$tab0,'t1');break;
			case 4:$table1=$this->agree(1,$tab0,'t1');break;
			default:$table1=$this->agree(5,null,'t1');break;									
		}
		switch ($t1) {
			case 1:$table2=$this->agree(4,$tab1,'t2');break;
			case 2:$table2=$this->agree(3,$tab1,'t2');break;
			case 3:$table2=$this->agree(2,$tab1,'t2');break;
			case 4:$table2=$this->agree(1,$tab1,'t2');break;
			default:$table2=$this->agree(5,null,'t2');break;		
		}
		switch ($t2) {
			case 1:$table3=$this->agree(4,$tab2,'t3');break;
			case 2:$table3=$this->agree(3,$tab2,'t3');break;
			case 3:$table3=$this->agree(2,$tab2,'t3');break;
			case 4:$table3=$this->agree(1,$tab2,'t3');break;
			default:$table3=$this->agree(5,null,'t3');break;		
		}		
		$datos1 = array_merge($table1,$table2,$table3);			
		return $datos1;
				
	}	
	function agree($n,$array=null,$table=null){
	
	 $array == null && $array=array();
	  				
	 if($table==null){				
		for ($i=0; $i <$n ; $i++) { 
			array_push($array,array("nombre_institucion"=>"",
			"nro_cuenta"=>"",
			"concepto"=>"",
			"fecha"=>"",
			"saldo"=>"",
			"moneda"=>"",			
			"denominacion"=>"",
			"nro_cheque"=>"")
			);				
		}			
		return $array;
	 }elseif($table=='t1'){	 	
		for ($i=0; $i <$n ; $i++) { 
			array_push($array,array(
	  'nombre_institucion' => '',
      'fecha' => '',
      'saldo' => '',
      'observaciones' => '',
      'fun_elab' => '',
      'fun_vb' => '',
      'moneda' => '',
      'fecha_reg' => '',
      'concepto' => '',
      'importe' => '',
      'nro_comprobante' => '',
      'tipo' => 'cheque',
      'sal_ext_ban' => '',
      'periodo' => '',
      'gestion' => '',
      'nro_cuenta' => '',
      'denominacion' => ''
      )); 				
		}			
		return $array;	 	
	 }elseif($table=='t2'){	 	
		for ($i=0; $i <$n ; $i++) { 
			array_push($array,array(
	  'nombre_institucion' => '',
      'fecha' => '',
      'saldo' => '',
      'observaciones' => '',
      'fun_elab' => '',
      'fun_vb' => '',
      'moneda' => '',
      'fecha_reg' => '',
      'concepto' => '',
      'importe' => '',
      'nro_comprobante' => '',
      'tipo' => 'deposito',
      'sal_ext_ban' => '',
      'periodo' => '',
      'gestion' => '',
      'nro_cuenta' => '',
      'denominacion' => ''
      )); 				
		}			
		return $array;
	 }elseif($table=='t3'){
		for ($i=0; $i <$n ; $i++) { 
			array_push($array,array(
	  'nombre_institucion' => '',
      'fecha' => '',
      'saldo' => '',
      'observaciones' => '',
      'fun_elab' => '',
      'fun_vb' => '',
      'moneda' => '',
      'fecha_reg' => '',
      'concepto' => '',
      'importe' => '',
      'nro_comprobante' => '',
      'tipo' => 'transito',
      'sal_ext_ban' => '',
      'periodo' => '',
      'gestion' => '',
      'nro_cuenta' => '',
      'denominacion' => ''
      ));			
		}			
		return $array;
	 }
 }
		
}
?>