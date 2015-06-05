<?php

if (md5($_POST['dato']) == $_POST['dato_encriptado']) {
	echo "true";
} else {
	echo "false";
}

?>