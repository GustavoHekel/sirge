<?php

require '../../init.php';

$f = new PdfDdjjBackup();
$p = new Ddjj();

$inst_ddjj        = new DdjjBackup();
$inst_sirge       = new Sirge();
$nombre_provincia = $inst_sirge->getNombreProvincia($_GET['id_provincia']);
$data             = $f->getBackupsAño($_GET['id_provincia'], $_GET['year']);
$id_provincia     = $_SESSION['grupo'];
//$id_provincia = '04'; //uso para probar

$f->TablaSimple($data, $id_provincia);

//$f->Output("DDJJ_BACKUP.f", 'D');
$f->Output();
?>