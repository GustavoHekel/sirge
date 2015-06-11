<?php

require_once '../../init.php';

$u = new Usuario();
$id = $_REQUEST['user'];
$data = $u->getUserData($id);

$html = [
  '../../vistas/panel/ajustes.html'
];

$diccionario = [
	'NOMBRE' => $data['nombre'],
	'APELLIDO' => $data['apellido'],
	'DOMICILIO' => $data['domicilio'],
	'SELECT_PROVINCIA' => Forms::getSelectProvincia($id),
	'EMAIL' => $data['email'],
	'SELECT_TIPO_DOC' => Forms::getSelectTipoDocumento($id),
	'NUMERO_DOCUMENTO' => $data['numero_documento'],
	'FECHA_NACIMIENTO' => date_format(date_create($data['fecha_nacimiento']) , "d/m/Y"),
	'SELECT_GENERO' => Forms::getSelectSexo($id)
];

$params = [
	'template' => Html::vista($html , $diccionario)
];

echo $_GET['callback'] . '(' . json_encode($params) . ')';
