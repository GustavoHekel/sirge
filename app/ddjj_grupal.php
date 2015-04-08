<?php

require '../init.php';

$f = new PdfDdjjSirge();
$p = new Ddjj();

$id = isset ($_GET['id_impresion']) ? $_GET['id_impresion'] : null ;

if (is_null ($id)) {
	$id = $p->listarPendientes($_GET['id_padron'] , true);
}

$f->SetLeftMargin(25);
$f->AliasNbPages();
$f->AddPage();
$f->ddjjSirge($id);
$f->Output("DDJJ_GRUPAL.pdf",'D');
