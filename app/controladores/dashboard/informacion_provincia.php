<?php

require_once '../../../init.php';

$html = array (
	'../../vistas/dashboard/informacion_provincia.html'
);

$prestaciones 	= new Prestaciones();
$efectores 		= new Efectores();
$beneficiarios 	= new Beneficiarios();

$diccionario = array (
	'HABITANTES' 			=> $beneficiarios->INDECHabitantes(SIRGe::RetornaIdProvincia($_POST['provincia'])),
	'BENEFICIARIOS' 		=> $beneficiarios->ResumenBeneficiarios(SIRGe::RetornaIdProvincia($_POST['provincia']) , 'beneficiarios'),
	'BENEFICIARIOS_CEB' 	=> $beneficiarios->ResumenBeneficiarios(SIRGe::RetornaIdProvincia($_POST['provincia']) , 'beneficiarios_ceb'),
	'MUJERES' 				=> $beneficiarios->ResumenBeneficiarios(SIRGe::RetornaIdProvincia($_POST['provincia']) , 'mujeres'),
	'HOMBRES' 				=> $beneficiarios->ResumenBeneficiarios(SIRGe::RetornaIdProvincia($_POST['provincia']) , 'hombres'),
	'MUJERES_CEB' 			=> $beneficiarios->ResumenBeneficiarios(SIRGe::RetornaIdProvincia($_POST['provincia']) , 'mujeres_ceb'),
	'HOMBRES_CEB' 			=> $beneficiarios->ResumenBeneficiarios(SIRGe::RetornaIdProvincia($_POST['provincia']) , 'hombres_ceb'),
	'GRUPO_A' 				=> $beneficiarios->ResumenBeneficiarios(SIRGe::RetornaIdProvincia($_POST['provincia']) , 'beneficiarios_05'),
	'GRUPO_B' 				=> $beneficiarios->ResumenBeneficiarios(SIRGe::RetornaIdProvincia($_POST['provincia']) , 'beneficiarios_69'),
	'GRUPO_C' 				=> $beneficiarios->ResumenBeneficiarios(SIRGe::RetornaIdProvincia($_POST['provincia']) , 'beneficiarios_1019'),
	'GRUPO_D' 				=> $beneficiarios->ResumenBeneficiarios(SIRGe::RetornaIdProvincia($_POST['provincia']) , 'beneficiarios_2064'),
	'EFECTORES' 			=> $efectores->CantidadEfectoresProvincia(SIRGe::RetornaIdProvincia($_POST['provincia'])),
	'EFECTORES_CONVENIO' 	=> $efectores->CantidadEfectoresCompromisoProvincia(SIRGe::RetornaIdProvincia($_POST['provincia'])),
	'DESCENTRALIZACION' 	=> $efectores->PorcentajeDescentralizacion(SIRGe::RetornaIdProvincia($_POST['provincia'])),
	'PRESTACIONES' 			=> $prestaciones->CantidadPrestacionesProvincia(SIRGe::RetornaIdProvincia($_POST['provincia']))
);

HTML::Vista($html , $diccionario);

?>
