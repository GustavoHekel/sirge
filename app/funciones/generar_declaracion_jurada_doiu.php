<?php

require '../../init.php';

$f = new PdfDdjjDoiu();
$p = new Ddjj();

$grupo = $_SESSION['grupo'];

$f->SetLeftMargin(15);
$f->AliasNbPages();
$f->AddPage();

if (isset($_GET['id_impresion'])) {
	$f->ddjjImpDoiu($_GET['id_impresion']);
}

$periodo = "";

if (isset($_GET['periodo'])) {
	$periodo .= "_" . $_GET['periodo'];
}

$f->Output("DDJJ_" . $_SESSION['grupo'] . $periodo . ".pdf", 'D');
//$f->Output();
?>
