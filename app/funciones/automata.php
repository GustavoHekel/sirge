<?php

require '../../init.php';

$db = Bdd::getInstance();
$consulta = $_REQUEST['id_consulta'];

$data = $db->query('select * from consultas.automaticas where id_consulta = ?' , [$consulta])->getResults()[0];

echo '<pre>' , print_r($data) , '</pre>';

$sql = file_get_contents($data['ruta_sql']);

$resultado = $db->query($sql)->getResults();

echo '<pre>' , print_r($resultado) , '</pre>';

foreach ($resultado as $key => $value) {
	file_put_contents($data['ruta_destino'], implode(";", $value) . "\r\n" , FILE_APPEND);
}



