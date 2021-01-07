<?php
    class Utente{


        /**
         * Permette di creare l'index della pagina utente.
         */
        function __construct()
        {
        }

        /*
        * Permette di creare l'index della pagina visualizza utenti
        */
        public function index(){

            require './application/views/header.php';

            require_once './application/models/database_model.php';
            require_once './application/models/utente_model.php';

            $db = new Database_Model();
            $conn = $db->getConnection();

            $utente = new Utente_Model();

            //Permette di fare il reset degli id degli utenti
            $utente->resetIdUser($conn);

            //Leggo i dati sulla tabella utenti
            $sth = $utente->getUtenti($conn);

            require './application/views/utente/index.php';
            require './application/views/footer.php';
        }
        
        /*
         * Permette di aggiungere un nuovo utente
         */
        public function addUsers(){

            require_once './application/controller/aggiungi_utente.php';
            $utente = new Aggiungi_Utente();
            $utente->index();

        }
        
        /**
         * Permette di modificare i permessi di un determinato utente
         */
        public function changeType(){
            $id =  $this->test_input($_POST["index"]);
            $type = $this->test_input($_POST["value"]);
            require './application/models/database_model.php';
            require './application/models/utente_model.php';

            $db = new Database_Model();
            //connessione al database
            $conn = $db->getConnection();
            $utente = new Utente_Model();
            //modifico il tipo dell'utente
            $utente->updateTypeUser($conn, $id,$type);

            header('Location: ' . URL .'utente');
        }

        /**
         * Permette di eliminare un utente specifico
         */
        public function deleteUsers($id){
            require_once './application/models/database_model.php';
            require_once './application/models/utente_model.php';


            $db = new Database_Model();
            //Mi collego al database gestione_backup
            $conn = $db->getConnection();
            $utente = new Utente_Model();
            //elimino un utente
            $utente->deleteUser($conn, $id);
            header('Location: '.URL.'utente');

        }
        
        
        /**
         * Visualizza la pagina impostazioni account.
         */
        public function editProfile(){
            require './application/views/header.php';
            require './application/views/utente/edit_profile.php';
            require './application/views/footer.php';
            
        }
        
        /**
         * Permette d caricare l'immagine
         */
        public function uploadImage(){
            session_start();
            //percorso della cartella dove sono presenti le immagini di profilo degli utenti
            $path_dir_img = "application/sources/img/users/";  
            if (!empty($_FILES)) {
              //immagine temporanea appena caricato nel dropzone.
              $temp_file = $_FILES['file']['tmp_name'];  
              //percorso dell'immagine del profilo
              $path_img =  $path_dir_img . $_SESSION["id"] . ".png";;
              //creo una sessione con l'immagine temporanea.
              $_SESSION['temp_immagine'] = $_SESSION["id"] . ".png";
              //sposto l'immagine temporanea nella cartella users.
              move_uploaded_file($temp_file[0],$path_img);
            }
        }
        
        /**
         * Permette di salvare le impostazioni del account.
         */
        public function saveSettings(){
            //faccio partire le sessioni
            session_start();
            require './application/models/utente_model.php';
            require './application/models/database_model.php';
            require './application/models/login_model.php';
            
            $password = $newPassword = $nome = $cognome = $immagine  = $email = $username = " ";
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                //se l'utente ha clicca il pulsante cancella
                if(isset($_POST["cancella"])){
                    unset($_SESSION['temp_immagine']);
                    //ritorno alla pagina che permette di modificare l'account.
                    header('Location: '.URL.'utente/editProfile');
                }
                else{
                    //pattern della password
                    $patternPassword = "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/";
                    //pattern del nome e del cognome
                    $pattern = "/^[A-Z]+[a-z]{0,}/";
                    
                    $database = new Database_Model();
                    //creo una connessione al database
                    $conn = $database->getConnection();
                    $login = new Login_Model();
                    $utente = new Utente_Model();
                    
                    //se carico solo l'immagine
                    if(isset($_SESSION["temp_immagine"])){
                        $_SESSION["immagine"] = $_SESSION["temp_immagine"]; 
                        $immagine = $_SESSION["temp_immagine"];   
                        unset($_SESSION["temp_immagine"]);
                    }
                    else{
                        $immagine  = $_SESSION['immagine'];
                    }
                     
                    //se modifico solo il nome
                    if(isset($_POST["nome"]) && preg_match($pattern, $_POST["nome"]) ){
                        //aggiorno solo il nome dell'utente
                        $nome = $this->test_input($_POST["nome"]);
                        $_SESSION["nome"] = $nome;
                    }
                    else{
                        $nome = $_SESSION["nome"];
                    }
                    
                    //se modifico solo cognome
                    if(isset($_POST["cognome"]) &&   preg_match($pattern, $_POST['cognome'])){
                        //aggiorno solo il nome dell'utente
                        $cognome = $this->test_input($_POST["cognome"]);
                        //modifico la sessione cognome
                        $_SESSION["cognome"] = $cognome;
                        
                    }
                    else{
                        $cognome = $_SESSION["cognome"];
                    }
                    
                    
                    //se modifico solo l'email
                    if(isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                        $email = $this->test_input($_POST["email"]);
                        //modifico la sessione email
                        $_SESSION['email'] = $email;
                    }
                    else{
                        $email = $_SESSION['email'];
                    }
                    
                    //se modifico solo lo username
                    if(isset($_POST["username"])){
                        //aggiorno solo il nome dell'utente
                        $username = $this->test_input($_POST["username"]); 
                        
                        $result =  $login->checkUsername($conn, $username);
                        
                        //pattern  username
                        $patternUsername = "/[a-z]{0,9}\.[a,z]{0,9}/";
                        
                        if($result == null && preg_match($patternUsername, $username)){
                            //modifico sessione username
                            $_SESSION["username"] = $username;    
                        }
                        else{
                            $username = $_SESSION["username"];
                        }
                    }
                    else{
                        $username = $_SESSION["username"];
                    }
                                
                    
                    //se modifico solamente la password
                    if(isset($_POST['password']) && isset($_POST["newPassword"])){
                        $password = $this->test_input($_POST["password"]);
                        //verifico che la password inserito nel campo "password attuale"
                        //corrisponde alla password dell'utente.
                        $result = $login->getUtente($conn, $username, $password,"username");
                        if(($result && preg_match($patternPassword, $newPassword))){
                            $pswd = new Password();
                            //nuova password codificata
                            $newPassword = $pswd->encode($this->test_input($_POST["newPassword"]));
                        }
                        else{
                            //mantengo la password corrente
                            $currentPassword = $utente->getUserById($conn, $_SESSION['id']);
                            $newPassword = $currentPassword['pswd'];
                        }
                    }
                    else{
                        //mantengo la password corrente
                        $currentPassword = $utente->getUserById($conn, $_SESSION['id']);
                        $newPassword = $currentPassword['pswd'];
                    }
                    //aggiorno l'account dell'utente
                    $utente->updateProfilo($conn, $immagine, $nome, $cognome, $email, $username, $newPassword, $_SESSION['id']);  
                    //ritorno alla pagina che permette di modificare l'account.
                    header('Location: '.URL.'utente/editProfile');             
                }
            }
        }
        
        /**
         * Permette di uscire dalla pagina impostazioni account
         */
        public function exitEditProfile(){
            unset($_SESSION["temp_immagine"]);
            header("Location: ".URL."utente");
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