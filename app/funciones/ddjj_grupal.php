<?php

require '../../init.php';

$f = new PDF();
$p = new Padron();

$f->SetLeftMargin(25);
$f->AliasNbPages();
$f->AddPage();
$p->ImpresionDDJJSIRGe($f->DDJJSIRGe($_GET['id_padron']));
$f->Output("DDJJ_GRUPAL.pdf",'D');

?>
