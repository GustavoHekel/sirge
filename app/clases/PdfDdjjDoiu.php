<?php

class PdfDdjjDoiu extends PdfLogoViejo {

	private
	$_sistema = 'SIRGe Web ',
	$_texto,
	$_constancia,
	$_id_impresion,
	$_data_backup,
	$_ddjj,
	$_db;
	var $widths;
	var $aligns;

	public function __construct() {
		parent::FPDF();
		$this->_db   = Bdd::getInstance();
		$this->_ddjj = new Ddjj();
	}

	protected function encabezado() {

		if (isset($_GET['periodo'])) {
			$periodo = $_GET['periodo'];
		} else {
			$periodo = $this->_data_backup[0]['periodo_reportado'];
		}
		$this->_texto      = "Por medio de la presente se informa que se encuentra actualizada en el SIRGe Web la Tabla de Efectores correspondiente al mes de " . mes_a_texto(substr($periodo, -2)) . " de " . substr($periodo, 0, 4) . ".";
		$this->_constancia = "Dejo constancia bajo juramento que la información referida en la presente nota y los soportes ópticos acompañados, han sido elaborados siguiendo todos los procedimientos razonables para garantizar la mayor exactitud posible en los mismos";

		$this->Cell(130);

		$this->SetFont('Arial', 'B', 10);
		$this->Ln(5);
		$this->Cell(155, 10, utf8_decode('FORMULARIO DE ENVÍO DE INFORMACIÓN PRIORIZADA DEL PROGRAMA SUMAR'), 1, 0, 'C');
		$this->Cell(26, 10, numero_ddjj($this->_data_backup[0]['id_provincia'], $this->_data_backup[0]['periodo_reportado']), 1, 0, 'C');
		$this->Ln(8);
		$this->Cell(175, 15, utf8_decode(Sirge::getNombreProvincia($this->_data_backup[0]['id_provincia'])) . ", " . fecha_con_nombre_mes($this->_data_backup[0]['fecha_impresion']), 0, 0, 'R');
		$this->Ln(10);
		$this->destinatarioCoordNac();
		$this->SetFont('Arial', '', 9);
		$this->Ln(3);
		$this->Cell(0, 8, utf8_decode("De mi mayor consideración:"));
		$this->Ln();
		$this->Cell(15);
		$this->Write(8, utf8_decode($this->_texto));
		$this->Ln(10);
		$this->_texto = 'De acuerdo con dichos elementos, el número de Efectores Integrantes asciende a ' . $this->_data_backup[0]['efectores_integrantes'] . '. Asimismo, el número de Efectores con Compromiso de Gestión firmado en la provincia asciende a ' . $this->_data_backup[0]['efectores_convenio'] . '.';
		$this->Cell(15);
		$this->Write(8, utf8_decode($this->_texto));
		$this->Ln(10);
		$this->_texto = 'Por otra parte, dejo constancia que:';
		$this->Write(8, utf8_decode($this->_texto));
		$this->Ln();
		$this->SetLeftMargin(30);
		$this->SetFont('Arial', '', 9);
		$this->_texto = '1.   Se encuentra cargado y autorizado el Tablero de Control del Programa SUMAR con los datos correspondientes al período ' . $this->_data_backup[0]['periodo_tablero_control'] . '.';
		$this->Write(8, utf8_decode($this->_texto));
		$this->Ln();
		$this->_texto = '2.  Con fecha ' . formato_fecha($this->_data_backup[0]['fecha_cuenta_capitas']) . ' se remitió al Área de Supervisión y Auditoría de la Gestión Administrativa y Financiera de la UEC la Declaración Jurada que incluye los ingresos y egresos de la Cuenta Cápitas Provincial del SPS durante el mes de ' . mes_a_texto(substr($this->_data_backup[0]['periodo_cuenta_capitas'], -2)) . ' de ' . substr($this->_data_backup[0]['periodo_cuenta_capitas'], 0, 4) . ', y la copia del extracto bancario de dicha cuenta correspondiente al mismo período.';
		$this->Write(8, utf8_decode($this->_texto));
		$this->Ln();
		$this->_texto = '3.  Con fecha ' . formato_fecha($this->_data_backup[0]['fecha_sirge']) . ' se remitió al Área Sistemas Informáticos de la UEC la Declaración Jurada de Prestaciones, Comprobantes y Uso de Fondos realizado por los efectores correspondientes al Sistema de Reportes de Gestión (SIRGE), actualizando con los datos correspondientes al período ' . $this->_data_backup[0]['periodo_sirge'] . '.';
		$this->Write(8, utf8_decode($this->_texto));
		$this->Ln();
		$this->_texto = '4.  Con fecha ' . formato_fecha($this->_data_backup[0]['fecha_reporte_bimestral']) . ' se remitió al Área Planificación Estratégica de la UEC, el Reporte bimestral de Prestaciones del SPS del Programa SUMAR y el Reporte bimestral de Uso de Fonos del SPS del Programa SUMAR correspondientes al bimestre Nº ' . $this->_data_backup[0]['bimestre'] . ' del año ' . $this->_data_backup[0]['anio_bimestre'] . '.';
		$this->Write(8, utf8_decode($this->_texto));
		$this->Ln(10);
		$this->SetLeftMargin(15);
		$this->SetFont('Arial', '', 9);
		$this->Write(8, utf8_decode($this->_constancia));
		$this->Ln(11);
	}

