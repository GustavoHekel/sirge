<?php
require_once "phpmailer/class.phpmailer.php";

class Email {

	public function enviar_mail($datos) {

		$datos['emailTo']   = "";
		$datos['emailFrom'] = "";
		$datos['subject']   = "";
		$datos['message']   = "";

		if (isset($_POST)) {
			$datos = array_merge($datos, $_POST);

			$mailTo   = $datos['emailTo'];
			$mailFrom = $datos['emailFrom'];
			$subject  = $datos['subject'];
			$fecha    = $datos['fecha'];
			$message  = wordwrap($datos['message'], 70);

			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPDebug  = 0; // enables SMTP debug information (for testing)
			$mail->SMTPAuth   = true;
			$mail->SMTPSecure = "tls";
			                                //$mail->CharSet  = 'UTF-8';
			$mail->Host = "smtp.gmail.com"; // SMTP server example
			$mail->Port = 587;              // set the SMTP port for the GMAIL server
			$mail->SetLanguage("es", 'language/');
			$mail->Username = "sirgeweb@gmail.com"; // SMTP account username example
			$mail->Password = "riv@davia875";       // SMTP account password example
			$mail->SetFrom  = $mailFrom;
			$mail->FromName = "De: " . $mailFrom . " (" . $subject . ")";
			$mail->addAddress($mailTo);
			//$mail->addAddress('ellen@example.com, "Roberto"');
			//$mail->addReplyTo($mailFrom, 'Information');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');
			$mail->Subject = "Contacto - " . $subject;

			$html = ['../../vistas/contacto/contexto_mail.html'];

			$diccionario = [
				'MAIL'        => $mailFrom,
				'FECHA'       => $fecha,
				'NOMBRE_FROM' => $subject,
				'BODY'        => $message];

			//echo Html::vista($html, $diccionario);
			$mail->isHTML(true);
			$mail->msgHTML(Html::vista($html, $diccionario, false));

			//$mail->Body = $message;

			//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			if (!$mail->Send()) {
				echo 'Message could not be sent.\n';
				echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				echo "true";
			}
		}
	}
}

?>