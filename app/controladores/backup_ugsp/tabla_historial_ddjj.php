<?php

require_once '../../../init.php';

$inst_backup = new DdjjBackup();
$inst_sirge  = new Sirge();

$id_provincia = $_POST['provincia'];

if (isset($id_provincia))
{
	$data = $inst_backup->getHistorialDdjjProvincia($id_provincia);

	//echo "<pre>", print_r($data), "</pre>";

	if (count($data))
	{
		echo $inst_sirge->jsonDT($data, false);
	}
	else
	{
		$data['iTotalRecords'] = 0;
		$data['iTotalDisplayRecords'] = 0;
		die(json_encode($data));
	}
}

?>