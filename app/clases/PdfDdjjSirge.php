<?php

class PdfDdjjSirge extends Pdf {
	
	private
		$_sistema = 'SIRGe Web ' ,
		$_texto ,
		$_prestaciones ,
		$_comprobantes ,
		$_fondos ,
		$_contador = array (
			'insertados' 	=> 0 ,
			'rechazados' 	=> 0 ,			
			'modificados'	=> 0 ,
			'total'			=> 0
		) ,
		$_lotes = array() ,
		$_id_impresion ,
		$_data_sirge,
		$_ddjj,
		$_db;
	
	public function __construct(){
		parent::FPDF();
		$this->_db = Bdd::getInstance();
		$this->_ddjj = new Ddjj();
	}
	
	protected function encabezado () {
		
		$this->_texto 			= "Por medio de la presente elevo a Ud. en carácter de Declaración Jurada, los resultados del proceso de validación de la información en el ";
		$this->_prestaciones 	= "correspondiente a las PRESTACIONES aprobadas desde la última presentación hasta el día de la fecha, detalladas en el siguiente cuadro:";
		$this->_comprobantes 	= "correspondiente a los COMPROBANTES recibidos desde la última presentación hasta el día de la fecha, detallados en el siguiente cuadro:";
		$this->_fondos		  	= "correspondiente a las APLICACIONES DE FONDOS reportadas por los efectores desde la última presentación hasta el día de la fecha, detalladas en el siguiente cuadro:";
        $this->_osp          	= "correspondiente a la OBRA SOCIAL PROVINCIAL, detallados en el siguiente cuadro:";
        $this->_sss          	= "correspondiente a la SUPERINTENDENCIA DE SERVICIOS DE SALUD, detallados en el siguiente cuadro:";
        $this->_profe          	= "correspondiente al PROGRAMA FEDERAL DE SALUD, detallados en el siguiente cuadro:";
		
		$this->Cell(130);
		$this->Cell(25,8,utf8_decode(Sirge::getNombreProvincia($this->_data_sirge[0]['id_provincia'])) . ", " . $this->_data_sirge[0]['fecha_impresion'],0,0,'R');
		$this->Ln();
		$this->Cell(0,5,utf8_decode ("SEÑOR"));
		$this->Ln();
		$this->Cell(0,5,utf8_decode ("COORDINADOR DEL ÁREA SISTEMAS INFORMÁTICOS"));
		$this->Ln();
		$this->Cell(0,5,utf8_decode ("LIC. JAVIER E. MINSKY"));
		$this->Ln();
		$this->SetFont('Arial','BU',11);
		$this->Cell(0,5,"S           /           D",0,0,'D');
		$this->SetFont('Arial','',11);
		$this->Ln(12);
		$this->Cell(0,8,utf8_decode("De mi mayor consideración:"));
		$this->Ln();
		$this->Cell(50);
		$this->SetFont('Arial','',11);
		$this->Write(8,utf8_decode($this->_texto));
		$this->SetFont('Arial','B',11);
		$this->Write(8,$this->_sistema);
		$this->SetFont('Arial','',11);
		
		switch ($this->_data_sirge[0]['id_padron']) {
			case 1:
				$this->Write(8,utf8_decode($this->_prestaciones));
				$this->SetFont('Arial','BU',11);
				$this->Ln(10);
				$this->Cell(0,8,utf8_decode("INFORMACIÓN DE PRESTACIONES:"));
				break;
			case 2:
				$this->Write(8,utf8_decode($this->_fondos));
				$this->SetFont('Arial','BU',11);
				$this->Ln(10);
				$this->Cell(0,8,utf8_decode("INFORMACIÓN DE APLICACION DE FONDOS:"));
				break;
			case 3:
				$this->Write(8,utf8_decode($this->_comprobantes));
				$this->SetFont('Arial','BU',11);
				$this->Ln(10);
				$this->Cell(0,8,utf8_decode("INFORMACIÓN DE COMPROBANTES:"));
				break;
            case 4:
				$this->Write(8,utf8_decode($this->_sss));
				$this->SetFont('Arial','BU',11);
				$this->Ln(10);
				$this->Cell(0,8,utf8_decode("INFORMACIÓN DE SUPER INTENDENCIA DE SERVICIOS DE SALUD:"));
				break;
            case 5:
				$this->Write(8,utf8_decode($this->_profe));
				$this->SetFont('Arial','BU',11);
				$this->Ln(10);
				$this->Cell(0,8,utf8_decode("INFORMACIÓN DE PROGRAMA FEDERAL DE SALUD:"));
				break;
            case 6:
				$this->Write(8,utf8_decode($this->_osp));
				$this->SetFont('Arial','BU',11);
				$this->Ln(10);
				$this->Cell(0,8,utf8_decode("INFORMACIÓN DE OBRA SOCIAL PROVINCIAL:"));
				break;
			default: die("ERROR");
		}
		$this->Ln(10);
	}
	
