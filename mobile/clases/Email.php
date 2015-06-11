<?php

class Email {
	
	private $_mail;
	
	public function __construct(){
		$this->_mail = new PHPMailer;
		$this->_mail->isSMTP();
		$this->_mail->SMTPDebug = 0;
		$this->_mail->Debugoutput = 'html';
		$this->_mail->Host = 'smtp.gmail.com';
		$this->_mail->Port = 587;
		$this->_mail->SMTPSecure = 'tls';
		$this->_mail->SMTPAuth = true;
		$this->_mail->Username = "sirgeweb@gmail.com";
		$this->_mail->Password = "riv@davia875";
		$this->_mail->setFrom('sirgeweb@gmail.com', 'Programa SUMAR');
	}
	
	public function enviarValidacion($correo , $asunto , $cuerpo , $uniqueid){
		
		$html = [$cuerpo];
		$diccionario = [
			'EMAIL' => $correo,
			'HASH' => $uniqueid
		];
		
		$template = Html::vista($html , $diccionario);
		
		$this->_mail->addAddress($correo);
		$this->_mail->Subject = html_entity_decode($asunto);
		$this->_mail->msgHTML($template);
		$this->_mail->AltBody = 'This is a plain-text message body';
		$this->_mail->send();
	}
	
	public function enviarProblema ($cuerpo , $correo = 'gdhekel@gmail.com' , $asunto = 'Reporte problema App SUMAR'){
		$this->_mail->addAddress($correo);
		$this->_mail->Subject = html_entity_decode($asunto);
		$this->_mail->msgHTML($cuerpo);
		$this->_mail->AltBody = 'This is a plain-text message body';
		$this->_mail->send();	
	}
}
