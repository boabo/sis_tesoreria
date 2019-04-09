<?php
class RConciliacionBancariaXLS
{
    private $docexcel;
    private $objWriter;
    private $equivalencias=array();
    private $monto=array();
    private $montoDebito=array();
    private $objParam;
    public  $url_archivo;
    public  $fill = 0;
    public  $filles = 0;
    public  $garante = 0;
    public  $pika = 0;


    function __construct(CTParametro $objParam){
        $this->objParam = $objParam;
        $this->url_archivo = "../../../reportes_generados/".$this->objParam->getParametro('nombre_archivo');
        set_time_limit(400);
        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize'  => '10MB');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $this->docexcel = new PHPExcel();
        $this->docexcel->getProperties()->setCreator("PXP")
            ->setLastModifiedBy("PXP")
            ->setTitle($this->objParam->getParametro('titulo_archivo'))
            ->setSubject($this->objParam->getParametro('titulo_archivo'))
            ->setDescription('Reporte "'.$this->objParam->getParametro('titulo_archivo').'", generado por el framework PXP')
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Report File");

        $this->equivalencias=array( 0=>'A',1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',
            9=>'J',10=>'K',11=>'L',12=>'M',13=>'N',14=>'O',15=>'P',16=>'Q',17=>'R',
            18=>'S',19=>'T',20=>'U',21=>'V',22=>'W',23=>'X',24=>'Y',25=>'Z',
            26=>'AA',27=>'AB',28=>'AC',29=>'AD',30=>'AE',31=>'AF',32=>'AG',33=>'AH',
            34=>'AI',35=>'AJ',36=>'AK',37=>'AL',38=>'AM',39=>'AN',40=>'AO',41=>'AP',
            42=>'AQ',43=>'AR',44=>'AS',45=>'AT',46=>'AU',47=>'AV',48=>'AW',49=>'AX',
            50=>'AY',51=>'AZ',
            52=>'BA',53=>'BB',54=>'BC',55=>'BD',56=>'BE',57=>'BF',58=>'BG',59=>'BH',
            60=>'BI',61=>'BJ',62=>'BK',63=>'BL',64=>'BM',65=>'BN',66=>'BO',67=>'BP',
            68=>'BQ',69=>'BR',70=>'BS',71=>'BT',72=>'BU',73=>'BV',74=>'BW',75=>'BX',
            76=>'BY',77=>'BZ');

    }

