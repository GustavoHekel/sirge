<?php

require_once '../../init.php';

$u = new Usuario();
$e = new Efector();
$id = $_REQUEST['user'];
$data = $u->getUserData($id);
$estado = $u->getEstado($id);

$html = [
  '../../vistas/tabs/usuario.html'
];

$diccionario = [
	'NOMBRE_APELLIDO' => $data['n'],
	'EDAD' => $data['edad'],
	'TIPO_NUMERO_DOCUMENTO' => $data['dni'],
	'DOMICILIO' => $data['domicilio'],
	'PROVINCIA' => $data['provincia'],
	'NOMBRE_EFECTOR_ASIGNADO' => $e->getEfectorAsignado($id),
	'ESTADO' => $estado['ESTADO'],
	'CSS_ESTADO' => $estado['CSS_ESTADO'],
	'ICONO_ESTADO' => $estado['ICONO_ESTADO'],
	'PRACTICAS_ULTIMO_AÑO' => $u->getPracticasUltimoAnio($id)
];

$params = [
	'template' => Html::vista($html , $diccionario)
];

echo $_GET['callback'] . '(' . json_encode($params) . ')';
