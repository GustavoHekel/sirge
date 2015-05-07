<?php

require_once '../../../init.php';

//var_dump($datos);

$url = ['../../vistas/indicadores/tabla_efectores_priorizados.html'];

$array = array('id_provincia' => $_POST['provincia'], 'indicador' => $_POST['indicador']);

Html::vista($url, $array);

//echo json_encode($datos);

?>