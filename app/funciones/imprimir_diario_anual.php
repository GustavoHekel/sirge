<?php

require '../../init.php';

$f = new PdfDdjjBackup();
$p = new Ddjj();

$inst_ddjj = new DdjjBackup();
$data      = $f->getBackupsAño($_GET['id_provincia'], $_GET['year']);

$f->SetLeftMargin(25);
$f->AliasNbPages();
$f->AddPage();

//$f->ddjjImpBackup($id);

$f->SetFont('Arial', '', 14);

$f->SetDrawColor(153, 204, 255);
$f->SetLineWidth(0.7);
$f->Rect(10, 20, 190, 90, 'D');
//Table with 20 rows and 4 column

$f->SetY(33);
$f->SetX(20);
$f->SetTextColor(51, 153, 255);
$f->SetFont('Arial', 'B', 12);
$f->Cell(0, 0, "BACKUPS " . $_GET['id_provincia'] . " DEL " . $_GET['year'], 0, 1);
$f->SetY(39);
$f->SetX(20);

$y = 45;
$f->SetY($y);
$f->SetX(50);
$f->SetDrawColor(225, 225, 225);
$f->SetLineWidth(0.3);
$f->Rect(20, 48, 170, 49, 'D');
$f->SetTextColor(90, 90, 90);
$f->SetFont('Arial', '', 11);

$y += 7;
$f->SetY($y);
$f->SetX(21);
$f->Cell(0, 0, "Apellidos: ", 0, 1);
$f->SetX(67);
$f->SetFont('Arial', 'B', 11);
$f->SetTextColor(55, 55, 55);
$f->Cell(0, 0, "hola", 0, 1);
$ylinea = 57;
$f->Line(20, $ylinea, 190, $ylinea);

//$f->Output("DDJJ_BACKUP.f", 'D');
$f->Output();
?>