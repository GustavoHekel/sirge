<?php
require_once "phpmailer/class.phpmailer.php";

class Email {

	public function enviar_mail($datos)
	{
		if ( ! isset($datos['emailFrom']))
		{
			$datos['emailFrom'] = "sirgeweb@gmail.com";
		}
		if ( ! isset($datos['subject']))
		{
			$datos['subject'] = "";
		}
		if ( ! isset($datos['message']))
		{
			$datos['message'] = "";
		}

		if (isset($_POST) || is_array($datos['emailTo']))
		{
			$datos = array_merge($datos, $_POST);

			$mailTo   = $datos['emailTo'];
			$mailFrom = $datos['emailFrom'];
			$subject  = $datos['subject'];
			$fecha    = $datos['fecha'];
			$message  = wordwrap($datos['message'], 70);

			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = "tls";
			                                //$mail->CharSet  = 'UTF-8';
			$mail->Host = "smtp.gmail.com"; // SMTP server example
			$mail->Port = 587;              // set the SMTP port for the GMAIL server
			$mail->SetLanguage("es", 'language/');
			$mail->Username = "sirgeweb@gmail.com"; // SMTP account username example
			$mail->Password = "riv@davia875";       // SMTP account password example
			$mail->SetFrom = $mailFrom;
			$mail->FromName = "De: ".$mailFrom." (".$subject.")";

			if (is_array($mailTo))
			{
				for ($i = 0; $i < count($mailTo); $i++)
				{
					$mail->addAddress($mailTo[$i]);
				}
			}
			else
			{
				$mail->addAddress($mailTo);
			}

			//$mail->addAddress('ellen@example.com, "Roberto"');
			//$mail->addReplyTo($mailFrom, 'Information');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');
			$mail->Subject = $subject;

			$html = ["http://".$_SERVER['HTTP_HOST']."/sirge2/app/vistas/contacto/contexto_mail.html"];

			if (isset($datos['ajax']))
			{
				$ruta_logo_sumar = "../public/img/sumar-grande.png";
			}
			else
			{
				$ruta_logo_sumar = "../../../public/img/sumar-grande.png";
			}

			$diccionario = [
				'MAIL'  => $mailFrom,
				'FECHA' => $fecha,
				'NOMBRE_FROM' => $subject,
				'BODY'  => $message,
				'RUTA_LOGO_SUMAR' => $ruta_logo_sumar];

			$mail->isHTML(true);
			$mail->msgHTML(Html::vista($html, $diccionario, false));

			//$mail->Body = $message;

			//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			if ( ! $mail->Send())
			{
				echo 'Message could not be sent.\n';
				echo 'Mailer Error: '.$mail->ErrorInfo;
			}
			else
			{
				if (isset($datos['ajax']))
				{
					return;
				}
				else
				{
					echo "true";
				}
			}
		}
	}
}
?>