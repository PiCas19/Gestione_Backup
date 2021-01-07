<?php
	
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	require_once "vendor/autoload.php";
	
	class Mail_Model {
		//Oggetto PHPMailer
		private $mail = "";
	
		public function __construct()
		{
			//Creo un nuovo oggetto PHPMailer
			$this->mail = new PHPMailer(true);
		}
	
		/**
		 * Permette di inviare le email.
		 */
		public function send($recivement, $nameRecivement, $errorMsg, $database){
			try {
				//Indirizzo email e nome 
				$this->mail->From = "system@gestione-backup.com";
				$this->mail->FromName = "Gestione backup";
				
				//Charset UTF-8
				$this->mail->CharSet = 'UTF-8';
				
	
				//Aggiungo l'email destinatario
				$this->mail->addAddress($recivement, $nameRecivement);
	
				$this->mail->isHTML(true);
	
				//Imposto l'oggetto dell'email
				$this->mail->Subject = 'Errore backup';
				
			
				$mailContent = "
					<html>
						<head>
							<titl>Errore backup</title>
						</head>
						<body>
							<p>Si è verificato un errore durante il backup del database ". $database .".</p><br>
							<p><b>Errore:</b></p>
							<p>".$errorMsg."<p><br>
							<p>Per ulteriori informazioni contattare: system@gestione-backup.com</p>
						</body>
					</html>";
				$this->mail->Body = $mailContent;
				
				//Invio l'email
				$this->mail->send();
			}
			catch (Exception $e)
			{
				/* PHPMailer exception. */
				echo $e->errorMessage();
			}
			catch (\Exception $e)
			{
				/* PHP exception (note the backslash to select the global namespace Exception class). */
				echo $e->getMessage();
			}
		}
		
	}

?>