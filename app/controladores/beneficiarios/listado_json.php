<?php

require_once '../../../init.php';

$b = new Beneficiarios();
echo $b->listar($_POST);
