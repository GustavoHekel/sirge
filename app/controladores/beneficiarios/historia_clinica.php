<?php

require_once '../../../init.php';

$benef = new Beneficiarios();

$data = $benef->getDataBeneficiarioDNI($_POST['dni_paciente']);

$Html = ['../../vistas/beneficiarios/historia_clinica.html'];

$diccionario = [
    'NOMBRE_PACIENTE' => $data['nombre'] . ' ' . $data['apellido'] ,
    'NOMBRE' => $data['nombre'],
    'APELLIDO' => $data['apellido'],
    'DNI' => $data['numero_documento'],
    'CLAVE_BENEFICIARIO' => $data['clave_beneficiario'],
    'NACIMIENTO' => $data['fecha_nacimiento'],
    'INSCRIPCION' => $data['fecha_inscripcion'],
    'EDAD' => $data['edad'],
    'SEXO' => $data['sexo'],
    'PROVINCIA' => '',
    'DEPARTAMENTO' => '',
    'LOCALIDAD' => '',
    'CALLE' => '',
    'CODIGO_POSTAL' => '',
//    'MATRIZ' => $benef->matriz($_POST['dni_paciente'])
];

Html::vista($Html , $diccionario);