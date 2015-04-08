<?php

require_once '../../../init.php';

$iEfector = new Efectores();

$e = $iEfector->getEfector($_POST['id_efector']);
$g = $iEfector->getEfectorGeo($_POST['id_efector']);
$c = $iEfector->getEfectorCompromiso($_POST['id_efector']);
$a = $iEfector->getEfectorConvenio($_POST['id_efector']);
$d = $iEfector->getEfectorDescentralizacion($_POST['id_efector']);

$Html = array (
  '../../vistas/efectores/detalle_efector.html'
);

$diccionario = array (
  'PRESTACIONES_REPORTADAS' => $iEfector->getPrestaciones($_POST['id_efector']),
  'BENEFICIARIOS_INSCRIPTOS' => $iEfector->getBeneficiariosInscriptos($_POST['id_efector']),
  'PRESTACIONES_PRIORIZADAS' => $iEfector->getPrestacionesPriorizadas($_POST['id_efector']),
  'PRESTACIONES_CEB' => $iEfector->getBeneficiariosCeb($_POST['id_efector']),
  'NOMBRE' => $e['nombre'],
  'CUIE' => $e['cuie'],
  'SIISA' => $e['siisa'],
  'DOMICILIO' => $e['domicilio'],
  'CP' => $e['codigo_postal'],
  'TIPO_EFECTOR' => $e['tipo_efector'],
  'CATEGORIZACION' => $e['tipo_categorizacion'],
  'PPAC' => $e['ppac'],
  'SUMAR' => $e['sumar'],
  'PRIORIZADO' => $e['priorizado_sumar'],
  'PROVINCIA' => $g['provincia'],
  'DEPARTAMENTO' => $g['departamento'],
  'LOCALIDAD' => $g['localidad'],
  'NUMERO' => $c['numero_compromiso'],
  'FIRMANTE' => $c['firmante'],
  'PAGO_INDIRECTO' => $c['pago_indirecto'],
  'F_SUS' => $c['fecha_suscripcion'] ,
  'F_INI' => $c['fecha_inicio'] ,
  'F_FIN' => $c['fecha_fin'],
  'NUMERO_CA' => $a['numero_compromiso'],
  'FIRMANTE_CA' => $a['firmante'],
  'F_SUS_CA' => $a['fecha_suscripcion'],
  'F_INI_CA' => $a['fecha_inicio'],
  'F_FIN_CA' => $a['fecha_fin'],
  'NOM_TER_ADMIN' => $a['nombre_tercer_administrador'],
  'COD_TER_ADMIN' => $a['codigo_tercer_administrador'],
  'REFERENTE' => $iEfector->getEfectorReferente($_POST['id_efector']),
  'INTERNET' => $d['internet'],
  'FDESC' => $d['factura_descentralizada'],
  'FONLI' => $d['factura_on_line'],
  'TELEFONO' => Bdd::getInstance()->find('numero_telefono' , 'efectores.telefonos' , ['id_efector = ?' , [$_POST['id_efector']]]),
  'EMAIL' => Bdd::getInstance()->find('email' , 'efectores.email' , ['id_efector = ?' , [$_POST['id_efector']]]),
);

Html::vista($Html , $diccionario);
