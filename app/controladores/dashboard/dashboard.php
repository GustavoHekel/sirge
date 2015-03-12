<?php

require_once '../../../init.php';

$dash = new Dashboard();
$noti = new Notificaciones();

$html = array (
	'../../vistas/dashboard/dashboard.html'
);

$diccionario = array (
	'TOTAL_PRESTACIONES' 	=> $dash->cantidadPrestaciones(),
	'TOTAL_EFECTORES' 		=> $dash->cantidadEfectores(),
	'TOTAL_BENEFICIARIOS'	=> $dash->cantidadBeneficiarios(),
	'TOTAL_USUARIOS' 		=> $dash->cantidadUsuarios(),
	'ESTADO_PRESTACIONES' 	=> $dash->porcentaje(1),
	'ESTADO_COMPROBANTES' 	=> $dash->porcentaje(3),
	'ESTADO_FONDOS' 		=> $dash->porcentaje(2),
	'ESTADO_PUCO' 			=> $dash->porcentaje(4),
	'CANTIDAD_VISITAS' 		=> $dash->visitas(),
	'COMENTARIOS' 			=> $dash->listadoComentarios(),
	'PERIODO' 				=> date('Y-m'),
	'NOTIFICACION_SIRGE'	=> $noti->gritterSIRGe($_SESSION['grupo']),
	'NOTIFICACION_DOIU9' 	=> $noti->gritterDOIU9($_SESSION['grupo'])
);

Html::Vista($html , $diccionario);

?>
