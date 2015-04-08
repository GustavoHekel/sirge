<?php

require_once '../../../init.php';

$Html = array (
	'../../vistas/dashboard/informacion_provincia.html'
);

$prestaciones 	= new Prestaciones();
$efectores 		= new Efectores();
$beneficiarios 	= new Beneficiarios();

$diccionario = array (
	'HABITANTES' 			=> $beneficiarios->habitantes(Sirge::getIdProvincia($_POST['provincia'])),
	'BENEFICIARIOS' 		=> $beneficiarios->resumen(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios'),
	'BENEFICIARIOS_CEB' 	=> $beneficiarios->resumen(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios_ceb'),
	'MUJERES' 				=> $beneficiarios->resumen(Sirge::getIdProvincia($_POST['provincia']) , 'mujeres'),
	'HOMBRES' 				=> $beneficiarios->resumen(Sirge::getIdProvincia($_POST['provincia']) , 'hombres'),
	'MUJERES_CEB' 			=> $beneficiarios->resumen(Sirge::getIdProvincia($_POST['provincia']) , 'mujeres_ceb'),
	'HOMBRES_CEB' 			=> $beneficiarios->resumen(Sirge::getIdProvincia($_POST['provincia']) , 'hombres_ceb'),
	'GRUPO_A' 				=> $beneficiarios->resumen(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios_05'),
	'GRUPO_B' 				=> $beneficiarios->resumen(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios_69'),
	'GRUPO_C' 				=> $beneficiarios->resumen(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios_1019'),
	'GRUPO_D' 				=> $beneficiarios->resumen(Sirge::getIdProvincia($_POST['provincia']) , 'beneficiarios_2064'),
	'EFECTORES' 			=> $efectores->getEfectoresProvincia(Sirge::getIdProvincia($_POST['provincia'])),
	'EFECTORES_CONVENIO' 	=> $efectores->getEfectoresCompromisoProvincia(Sirge::getIdProvincia($_POST['provincia'])),
<<<<<<< HEAD
	'DESCENTRALIZACION' 	=> $efectores->getDescentralizacion(Sirge::getIdProvincia($_POST['provincia'])),
=======
	'DESCENTRALIZACION' 	=> $efectores->descentralizacion(Sirge::getIdProvincia($_POST['provincia'])),
>>>>>>> 97477a8c1cbe908c65c71c0041e8efb517f228f6
	'PRESTACIONES' 			=> $prestaciones->getPrestacionesProvincia(Sirge::getIdProvincia($_POST['provincia']))
);

Html::vista($Html , $diccionario);