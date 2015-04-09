<?php

require_once '../../../init.php';

$inst_ddjj = new DdjjBackup();

if ( ! isset($_POST['acc']))
{
	$Html = array(
		'../../vistas/backup_ugsp/formulario_ddjj.html',
	);

	$diccionario = array('vacio' => 'vacio');

	Html::vista($Html, $diccionario);
}
else
{
	switch ($_POST['acc'])
	{
		case 'consulta':

			if ($_SESSION['grupo'] > 24)
		{
				die("2");
				//echo "<pre>", print_r(count($data)), "</pre>";
			}
		else
		{
				$data = $inst_ddjj->getImpresionEnPeriodo($_SESSION['grupo'], $_POST['periodo']);

				if ($data)
			{
					die("1"); 	// Ya se informó el periodo
				}
			else
			{
					die("0"); 	// No se informó el periodo
				}
			}
			break;

		case 'generaddjj':

			$res = $inst_ddjj->getVersion($_SESSION['grupo'], $_POST['periodo']);

			if ($res)
		{
				$version = $res[0] + 1;
			}
		else
		{
				$version = 1;
			}

			$data = $inst_ddjj->insertarBackupDdjj($_SESSION['grupo'], $_SESSION['id_usuario'], $_POST['periodo'], $_POST['fecha_backup'], htmlentities($_POST['nombre_backup'], ENT_QUOTES, 'UTF-8'), $version);

			if ($data)
		{
				die("1");
			}
		else
		{
				die("0");
			}
			break;

		case 'reimprimir':

			$data = $inst_ddjj->insertarMotivoReimpresionDdjj(htmlentities($_POST['motivo_reimpresion'], ENT_QUOTES, 'UTF-8'), $_SESSION['grupo'], $_POST['periodo'], $_SESSION['grupo'], $_POST['periodo']);

			if ($data)
		{
				die('1');
			}

			break;
	}
}

?>