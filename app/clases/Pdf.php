<?php

class Pdf extends FPDF {
	
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
		$_data_sirge;
	
	public function Header () {
		$this->SetFont('Arial','B',11);
		$this->Image('../../public/img/sumar-grande.png',25,10,20);
		$this->Image('../../public/img/min_logo.jpg',130,15,0,15);
		$this->Line(10,35,200,35);
		$this->Ln(25);
	}
	
	public function Footer () {
		$this->SetY(-15);
		$this->SetFont('Arial','',8);
		$this->Line(10,280,200,280);
		$this->Cell(0,10,utf8_decode('Página '.$this->PageNo().'/{nb}'),0,0,'C');
	}
	
	protected function EncabezadoSirge () {
		
		$this->_texto 			= "Por medio de la presente elevo a Ud. en carácter de Declaración Jurada, los resultados del proceso de validación de la información en el ";
		$this->_prestaciones 	= "correspondiente a las PRESTACIONES aprobadas desde la última presentación hasta el día de la fecha, detalladas en el siguiente cuadro:";
		$this->_comprobantes 	= "correspondiente a los COMPROBANTES recibidos desde la última presentación hasta el día de la fecha, detallados en el siguiente cuadro:";
		$this->_fondos		  	= "correspondiente a las APLICACIONES DE FONDOS reportadas por los efectores desde la última presentación hasta el día de la fecha, detalladas en el siguiente cuadro:";
		
		$this->Cell(130);
		$this->Cell(25,8,utf8_decode(SIRGe::RetornaNombreProvincia($this->_data_sirge[0]['id_provincia'])) . ", " . $this->_data_sirge[0]['fecha_impresion'],0,0,'R');
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
			default: die("ERROR");
		}
		$this->Ln(10);
	}
	
	protected function GetDataImpresion ($id_impresion) {
		$params = array ($id_impresion);
		$sql = "
			select 
				l.lote
				, fecha_impresion :: date
				, s.id_provincia
				, id_padron
				, registros_in
				, registros_out
				, registros_mod
			from 
				ddjj.sirge s left join
				sistema.lotes l on l.lote = any (s.lote)
			where 
				id_impresion = ?";
		$this->_data_sirge = Bdd::GetInstance()->Query($sql , $params)->GetResults();
	}
	
	protected function GetIdImpresion ($id_padron , $id_impresion = null) {
		
		if (! is_null ($id_impresion)) {
			$padron = new Padron();
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
			$this->_lotes 			= Bdd::GetInstance()->Query($sql , $params)->GetResults();
			$this->_id_impresion 	= $padron->RegistraDDJJSIRGe($this->_lotes);
		} else {
			$this->_id_impresion	= $id_impresion;
		}
	}
	
	protected function TablaLotes () {

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
	
	protected function Saludo () {
		$this->SetFont('Arial','',11);
		$this->Cell(0,6,utf8_decode("Sin otro particular saludo a Ud. con mi consideración más distinguida"));
		$this->SetY(-30);
		$this->Cell(80);
		$this->Cell(80,6,utf8_decode("Firma y sello del Coordinador Ejecutivo"),'T',0,'C');
	}
	
	public function DDJJSIRGe ($id_impresion) {
		$this->GetDataImpresion($id_impresion);
		$this->EncabezadoSirge();
		$this->TablaLotes();
		$this->Saludo();
	}
	
}

?>
