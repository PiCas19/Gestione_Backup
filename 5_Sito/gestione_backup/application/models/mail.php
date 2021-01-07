<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "./application/sources/vendor/autoload.php";

class Mail
{   
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
    public function send($recivement, $nameRecivement, $passwordUser){
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
            $this->mail->Subject = 'Account Gestione Backup';
            
        
            $mailContent = "
                <html>
                    <head>
                        <titleCredenziali account</title>
                    </head>
                    <body>
                        <h2>Benvenuto " . $nameRecivement . " su Gestione Backup</h2>
                        <p>L'applicativo <b>Gestione Backup</b> permette di gestire dei backup dei siti produttivi della SAMT.</p>
                        <p>Per accedere al sito e modificare la password, cliccare il seguente link: <a href='http://www.samtinfo.ch/i17caspie/gestione_backup/'>Gestione Backup</a></p>
                        <p>Email: ".$recivement."</p>
                        <p>Password provvisoria: ".$passwordUser."</p>
                        <p>Per ulteriori informazioni o problemi contattare: system@gestione-backup.com</p>
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