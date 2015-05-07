<?php

require_once '../../../init.php';

$efectores_priorizados = new Indicadores();

$datos = $efectores_priorizados->getEfectoresPriorizados($_POST['indicador'], $_POST['provincia']);

echo json_encode($datos);

?>