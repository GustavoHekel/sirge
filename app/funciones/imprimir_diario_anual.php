<?php

require '../../init.php';

$f = new PdfDdjjBackup();
$p = new Ddjj();

$f->SetLeftMargin(25);
$f->AliasNbPages();
$f->AddPage();

//$f->ddjjImpBackup($id);

$f->SetDrawColor(153, 204, 255);
$f->SetLineWidth(0.7);
$f->Rect(10, 20, 190, 90, 'D');

$f->SetY(33);
$f->SetX(20);
$f->SetTextColor(51, 153, 255);
$f->SetFont('Arial', 'B', 12);
$f->Cell(0, 0, "CONSTANCIA DE INSCRIPCION", 0, 1);
$f->SetY(39);
$f->SetX(20);
$f->Cell(0, 0, "PARA EL BENEFICIARIO", 0, 1);

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
$f->Cell(0, 0, $apellidos, 0, 1);
$ylinea = 57;
$f->Line(20, $ylinea, 190, $ylinea);
$y += 10;
$ylinea += 10;
$f->SetY($y);
$f->SetX(21);
$f->SetFont('Arial', '', 11);
$f->SetTextColor(90, 90, 90);
$f->Cell(0, 0, "Nombres: ", 0, 1);
$f->SetX(67);
$f->SetFont('Arial', 'B', 11);
$f->SetTextColor(55, 55, 55);
$f->Cell(0, 0, $nombres, 0, 1);
$f->Line(20, $ylinea, 190, $ylinea);
$y += 10;
$ylinea += 10;
$f->SetY($y);
$f->SetX(21);
$f->SetFont('Arial', '', 11);
$f->SetTextColor(90, 90, 90);
$f->Cell(0, 0, "Tipo de Documento: ", 0, 1);
$f->SetX(67);
$f->SetFont('Arial', 'B', 11);
$f->SetTextColor(55, 55, 55);
$f->Cell(0, 0, $tipo_documento, 0, 1);

$f->SetX(110);
$f->SetFont('Arial', '', 11);
$f->SetTextColor(90, 90, 90);
$f->Cell(0, 0, "Numero de Documento: ", 0, 1);
$f->SetX(157);
$f->SetFont('Arial', 'B', 11);
$f->SetTextColor(55, 55, 55);
$f->Cell(0, 0, $numero_documento, 0, 1);

$f->Line(20, $ylinea, 190, $ylinea);

$y += 10;
$ylinea += 10;
$f->SetY($y);
$f->SetX(21);
$f->SetFont('Arial', '', 11);
$f->SetTextColor(90, 90, 90);
$f->Cell(0, 0, "Clave de Beneficiario: ", 0, 1);
$f->SetX(67);
$f->SetFont('Arial', 'B', 11);
$f->SetTextColor(55, 55, 55);
$f->Cell(0, 0, $clave_beneficiario, 0, 1);

$f->SetX(110);
$f->SetFont('Arial', '', 11);
$f->SetTextColor(90, 90, 90);
$f->Cell(0, 0, "Fecha de Inscripcion: ", 0, 1);
$f->SetX(157);
$f->SetFont('Arial', 'B', 11);
$f->SetTextColor(55, 55, 55);
$f->Cell(0, 0, $fecha_ins, 0, 1);

$f->Line(20, $ylinea, 190, $ylinea);

$y += 10;
$ylinea += 10;
$f->SetY($y);
$f->SetX(21);
$f->SetFont('Arial', '', 11);
$f->SetTextColor(90, 90, 90);
$f->Cell(0, 0, "Provincia: ", 0, 1);
$f->SetX(67);
$f->SetFont('Arial', 'B', 11);
$f->SetTextColor(55, 55, 55);
$f->Cell(0, 0, $provincia, 0, 1);

$f->SetX(110);
$f->SetFont('Arial', '', 11);
$f->SetTextColor(90, 90, 90);
$f->Cell(0, 0, "CUIE: ", 0, 1);
$f->SetX(157);
$f->SetFont('Arial', 'B', 11);
$f->SetTextColor(55, 55, 55);
$f->Cell(0, 0, $cuie, 0, 1);

//$f->Output("DDJJ_BACKUP.f", 'D');
$f->Output();
?>