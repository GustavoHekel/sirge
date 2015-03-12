<?php

require_once '../../../init.php';

$params = array ($_POST['lote']);
$sql 	= "
	select 
		* 
	from 
		sistema.lotes l left join
		sistema.subidas s on l.id_subida = s.id_subida left join
		sistema.usuarios u on l.id_usuario = u.id_usuario
	where lote = ?";
$data 	= Bdd::GetInstance()->Query($sql , $params)->GetRow();

$html = array (
	'../../vistas/padron/detalle_lote.html'
);

$diccionario = array (
	'LOTE' 				=> $data['lote'],
	'PROVINCIA' 		=> Sirge::getNombreProvincia ($data['id_provincia']),
	'ARCHIVO' 			=> $data['nombre_original'],
	'IN' 				=> $data['registros_in'],
	'OUT' 				=> $data['registros_out'],
	'MOD'				=> $data['registros_mod'],
	'INICIO' 			=> $data['inicio'],
	'FIN' 				=> $data['fin'],
	'USUARIO_CARGA' 	=> $data['usuario'],
	'USUARIO_CIERRE' 	=> Padron::getUsuarioCierre($data['lote']),
	'RECHAZOS'			=> Padron::getRechazos($data['lote'])
);

Html::Vista($html , $diccionario);


?>
