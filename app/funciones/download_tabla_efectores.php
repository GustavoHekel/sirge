<?php
$ruta = $_REQUEST['file'];

$zip = new ZipArchive();
$nombre = 'tabla_efectores.zip';

if ($zip->open ('../../data/padrones/' . $nombre , ZIPARCHIVE::CREATE) === true) {
  $zip->addFile ($ruta ,'tabla_efectores.csv');
  $zip->close();

  if (! unlink ($ruta)) {
      die ("ERROR_2");
  }

  if (file_exists ('../../data/padrones/' . $nombre)){
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename('../../data/padrones/' . $nombre));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('../../data/padrones/' . $nombre));
    readfile('../../data/padrones/' . $nombre);
    exit;
  } else {
      die ("ERROR_1");
  }
}