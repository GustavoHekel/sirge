<?php

require_once '../../../init.php';

$benef = new Indicadores();

$Html = array(
	'../../vistas/indicadores/medica.html',
);

$sirge = new Sirge();

$diccionario = array(
	'provincias' => $sirge->selectProvincia('provincias', $_SESSION['grupo']));

Html::vista($Html, $diccionario);

?>