    function imprimeCabecera() {
        $this->docexcel->createSheet();
        $this->docexcel->getActiveSheet()->setTitle('Conciliacion Bancaria');
        $this->docexcel->setActiveSheetIndex(0);


        $gdImage = imagecreatefromjpeg('../../../sis_kactivos_fijos/reportes/LogoBoa.jpg');
        // Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
        $objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
        $objDrawing->setName('Sample image');
        $objDrawing->setDescription('Sample image');
        $objDrawing->setImageResource($gdImage);
        $objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
        $objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
        $objDrawing->setHeight(60);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWorksheet($this->docexcel->getActiveSheet());
        $this->docexcel->getActiveSheet()->mergeCells('A3:B3');
		        

        $this->docexcel->setActiveSheetIndex(0);
        $sheet0 = $this->docexcel->getActiveSheet();

        $sheet0->setTitle('CONCILIACION BANCARIA');


        $sheet0->getColumnDimension('B')->setWidth(7);
        $sheet0->getColumnDimension('C')->setWidth(20);
        $sheet0->getColumnDimension('D')->setWidth(25);
        $sheet0->getColumnDimension('E')->setWidth(10);
        $sheet0->getColumnDimension('F')->setWidth(10);
        $sheet0->getColumnDimension('G')->setWidth(10);
        $sheet0->getColumnDimension('H')->setWidth(10);
        $sheet0->getColumnDimension('I')->setWidth(10);
        $sheet0->getColumnDimension('J')->setWidth(10);
        $sheet0->getColumnDimension('K')->setWidth(15);
        $sheet0->getColumnDimension('L')->setWidth(15);
        $sheet0->getColumnDimension('L')->setWidth(12);
        $sheet0->getColumnDimension('M')->setWidth(10);
        $sheet0->getColumnDimension('N')->setWidth(10);
        $sheet0->getColumnDimension('O')->setWidth(10);
        $sheet0->getColumnDimension('P')->setWidth(10);

        
        $title = "CONCILIACIÓN BANCARIA";        


        $styleTitulos = array(
            'font' => array(
                'bold' => true,
                'size' => 8,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'D8D8D8'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ));        

        $styleCabeza = array(
            'font' => array(
                'bold' => true,
                'size' => 8,
                'name' => 'Times New Roman'
            ),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'D8D8D8'
                )
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $styleBoa1 = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array(
                    'rgb' => 'c5d9f1'
                )

            ),
            'font'  => array(
                'bold'  => true,
                'size'  => 14,
                'name'  => 'Times New Roman'


            )
        );
        $styleTable = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'font'  => array(                
                'size'  => 9,
                'name'  => 'Arial'


            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),            
        );
        $styleTableSubti = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'font'  => array(
            	'bold' => true,                 
                'size'  => 10,
                'name'  => 'Arial'


            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),            
        );		
		
        $styleSaldos = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),        
            'font'  => array(
                'bold'  => true,
                'size'  => 11,
                'name'  => 'Times New Roman'
            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )                        
        );
        $styleTitles = array(
            'font'  => array(
                'bold'  => true,
                'size'  => 10,
                'name'  => 'Times New Roman'
            )            
        );
		$styleObservacion = array(		
			'font' => array(
				'bold' => true,
				'size' => 10,
				'name' => 'Arial'
			),
            'borders' => array(
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )                
            ),			
		);
		$styleBold = array(		
            'borders' => array(
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                )                
            ),			
		);		
		$styleImporte = array(
			'font' => array(
				'bold' => true,
				'size' => 10,
				'name' => 'Times New Roman'
			),
            'borders' => array(
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
            ),			
		);
        $styleCbte = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'font'  => array(                
                'size'  => 9,
                'name'  => 'Arial'


            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),            
        );
        $styleImpo = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'font'  => array(                
                'size'  => 10,
                'name'  => 'Arial'


            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),            
        );				
		$styleConceptoTitle = array(
			'font' => array(
				'bold' => true,
				'size' => 10,
				'name' => 'Arial'
			),
            'borders' => array(
                'left' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'right' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'bottom' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                ),
            ),			
		);	
        $styleConcepto = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
            ),
            'font'  => array(                
                'size'  => 10,
                'name'  => 'Arial'


            ),
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),            
        );									
				


        $sheet0->getStyle('A11:G11')->applyFromArray($styleCabeza);
        $sheet0->getRowDimension('6')->setRowHeight(35);
        $sheet0->getStyle('A11:G11')->applyFromArray($styleTitulos);
        $sheet0->getStyle('A11:G11')->getAlignment()->setWrapText(true);
		$this->docexcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);		

        //*************************************Cabecera*****************************************

        $sheet0->setCellValue('A4', 'BUENOS AIRES');
		$sheet0->getStyle('A4')->getFont()->applyFromArray(array('size'=>8,'name' => 'Garamond'));
		$sheet0->setCellValue('A6', 'CONCILIACIÓN BANCARIA');
		$this->docexcel->getActiveSheet()->mergeCells('A6:G6');
        $this->docexcel->getActiveSheet()->getStyle('A6')->applyFromArray($styleBoa1);
        $this->docexcel->getActiveSheet()->getStyle('A6')->getAlignment()->setWrapText(true);		

        $sheet0->setCellValue('A7', 'Banco: ');
		$sheet0->getStyle('A7')->getFont()->applyFromArray(array('bold'=>true,'size'=>10,'name' => 'Arial','underline'=> true,));		
		$sheet0->setCellValue('A8', 'Periodo o mes: ');
		$sheet0->getStyle('A8')->getFont()->applyFromArray(array('bold'=>true,'size'=>10, 'name' => 'Arial'));        
        $sheet0->setCellValue('A9', 'No. Cta.Cte');
		$sheet0->getStyle('A9')->getFont()->applyFromArray(array('bold'=>true,'size'=>10, 'name' => 'Arial'));
        $sheet0->setCellValue('E9', 'Moneda: ');
		$sheet0->getStyle('E9')->getFont()->applyFromArray(array('bold'=>true,'size'=>10, 'name' => 'Arial'));		
		

        $sheet0->setCellValue('A11', 'CONCEPTOS');		
        $this->docexcel->getActiveSheet()->getStyle('A11')->applyFromArray($styleConceptoTitle);
		$this->docexcel->getActiveSheet()->getStyle('A11')->getAlignment()->setWrapText(true);
		$this->docexcel->getActiveSheet()->mergeCells('A11:F11');				
		$sheet0->setCellValue('G11', 'IMPORTE');
		$sheet0->getRowDimension('6')->setRowHeight(35);
        $this->docexcel->getActiveSheet()->getStyle('G11:G42')->applyFromArray($styleImporte);
		$this->docexcel->getActiveSheet()->getStyle('G11:G42')->getAlignment()->setWrapText(true);		
		
		
		
        $sheet0->setCellValue('A12', '1. SALDO SEGÚN LIBROS');
		$this->docexcel->getActiveSheet()->mergeCells('A12:C12');
        $this->docexcel->getActiveSheet()->getStyle('A12')->applyFromArray($styleTitles);        
        $sheet0->setCellValue('A13', 'Memos: ');
        $this->docexcel->getActiveSheet()->getStyle('A13')->applyFromArray($styleTableSubti);        	
		$sheet0->setCellValue('B13', 'Débitos registrados por el Banco y no Contabilizados: ');        	
		$this->docexcel->getActiveSheet()->mergeCells('B13:F13');
		$this->docexcel->getActiveSheet()->getStyle('B13:F13')->applyFromArray($styleTableSubti);
		$this->docexcel->getActiveSheet()->getStyle('A13:F13')->getAlignment()->setWrapText(true);					
        $sheet0->setCellValue('A14', 'Fecha');
		$this->docexcel->getActiveSheet()->getStyle('A14')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('A14')->getAlignment()->setWrapText(true);				
		$sheet0->setCellValue('B14', 'CONCEPTO');			
		$this->docexcel->getActiveSheet()->getStyle('B14:D14')->applyFromArray($styleConcepto);
		$this->docexcel->getActiveSheet()->getStyle('B14:D14')->getAlignment()->setWrapText(true);
		$this->docexcel->getActiveSheet()->mergeCells('B14:D14');			
		$sheet0->setCellValue('E14', 'Compbte.No.');		
		$this->docexcel->getActiveSheet()->getStyle('E14')->applyFromArray($styleCbte);
		$this->docexcel->getActiveSheet()->getStyle('E14')->getAlignment()->setWrapText(true);
		//$this->docexcel->getActiveSheet()->getColumnDimension('E14')->setAutoSize(true);								
		$sheet0->setCellValue('F14', 'Importe');		
		$this->docexcel->getActiveSheet()->getStyle('F14')->applyFromArray($styleImpo);
		$this->docexcel->getActiveSheet()->getStyle('F14')->getAlignment()->setWrapText(true);		
		$sheet0->setCellValue('A19', 'TOTAL');
		$this->docexcel->getActiveSheet()->mergeCells('A19:E19');
		$this->docexcel->getActiveSheet()->getStyle('A19:E19')->applyFromArray($styleTableSubti);
		$this->docexcel->getActiveSheet()->getStyle('A19:E19')->getAlignment()->setWrapText(true);
		$sheet0->setCellValue('F19', '0,00');
		$this->docexcel->getActiveSheet()->getStyle('F19')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('F19')->getAlignment()->setWrapText(true);	
		$sheet0->setCellValue('A27', 'SALDO REAL');
		$this->docexcel->getActiveSheet()->mergeCells('A27:F27');
		$this->docexcel->getActiveSheet()->getStyle('A27:F27')->applyFromArray($styleSaldos);
		$this->docexcel->getActiveSheet()->getStyle('A27:F27')->getAlignment()->setWrapText(true);		
		$sheet0->setCellValue('G27', '0,00');
		$this->docexcel->getActiveSheet()->getStyle('G27')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('G27')->getAlignment()->setWrapText(true);					
		
		

        $sheet0->setCellValue('A20', 'Más: ');
		$this->docexcel->getActiveSheet()->getStyle('A20')->applyFromArray($styleTableSubti);
		$this->docexcel->getActiveSheet()->getStyle('A20')->getAlignment()->setWrapText(true);						
		$sheet0->setCellValue('B20','Abonos registrados por el Banco y no Contabilizados: ');
		$this->docexcel->getActiveSheet()->mergeCells('B20:F20');
		$this->docexcel->getActiveSheet()->getStyle('B20:F20')->applyFromArray($styleTableSubti);
		$this->docexcel->getActiveSheet()->getStyle('B20:F20')->getAlignment()->setWrapText(true);						
        $sheet0->setCellValue('A21', 'Fecha');
		$this->docexcel->getActiveSheet()->getStyle('A21')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('A21')->getAlignment()->setWrapText(true);						
		$sheet0->setCellValue('B21', 'CONCEPTO');
		$this->docexcel->getActiveSheet()->mergeCells('B21:D21');
		$this->docexcel->getActiveSheet()->getStyle('B21:D21')->applyFromArray($styleConcepto);
		$this->docexcel->getActiveSheet()->getStyle('B21:D21')->getAlignment()->setWrapText(true);				
		$sheet0->setCellValue('E21', 'Compbte.No.');
		$this->docexcel->getActiveSheet()->getStyle('E21')->applyFromArray($styleCbte);
		$this->docexcel->getActiveSheet()->getStyle('E21')->getAlignment()->setWrapText(true);		
		$sheet0->setCellValue('F21', 'Importe');
		$this->docexcel->getActiveSheet()->getStyle('F21')->applyFromArray($styleImpo);
		$this->docexcel->getActiveSheet()->getStyle('F21')->getAlignment()->setWrapText(true);		
		$sheet0->setCellValue('A26', 'TOTAL');		
		$this->docexcel->getActiveSheet()->mergeCells('A26:E26');
		$this->docexcel->getActiveSheet()->getStyle('A26:E26')->applyFromArray($styleTableSubti);
		$this->docexcel->getActiveSheet()->getStyle('A26:E26')->getAlignment()->setWrapText(true);
		$sheet0->setCellValue('F26', '0,00');
		$this->docexcel->getActiveSheet()->getStyle('F26')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('F26')->getAlignment()->setWrapText(true);			


        $sheet0->setCellValue('A28', '2. SALDO SEGÚN EXTRACTO BANCARIO');
		$this->docexcel->getActiveSheet()->mergeCells('A28:C28');
        $this->docexcel->getActiveSheet()->getStyle('A28')->applyFromArray($styleTitles);		
        $sheet0->setCellValue('A29', 'Memos: ');
		$this->docexcel->getActiveSheet()->getStyle('A29')->applyFromArray($styleTableSubti);		
		$sheet0->setCellValue('B29', 'Cheques Girados y no Cobrados (y Otros conceptos no registrados por el Banco): ');
		$this->docexcel->getActiveSheet()->mergeCells('B29:F29');
		$this->docexcel->getActiveSheet()->getStyle('B29:F29')->applyFromArray($styleTableSubti);
		$this->docexcel->getActiveSheet()->getStyle('B29:F29')->getAlignment()->setWrapText(true);		
				
        $sheet0->setCellValue('A30', 'Fecha');
		$this->docexcel->getActiveSheet()->getStyle('A30')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('A30')->getAlignment()->setWrapText(true);						
		$sheet0->setCellValue('B30', 'CONCEPTO');
		$this->docexcel->getActiveSheet()->mergeCells('B30:D30');
		$this->docexcel->getActiveSheet()->getStyle('B30:D30')->applyFromArray($styleConcepto);
		$this->docexcel->getActiveSheet()->getStyle('B30:D20')->getAlignment()->setWrapText(true);		
		$sheet0->setCellValue('E30', 'Cheque.No.');
		$this->docexcel->getActiveSheet()->getStyle('E30')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('E30')->getAlignment()->setWrapText(true);		
		$sheet0->setCellValue('F30', 'Importe');
		$this->docexcel->getActiveSheet()->getStyle('F30')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('F30')->getAlignment()->setWrapText(true);		
		
		$sheet0->setCellValue('A33', 'TOTAL');
		$this->docexcel->getActiveSheet()->mergeCells('A33:E33');
		$this->docexcel->getActiveSheet()->getStyle('A33:E33')->applyFromArray($styleTableSubti);
		$this->docexcel->getActiveSheet()->getStyle('A33:E33')->getAlignment()->setWrapText(true);
		$sheet0->setCellValue('F33', '0,00');
		$this->docexcel->getActiveSheet()->getStyle('F33')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('F33')->getAlignment()->setWrapText(true);			
				
        $sheet0->setCellValue('A35', 'Más: ');
		$this->docexcel->getActiveSheet()->getStyle('A35')->applyFromArray($styleTableSubti);
		$this->docexcel->getActiveSheet()->getStyle('A35')->getAlignment()->setWrapText(true);		
		$sheet0->setCellValue('B35','Depósitos y otros conceptos no Registrados por el Banco: ');
		$this->docexcel->getActiveSheet()->mergeCells('B35:F35');
		$this->docexcel->getActiveSheet()->getStyle('B35:F35')->applyFromArray($styleTableSubti);
		$this->docexcel->getActiveSheet()->getStyle('B35:F35')->getAlignment()->setWrapText(true);
				
        $sheet0->setCellValue('A36', 'Fecha');
		$this->docexcel->getActiveSheet()->getStyle('A36')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('A36')->getAlignment()->setWrapText(true);						
		$sheet0->setCellValue('B36', 'CONCEPTO');
		$this->docexcel->getActiveSheet()->mergeCells('B36:D36');
		$this->docexcel->getActiveSheet()->getStyle('B36:D36')->applyFromArray($styleConcepto);
		$this->docexcel->getActiveSheet()->getStyle('B36:D36')->getAlignment()->setWrapText(true);					
		$sheet0->setCellValue('E36', 'Compbte.No.');
		$this->docexcel->getActiveSheet()->getStyle('E36')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('E36')->getAlignment()->setWrapText(true);		
		$sheet0->setCellValue('F36', 'Importe');
		$this->docexcel->getActiveSheet()->getStyle('F36')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('F36')->getAlignment()->setWrapText(true);
				
		$sheet0->setCellValue('A41', 'TOTAL');
		$this->docexcel->getActiveSheet()->mergeCells('A41:E41');
		$this->docexcel->getActiveSheet()->getStyle('A41:E41')->applyFromArray($styleTableSubti);
		$this->docexcel->getActiveSheet()->getStyle('A41:E41')->getAlignment()->setWrapText(true);
		$sheet0->setCellValue('F41', '0,00');
		$this->docexcel->getActiveSheet()->getStyle('F41')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('F41')->getAlignment()->setWrapText(true);
				
		$sheet0->setCellValue('A43', '');
		$this->docexcel->getActiveSheet()->mergeCells('A43:D43');
		$this->docexcel->getActiveSheet()->getStyle('A43:D43')->applyFromArray($styleBold);
		$this->docexcel->getActiveSheet()->getStyle('A43:F43')->getAlignment()->setWrapText(true);				
		$sheet0->setCellValue('A42', 'SALDO REAL');
		$this->docexcel->getActiveSheet()->mergeCells('A42:F42');
		$this->docexcel->getActiveSheet()->getStyle('A42:F42')->applyFromArray($styleSaldos);
		$this->docexcel->getActiveSheet()->getStyle('A42:F42')->getAlignment()->setWrapText(true);
		$sheet0->setCellValue('G42', '0,00');
		$this->docexcel->getActiveSheet()->getStyle('G42')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('G42')->getAlignment()->setWrapText(true);

		$sheet0->setCellValue('A43', '');
		$this->docexcel->getActiveSheet()->mergeCells('A42:F42');
		$this->docexcel->getActiveSheet()->getStyle('A42:F42')->applyFromArray($styleSaldos);
		$this->docexcel->getActiveSheet()->getStyle('A42:F42')->getAlignment()->setWrapText(true);
				
		$sheet0->setCellValue('A44', 'Fecha de Elaboracion');
		$this->docexcel->getActiveSheet()->mergeCells('A44:C44');
		$this->docexcel->getActiveSheet()->getStyle('A44:C44')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('A44:C44')->getAlignment()->setWrapText(true);
				
		$sheet0->setCellValue('D44', 'Elaborado por: ');
		$this->docexcel->getActiveSheet()->mergeCells('D44:D44');	
		$this->docexcel->getActiveSheet()->getStyle('D44:D47')->applyFromArray($styleObservacion);
		$this->docexcel->getActiveSheet()->getStyle('D44:D47')->getAlignment()->setWrapText(true);
				
		$sheet0->setCellValue('E44', 'Vo.Bo.');
		$this->docexcel->getActiveSheet()->mergeCells('E44:G44');	
		$this->docexcel->getActiveSheet()->getStyle('A43:G47')->applyFromArray($styleObservacion);
		$this->docexcel->getActiveSheet()->getStyle('A43:G47')->getAlignment()->setWrapText(true);		
		
		$sheet0->setCellValue('A45', 'DIA');
		$this->docexcel->getActiveSheet()->getStyle('A45')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('A45')->getAlignment()->setWrapText(true);
		$sheet0->setCellValue('A46', '');
		$this->docexcel->getActiveSheet()->getStyle('A46')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('A46')->getAlignment()->setWrapText(true);				
		$sheet0->setCellValue('B45', 'MES');
		$this->docexcel->getActiveSheet()->getStyle('B45')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('B45')->getAlignment()->setWrapText(true);
		$sheet0->setCellValue('B46', '');
		$this->docexcel->getActiveSheet()->getStyle('B46')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('B46')->getAlignment()->setWrapText(true);				
		$sheet0->setCellValue('C45', 'AÑO');
		$this->docexcel->getActiveSheet()->getStyle('C45')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('C45')->getAlignment()->setWrapText(true);
		$sheet0->setCellValue('C46', '');
		$this->docexcel->getActiveSheet()->getStyle('C46')->applyFromArray($styleTable);
		$this->docexcel->getActiveSheet()->getStyle('C46')->getAlignment()->setWrapText(true);				
						
		$sheet0->setCellValue('A48', 'OBSERVACIONES');	
		$this->docexcel->getActiveSheet()->mergeCells('A48:G48');	
		$this->docexcel->getActiveSheet()->getStyle('A48:G51')->applyFromArray($styleImporte);
		$this->docexcel->getActiveSheet()->getStyle('A48:G51')->getAlignment()->setWrapText(true);		        		

    }

    function generarDatos(){
        $this->imprimeCabecera();


  }


    function generarReporte(){
        $this->generarDatos();
        $this->docexcel->setActiveSheetIndex(0);
        $this->objWriter = PHPExcel_IOFactory::createWriter($this->docexcel, 'Excel5');
        $this->objWriter->save($this->url_archivo);
    }

}
?>
