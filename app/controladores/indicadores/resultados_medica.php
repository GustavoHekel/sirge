<?php

require_once '../../../init.php';

$indic = new Indicadores();

$Html = ['../../vistas/indicadores/resultados_medica.html'];

$sirge = new Sirge();

$id_provincia = $_POST['id_provincia'];

$datos_fecha = explode('-', $_POST['fecha']);

$year  = $datos_fecha[0];
$month = $datos_fecha[1];

$periodo = intval(date("Ym", mktime(0, 0, 0, $month + 1, 0, $year)));
$fecha   = date('Y-m-d', mktime(0, 0, 0, $month + 1, 0, $year));

$indicadores = array(6, 9, 10, 11, 12, 13);

for ($i = 0; $i < 6; $i++)
{
	$indicador   = "3.".$indicadores[$i];
	$indicador_a = $indicador.".a";
	$indicador_b = $indicador.".b";

	$data = $indic->getIndicadorMedicaRangos($indicador, $id_provincia, $year);

	$row[$i] = $data[0];

	$data2 = $indic->getResultadosIndicadorProvincial($periodo, $id_provincia, '%'.$indicador.'%');

	for ($w = 0; $w < count($data2); $w++)
	{

		if (in_array($data2[$w]['codigo_indicador'], array("3.11.b", "3.12.b", "3.13.b")))
		{
			$resultado = $indic->getResultadosIndicadorProvPromedio($fecha, $fecha, $id_provincia, $data2[$w]['codigo_indicador'])[0]['round'];
		}
		else
		{
			$resultado = $data2[$w]['resultado'];
		}

		//$row[$i]['NOMBRE_INDICADOR'] = $data2[$w]['codigo_indicador'];
		$row[$i][$data2[$w]['codigo_indicador']] = $resultado;
	}

	if ($row[$i][$indicador_a] == 0 && $row[$i][$indicador_b] == 0)
	{
		$row[$i][$indicador_b] = 1;
	}

	$valor = round((($row[$i][$indicador_a] / $row[$i][$indicador_b]) * 100), 1);

	$row[$i]['valor'] = $valor;
}

for ($i = 0; $i < count($row); $i++)
{

	$array_a_mostrar['RESULTADO_INDICADOR_'.$row[$i]['codigo_indicador']] = $row[$i]['valor'];
	$array_a_mostrar['INDICADOR_'.$row[$i]['codigo_indicador']] = $row[$i]['codigo_indicador'];
	//$array_a_mostrar['DESCRIPCION_INDICADOR_'.$row[$i]['codigo_indicador']] = $indic->getDescripcionIndicador($row[$i]['codigo_indicador'])[0]['descripcion'];

	if (isset($row[$i]['3.'.$indicadores[$i].'.a']))
	{
		$array_a_mostrar['NUMERADOR_INDICADOR_3.'.$indicadores[$i].'.a'] = $row[$i]['3.'.$indicadores[$i].'.a'];
	}
	if (isset($row[$i]['3.'.$indicadores[$i].'.b']))
	{
		$array_a_mostrar['DENOMINADOR_INDICADOR_3.'.$indicadores[$i].'.b'] = $row[$i]['3.'.$indicadores[$i].'.b'];
	}

	$valor = $row[$i]['valor'];

	if ($valor >= $row[$i]['max_rojo'] && $valor <= $row[$i]['min_rojo'])
	{
		$array_a_mostrar['INDICADOR_'.$row[$i]['codigo_indicador'].'_COLOR'] = 'red';
	}
	elseif ($valor >= $row[$i]['max_verde'] && $valor <= $row[$i]['min_verde'])
	{
		$array_a_mostrar['INDICADOR_'.$row[$i]['codigo_indicador'].'_COLOR'] = 'green';
	}
	else
	{
		$array_a_mostrar['INDICADOR_'.$row[$i]['codigo_indicador'].'_COLOR'] = 'yellow';
	}
}

$array_a_mostrar['id_provincia'] = $id_provincia;
$array_a_mostrar['year'] = $year;
//echo "<pre>", print_r($array_a_mostrar), "</pre>";
               //echo "<pre>", print_r($row), "</pre>";
$diccionario = $array_a_mostrar;

Html::vista($Html, $diccionario);
?>