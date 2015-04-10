<?php

require '../../init.php';

$f = new PdfDdjjBackup();
$p = new Ddjj();

$id = isset($_GET['id_impresion']) ? $_GET['id_impresion'] : null;

if (is_null($id))
{
	return null;
}

$f->SetLeftMargin(25);
$f->AliasNbPages();
$f->AddPage();
$f->ddjjBackup($id);
$f->Output("DDJJ_BACKUP.pdf", 'D');
?>
