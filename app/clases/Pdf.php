<?php

class Pdf extends FPDF {

	public function __construct() {
		parent::FPDF();
	}

	private
	$_logo_sumar = '../../public/img/sumar-grande.png',
	$_logo_minis = '../../public/img/min_logo.jpg';

	final public function header() {
		$this->SetFont('Arial', 'B', 11);
		$this->Image($this->_logo_sumar, 25, 10, 20);
		$this->Image($this->_logo_minis, 130, 15, 0, 15);
		$this->Line(10, 35, 200, 35);
		$this->Ln(25);
	}

	final public function footer() {
		$this->SetY(-15);
		$this->SetFont('Arial', '', 8);
		$this->Line(10, 280, 200, 280);
		$this->Cell(0, 10, utf8_decode('Página ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
	}

	final protected function saludo() {
		$this->SetFont('Arial', '', 11);
		$this->Cell(0, 6, utf8_decode("Sin otro particular saludo a Ud. con mi consideración más distinguida"));
		$this->SetY(-30);
		$this->Cell(80);
		$this->Cell(80, 6, utf8_decode("Firma y sello del Coordinador Ejecutivo"), 'T', 0, 'C');
	}

	final protected function destinatario() {
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(0, 5, utf8_decode("SEÑOR"));
		$this->Ln();
		$this->Cell(0, 5, utf8_decode("COORDINADOR DEL ÁREA SISTEMAS INFORMÁTICOS"));
		$this->Ln();
		$this->Cell(0, 5, utf8_decode("LIC. JAVIER E. MINSKY"));
		$this->Ln();
		$this->SetFont('Arial', 'BU', 11);
		$this->Cell(0, 5, "S           /           D", 0, 0, 'D');
	}

	final protected function destinatarioCoordNac() {
		$this->SetFont('Arial', 'B', 10);
		$this->Cell(0, 5, utf8_decode("SEÑOR"));
		$this->Ln();
		$this->Cell(0, 5, utf8_decode("COORDINADOR NACIONAL DEL PROGRAMA SUMAR"));
		$this->Ln();
		$this->Cell(0, 5, utf8_decode("DR. MARTIN SABIGNOSO"));
		$this->Ln();
		$this->SetFont('Arial', 'BU', 11);
		$this->Cell(0, 5, "S           /           D", 0, 0, 'D');
	}

}

?>