	protected function getDataImpresion ($id_impresion) {
		$params = array ($id_impresion);
		$sql = "
			select 
				l.lote
				, fecha_impresion :: date
				, d.id_provincia
				, id_padron
				, registros_in
				, registros_out
				, registros_mod
			from 
				ddjj.sirge d left join
				sistema.lotes l on l.lote = any (d.lote) left join
				sistema.subidas s on l.id_subida = s.id_subida
			where 
				id_impresion = ?";
		$this->_data_sirge = $this->_db->query($sql , $params)->getResults();
	}
	
	protected function getIdImpresion ($id_padron , $id_impresion = null) {
		
		if (! is_null ($id_impresion)) {
			
			$params = array ($id_padron , $_SESSION['grupo']);
			$sql 	= "
				select 
					lote 
					, inicio :: date as fecha
					, registros_in as insertados 
					, registros_out as rechazados 
					, registros_mod as modificados 
				from 
					sistema.lotes 
				where 
					lote not in (select unnest (lote) from ddjj.sirge) 
					and id_estado = 1 
					and id_padron = ?
					and id_provincia = ?";
			$this->_lotes 			= $this->_db->query($sql , $params)->getResults();
			$this->_id_impresion 	= $ddjj->registrar($this->_lotes);
		} else {
			$this->_id_impresion	= $id_impresion;
		}
	}
	
	protected function tablaLotes () {

		$encabezado = array ('LOTE','INSERTADOS','RECHAZADOS','MODIFICADOS');
		
		$this->SetFont('Arial','B',8);
		$this->SetFillColor(220,220,220);
		
		foreach ($encabezado as $columna) {
			$this->Cell(40,8,$columna,1,0,'C',1);
		}
		$this->Ln();
		$this->SetFont('Arial','',9);

		foreach ($this->_data_sirge as $index => $clave) {
			
			$this->_contador['insertados'] 	+= $clave['registros_in'];
			$this->_contador['rechazados'] 	+= $clave['registros_out'];
			$this->_contador['modificados']	+= $clave['registros_mod'];
			
			$this->Cell(40,8,$clave['lote'],1,0,'R');
			$this->Cell(40,8,number_format($clave['registros_in'] , 0 , ',' , '.'),1,0,'R');
			$this->Cell(40,8,number_format($clave['registros_out'] , 0 , ',' , '.'),1,0,'R');
			$this->Cell(40,8,number_format($clave['registros_mod'] , 0 , ',' , '.'),1,0,'R');
			$this->Ln();
		}
		
		$this->SetFont('Arial','B',8);
		$this->Cell(40,8,"TOTALES",1,0,'L',1);
		$this->SetFont('Arial','B',9);
		$this->Cell(40,8,number_format($this->_contador['insertados'] , 0 , ',' , '.'),1,0,'R',1);
		$this->Cell(40,8,number_format($this->_contador['rechazados'] , 0 , ',' , '.'),1,0,'R',1);
		$this->Cell(40,8,number_format($this->_contador['modificados'] , 0 , ',' , '.'),1,0,'R',1);
		$this->Ln(15);
	}
	
	
	
	public function ddjjSirge ($id_impresion) {
		$this->getDataImpresion($id_impresion);
		$this->encabezado();
		$this->tablaLotes();
		$this->saludo();
	}
	
}

?>
