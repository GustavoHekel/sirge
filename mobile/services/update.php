<?php 

require_once '../init.php';

$db = Bdd::getInstance();

$sql = "
	UPDATE
		mobile.usuarios
	SET
		nombre = ?,
		apellido = ?,
		sexo = ?,
		tipo_documento = ?,
		numero_documento = ?,
		fecha_nacimiento = ?,
		email = ?,
		id_provincia = ?,
		domicilio = ?,
		fecha_modificacion = LOCALTIMESTAMP
	WHERE
		id_usuario = ?";

$params = [
	$_REQUEST['nombre'],
	$_REQUEST['apellido'],
	$_REQUEST['genero'],
	$_REQUEST['tipo_documento'],
	$_REQUEST['numero_documento'],
	$_REQUEST['fecha_nacimiento'],
	$_REQUEST['email'],
	$_REQUEST['id_provincia'],
	$_REQUEST['domicilio'],
	$_REQUEST['user']
];

$up['actualizado'] = $db->query($sql , $params)->getCount();

echo $_REQUEST['callback'] . '(' . json_encode($up) . ')';
