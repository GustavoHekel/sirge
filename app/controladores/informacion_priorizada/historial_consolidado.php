<?php

require_once '../../../init.php';

$inst_info_priorizada = new InformacionPriorizada();
$inst_sirge           = new Sirge();

/*function devolver_json($resource) {

for ($j = 0; $j < pg_num_fields($resource); $j++) {
$data['aoColumns'][$j]['sTitle'] = pg_field_name($resource, $j);
$data['aoColumns'][$j]['mData']  = pg_field_name($resource, $j);
}

if (pg_num_rows($resource)) {
$i = 0;

while ($registro = pg_fetch_assoc($resource)) {
for ($j = 0; $j < pg_num_fields($resource); $j++) {
$data['aaData'][$i][pg_field_name($resource, $j)] = $registro[pg_field_name($resource, $j)];
}
$i++;
}

$data['iTotalRecords']        = pg_num_rows($resource);
$data['iTotalDisplayRecords'] = pg_num_rows($resource);
$data['sEcho']                = 1;

} else {
$data['aaData']               = [];
$data['iTotalRecords']        = 0;
$data['iTotalDisplayRecords'] = 0;
}
return (json_encode($data));
}*/

if (isset($_GET['armar_tabla'])) {

	$i       = '201310';
	$periodo = $i;
	$ano     = substr($i, 0, 4);
	$mes     = substr($i, 4, 2);

	$datos = $inst_info_priorizada->getHistorialConsilidadoDoiu();
	//var_dump($datos);

	echo $inst_sirge->jsonDT($datos, false);
} else {
	$Html = array(
		'../../vistas/informacion_priorizada/historial_consolidado.html',
	);

	$diccionario = array('');

	Html::vista($Html, $diccionario);
}
?>