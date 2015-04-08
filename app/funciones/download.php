<?php
  $ruta = $_REQUEST['file'];

 if (file_exists($ruta)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($ruta));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($ruta));
    readfile($ruta);
    exit;
  }