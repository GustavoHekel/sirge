<?php

require '../../init.php';

$f = new PDF();
$p = new Padron();

$id = isset ($_GET['id_impresion']) ? $_GET['id_impresion'] : null ;

if (is_null ($id)) {
	$id = $p->LotesPendientesDDJJ($_GET['id_padron'] , true);
}

$f->SetLeftMargin(25);
$f->AliasNbPages();
$f->AddPage();
$f->DDJJSIRGe($id);
$f->Output("DDJJ_GRUPAL.pdf",'D');

?>
