<?php

require_once '../../init.php';

$u = new Usuario();
$id = $_REQUEST['user'];

$html = [
  '../../vistas/tabs/usuario.html'
];

$diccionario = [
	'NOMBRE_APELLIDO' => $u->getNombreApellido($id),
	'EDAD' => $u->getEdad($id),
	'TIPO_NUMERO_DOCUMENTO' => $u->getTipoNumero($id),
	'DOMICILIO' => 'Av. Nazca 1450 4ºF',
	'PROVINCIA' => 'Ciudad Autónoma de Buenos Aires',
	'NOMBRE_EFECTOR_ASIGNADO' => '',
	'ESTADO' => '',
	'PRACTICAS_ULTIMO_AÑO' => '',
	'FAMILIARES' => ''
];

$params = [
	'template' => Html::vista($html , $diccionario)
];

echo $_GET['callback'] . '(' . json_encode($params) . ')';