	protected function getDataImpresion($id_impresion) {

		$params = array($id_impresion);
		$sql    = "
			select
				*
			from
				ddjj.doiu9
			where
				id_impresion = ? ";
		$this->_data_backup = $this->_db->query($sql, $params)->getResults();
	}

	public function ddjjImpDoiu($id_impresion) {
		$this->getDataImpresion($id_impresion);
		$this->encabezado();
		$this->saludo();
	}
}

function formato_fecha($fecha) {
	$array = explode('-', $fecha);
	return $array[2] . '/' . $array[1] . '/' . $array[0];
}

function mes_a_texto($numero) {
	switch ($numero) {
		case 1:$mes = 'Enero';
			break;
		case 2:$mes = 'Febrero';
			break;
		case 3:$mes = 'Marzo';
			break;
		case 4:$mes = 'Abril';
			break;
		case 5:$mes = 'Mayo';
			break;
		case 6:$mes = 'Junio';
			break;
		case 7:$mes = 'Julio';
			break;
		case 8:$mes = 'Agosto';
			break;
		case 9:$mes = 'Septiembre';
			break;
		case 10:$mes = 'Octubre';
			break;
		case 11:$mes = 'Noviembre';
			break;
		case 12:$mes = 'Diciembre';
			break;
		default:break;
	}
	return $mes;
}

function numero_ddjj($id_provincia, $periodo) {
	return utf8_decode("Nº $id_provincia/$periodo");
}

function fecha_con_nombre_mes($fecha) {

	$fecha_array = explode('-', $fecha);

	switch ($fecha_array[1]) {
		case 1:$mes = 'Enero';
			break;
		case 2:$mes = 'Febrero';
			break;
		case 3:$mes = 'Marzo';
			break;
		case 4:$mes = 'Abril';
			break;
		case 5:$mes = 'Mayo';
			break;
		case 6:$mes = 'Junio';
			break;
		case 7:$mes = 'Julio';
			break;
		case 8:$mes = 'Agosto';
			break;
		case 9:$mes = 'Septiembre';
			break;
		case 10:$mes = 'Octubre';
			break;
		case 11:$mes = 'Noviembre';
			break;
		case 12:$mes = 'Diciembre';
			break;
		default:break;
	}

	return utf8_decode(html_entity_decode(substr($fecha_array[2], 0, 2) . ' de ' . $mes . ' de ' . $fecha_array[0]));
}

function get_Datetime_Now() {
	$tz_object = new DateTimeZone('Brazil/East');
	//date_default_timezone_set('Argentina/East');

	$datetime = new DateTime();
	$datetime->setTimezone($tz_object);
	return $datetime;
}

?>
