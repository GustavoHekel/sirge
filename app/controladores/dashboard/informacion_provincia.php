<?php

require_once '../../../init.php';

$html = array (
	'../../vistas/dashboard/informacion_provincia.html'
);

$prestaciones 	= new Prestaciones();
$efectores 		= new Efectores();
$beneficiarios 	= new Beneficiarios();

$diccionario = array (
	'HABITANTES' 			=> $beneficiarios->INDECHabitantes(Sirge::getIdProvincia($_POST['provincia'])),
	'BENEFICIARIOS' 		=> $beneficiarios->resumenBeneficiarios(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios'),
	'BENEFICIARIOS_CEB' 	=> $beneficiarios->resumenBeneficiarios(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios_ceb'),
	'MUJERES' 				=> $beneficiarios->resumenBeneficiarios(Sirge::getIdProvincia($_POST['provincia']) , 'mujeres'),
	'HOMBRES' 				=> $beneficiarios->resumenBeneficiarios(Sirge::getIdProvincia($_POST['provincia']) , 'hombres'),
	'MUJERES_CEB' 			=> $beneficiarios->resumenBeneficiarios(Sirge::getIdProvincia($_POST['provincia']) , 'mujeres_ceb'),
	'HOMBRES_CEB' 			=> $beneficiarios->resumenBeneficiarios(Sirge::getIdProvincia($_POST['provincia']) , 'hombres_ceb'),
	'GRUPO_A' 				=> $beneficiarios->resumenBeneficiarios(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios_05'),
	'GRUPO_B' 				=> $beneficiarios->resumenBeneficiarios(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios_69'),
	'GRUPO_C' 				=> $beneficiarios->resumenBeneficiarios(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios_1019'),
	'GRUPO_D' 				=> $beneficiarios->resumenBeneficiarios(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios_2064'),
	'EFECTORES' 			=> $efectores->cantidadEfectoresProvincia(Sirge::getIdProvincia($_POST['provincia'])),
	'EFECTORES_CONVENIO' 	=> $efectores->cantidadEfectoresCompromisoProvincia(Sirge::getIdProvincia($_POST['provincia'])),
	'DESCENTRALIZACION' 	=> $efectores->porcentajeDescentralizacion(Sirge::getIdProvincia($_POST['provincia'])),
	'PRESTACIONES' 			=> $prestaciones->cantidadPrestacionesProvincia(Sirge::getIdProvincia($_POST['provincia']))
);

Html::Vista($html , $diccionario);

?>
