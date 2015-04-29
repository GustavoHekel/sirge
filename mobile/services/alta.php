<?php

require_once '../init.php';

$db = Bdd::getInstance();
$data = json_decode($_REQUEST['p'] , true);
$return = [
	'alta' => 0
];
$params = [
	$data['nombre'],
	$data['apellido'],
	$data['sexo'],
	$data['tipo_documento'],
	$data['ndoc'],
	$data['fnac'],
	$data['email'],
	md5($data['pass']),
	$data['telefono'],
	$data['provincia']

];
$sql = "
	insert into mobile.usuarios (nombre , apellido , sexo , tipo_documento , numero_documento , fecha_nacimiento , email , pass , numero , id_provincia)
	values (?,?,?,?,?,?,?,?,?,?)";

$return['alta'] = $db->query($sql , $params)->getCount();

echo $_REQUEST['callback'] . '(' . json_encode($return) . ')';
