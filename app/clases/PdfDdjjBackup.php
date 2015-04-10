<?php

class PdfDdjjBackup extends Pdf {

	private
	$_sistema = 'SIRGe Web ',
	$_texto,
	$_constancia,
	$_id_impresion,
	$_data_backup,
	$_ddjj,
	$_db;

	public function __construct()
	{
		parent::FPDF();
		$this->_db = Bdd::getInstance();
		$this->_ddjj = new Ddjj();
	}

	protected function encabezado()
	{

		$this->_texto = "Por medio de la presente certifico que el día ".fecha_con_nombre_mes($this->_data_backup[0]['fecha_impresion'])." se ha realizado por duplicado la Copia de Resguardo Completa de la Base de Datos de Inscripción referente al período ".$this->_data_backup[0]['periodo_reportado']." en el archivo ".$this->_data_backup[0]['nombre_backup'].".";
		$this->_constancia = "Dejo constancia bajo juramento que la información enviada en esta nota es exacta y verdadera y que las copias han sido elaboradas y resguardadas siguiendo todos los procedimientos razonables para garantizar la mayor exactitud posible. Las mismas se encuentran a disposición de cualquier autoridad competente que las requiera.";

		$this->Cell(130);

		$this->SetFont('Arial', 'B', 10);
		$this->Ln(5);
		$this->Cell(140, 10, utf8_decode('FORMULARIO BACKUP DE DATOS DE INSCRIPCION'), 1, 0, 'C');
		$this->Cell(30, 10, numero_ddjj($this->_data_backup[0]['id_provincia'], $this->_data_backup[0]['periodo_reportado']), 1, 0, 'C');

		$this->Cell(-10, 35, utf8_decode(Sirge::getNombreProvincia($this->_data_backup[0]['id_provincia'])).", ".fecha_con_nombre_mes($this->_data_backup[0]['fecha_impresion']), 0, 0, 'R');
		$this->Ln();
		$this->Cell(0, 5, utf8_decode("SEÑOR"));
		$this->Ln();
		$this->Cell(0, 5, utf8_decode("COORDINADOR DEL ÁREA SISTEMAS INFORMÁTICOS"));
		$this->Ln();
		$this->Cell(0, 5, utf8_decode("LIC. JAVIER E. MINSKY"));
		$this->Ln();
		$this->SetFont('Arial', 'BU', 11);
		$this->Cell(0, 5, "S           /           D", 0, 0, 'D');
		$this->SetFont('Arial', '', 11);
		$this->Ln(12);
		$this->Cell(0, 8, utf8_decode("De mi mayor consideración:"));
		$this->Ln();
		$this->Cell(42);
		$this->Write(8, utf8_decode($this->_texto));
		$this->Ln(15);
		$this->Write(8, utf8_decode($this->_constancia));
		$this->Ln(15);
	}

	protected function getDataImpresion($id_impresion)
	{

		$params = array($id_impresion);
		$sql    = "
			select
				*
			from
				ddjj.backup
			where
				id_impresion = ? ";
		$this->_data_backup = $this->_db->query($sql, $params)->getResults();
	}

	protected function setDataBackup($id_impresion)
	{

		$params = array($id_impresion);
		$sql    = "
			select
				*
			from
				ddjj.backup
			where
				id_impresion = ? ";
		$this->_data_backup = $this->_db->query($sql, $params)->getResults();
	}

	function mes_a_texto($numero)
	{
		switch ($numero)
		{
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

	public function ddjjImpBackup($id_impresion)
	{
		$this->getDataImpresion($id_impresion);
		$this->encabezado();
		$this->saludo();
	}

	public function ddjjImpBackupGen($fecha_backup, $nombre_backup, $periodo, $id_provincia)
	{

		$parametros = [$id_provincia, $fecha_backup, $nombre_backup, $periodo];

		$sql = " SELECT id_impresion
						FROM ddjj.backup
						WHERE
						id_provincia = ?
						AND
						fecha_backup = ?
						AND
						nombre_backup = ?
						AND
						periodo_reportado = ? ";

		$data = $this->_db->query($sql, $parametros)->getResults()[0];

		return $data['id_impresion'];
	}

}

function numero_ddjj($id_provincia, $periodo)
{
	return utf8_decode("Nº $id_provincia/$periodo");
}

function fecha_con_nombre_mes($fecha)
{

	$fecha_array = explode('-', $fecha);

	switch ($fecha_array[1])
	{
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

	return utf8_decode(html_entity_decode(substr($fecha_array[2], 0, 2).' de '.$mes.' de '.$fecha_array[0]));
}

?>
