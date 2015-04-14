<?php

require '../../init.php';

$f = new PdfDdjjBackup();
$p = new Ddjj();

$id = isset($_GET['id_impresion']) ? $_GET['id_impresion'] : null;

$f->SetLeftMargin(25);
$f->AliasNbPages();
$f->AddPage();
if (is_null($id))
{
	$id = $f->ddjjImpBackupGen($_GET['fecha_backup'], $_GET['nombre_backup'], $_GET['periodo'], $_SESSION['grupo']);
}

$f->ddjjImpBackup($id);

$f->Output("DDJJ_BACKUP.pdf", 'D');
//$f->Output();
?>
