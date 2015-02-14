<?php

require_once '../../../init.php';

$sirge = new SIRGe();
$notif = new Notificaciones();

$html = array (
	'../../vistas/dashboard/dashboard.html'
);

$diccionario = array (
	'TOTAL_PRESTACIONES' 	=> $sirge->CantidadPrestaciones(),
	'TOTAL_EFECTORES' 		=> $sirge->CantidadEfectores(),
	'TOTAL_BENEFICIARIOS'	=> $sirge->CantidadBeneficiarios(),
	'TOTAL_USUARIOS' 		=> $sirge->CantidadUsuarios(),
	'ESTADO_PRESTACIONES' 	=> $sirge->PorcentajeDB(1),
	'ESTADO_COMPROBANTES' 	=> $sirge->PorcentajeDB(3),
	'ESTADO_FONDOS' 		=> $sirge->PorcentajeDB(2),
	'ESTADO_PUCO' 			=> $sirge->PorcentajeDB(4),
	'CANTIDAD_VISITAS' 		=> $sirge->VisitasDB(),
	'COMENTARIOS' 			=> $sirge->ListarComentariosDB(),
	'PERIODO' 				=> date('Y-m'),
	'NOTIFICACION_SIRGE'	=> $notif->GritterSIRGe('03'),
	'NOTIFICACION_DOIU9' 	=> $notif->GritterDOIU9('03')
);

HTML::Vista($html , $diccionario);

?>
