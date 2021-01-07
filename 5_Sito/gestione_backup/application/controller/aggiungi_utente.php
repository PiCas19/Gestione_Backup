<?php
    class Aggiungi_Utente{

        function __construct()
        {
        }

       /*
        * Permette di creare l'index della pagina visualizza utenti
        */
        public function index(){    
            require './application/views/header.php';
            require './application/views/aggiungi_utente/index.php';
        }

        /**
         * Permette di verificare il form che crea un nuovo utente.
         */
        public function checkAddUsers(){
            session_start();
            $nome = $cognome = $pswd = $email = $tipo = "";   
            
            $patternPassword = "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/";
            $pattern = "/^[A-Z]+[a-z]{0,}/";         
            
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                
                //il campo nome deve essere obbligatorio
                if(!empty($_POST["nome"])){
                    //verifico il campo nome 
                    if(preg_match($pattern, $_POST['nome'])){
                        $nome = $this->test_input($_POST["nome"]);
                    }
                    else{
                        $_SESSION['nameErr'] = "La prima lettera deve essere maiuscole e le altre minuscole";
                    }
                }
                else{
                    $_SESSION['nameErr'] = "Il campo nome è obbligatorio";
                }
                
                //il campo cognome deve essere obbligatorio
                if(!empty($_POST["cognome"])){
                    //verifico il campo cognome
                    if(preg_match($pattern, $_POST['cognome'])){
                       $cognome = $this->test_input($_POST["cognome"]);
                    }
                    else{
                       $_SESSION['surnameErr'] = "La prima lettera deve essere maiuscole e le altre minuscole";
                    }
                }
                else{
                   $_SESSION['surnameErr'] = "Il campo cognome è obbligatorio";
                }
               
                //il campo email deve essere obbligatorio
                if(!empty($_POST["email"])){
                   //verifico il campo email
                    if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                      $email = $this->test_input($_POST['email']);
                    }
                    else{
                      $_SESSION['emailErr'] = "Formato email sbagliato.";
                    }
                }
                else{
                    $_SESSION['emailErr'] = "Il campo email è obbligatorio";
                }
                
                //il radio button tipo deve essere obbligatorio
                if(!empty($_POST["type"])){
                    $tipo = $this->test_input($_POST["type"]);
                }
                else{
                    $_SESSION['typeErr'] = "Il radio button tipo è obbligatorio";
                }
                
                //il campo password deve essere obbligatorio
                if(!empty($_POST["password"])){
                    //verifico la password
                    if(preg_match($patternPassword, $_POST["password"])){
                        $pswd = $this->test_input($_POST["password"]);
                    }
                    else{
                        $_SESSION['passwordErr'] = "Deve contenere almeno un numero e una lettera maiuscola e minuscola e almeno 8 caratteri.";
                        header('Location: '.URL.'aggiungi_utente');
                    }
                }
                else{
                    $_SESSION['passwordErr'] = "Il campo password è obbligatorio";
                }
                
                //se ci sono almeno degli errori vengono stampati 
                // i messaggi errori che vengono slavate nelle varie sessioni
                if(isset($_SESSION['typeErr']) || isset($_SESSION['passwordErr']) ||
                    isset($_SESSION['emailErr']) || isset($_SESSION['surnameErr']) ||
                    isset($_SESSION['nameErr'])){
                    header('Location: '.URL.'aggiungi_utente');
                }
                else{
                
                    //Connessione al database gestione_backup
                    require './application/models/database_model.php';
                    $db = new Database_Model();
                    $conn = $db->getConnection();
                    require './application/models/utente_model.php';
                    $utente = new Utente_Model();
                    //Controllo che nel database esiste già un utente con la stessa email
                    $result = $utente->getUserByEmail($conn, $email);
                    echo var_dump($result);
                    //Non ci possono esserci degli utenti con la stessa email
                    if($result === false){
                        require './application/models/password.php';
                        require './application/models/mail.php';
                        $password = new Password();
                        $utente->addUser($conn, $nome, $cognome,"", $password->encode($pswd), $email, $tipo);
                        
                        //Invio un email
                        $mail = new Mail();
                        $mail->send($email, $nome." ".$cognome, $pswd);
                        
                        header('Location: '.URL.'utente');
                    }
                    else{
                        $_SESSION['accountErr'] = "Esiste già un utente con la stessa email";
                        //L'utente deve ricompilare il form
                        header('Location: '.URL.'aggiungi_utente');
                    }
                }
            }
            else{
                header('Location: '.URL.'aggiungi_utente');
            }
        }

        /**
         * Permette di ritornare alla pagina utente.
         */
        public function backViewsUsers(){
            header('Location: '.URL.'utente');
        }

       /**
        * Permette di controllare il dato inserito sia corretto
        */ 
        private function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

    }


?>