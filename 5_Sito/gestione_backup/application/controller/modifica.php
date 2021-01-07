<?php
    class Modifica {


        //identificativo del collegamento
        private $id;


        /*
         * Permette di settare l'identificativo del collegamento
         */ 
        public function setId($id){
            $this->id = $id;
        }


        function __construct()
        {
        }

        /*
         * Permette di creare l'index della pagina modifica
         */
        public function index(){

            require './application/models/database_model.php';
            require './application/models/backup_model.php';
            $db = new Database_Model();
            //Mi collego al database gestione_backup
            $conn = $db->getConnection();
            $db_link = new Backup_Model();
            //Leggo i dati di un determinato collegamento 
            $result = $db_link->getDataDbLinkById($conn, $this->id);
            require './application/views/header.php';
            require './application/views/modifica/index.php';
            require './application/views/footer.php';
        }

        /**
         * Permette di aggiornare i campi di un collegamento
         */
        public function modify($id){
            
            session_start();
            
            if (!isset($_SESSION['username'])) {
                header('Location: '.URL);
                exit;
            }
            $hostname = $dbname = $username = $pass =  "";
            //l'hostname può essere solamente efof.myd.infomaniak.com
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                if($_POST['dbhost'] == DBHOST){
                    $hostname = $this->test_input($_POST['dbhost']);
                    $username = $this->test_input($_POST['dbuser']);
                    $pass = $this->test_input($_POST['dbpass']);
                    $dbname = $this->test_input($_POST['dbname']);

                    require './application/models/database_model.php';
                    require './application/models/password.php';
                    require './application/models/backup_model.php';

                    $password = new Password();
                    $db_link = new Backup_Model();
                    $db = new Database_Model();
                    
                    //Creo una connessione al database gestione_backup
                    $conn = $db->getConnection();
                    
                    //Leggo la password di un determinato collegamento rispetto allo username
                    $pswd = $db_link->getDbLinkPassword($conn, $username);
                    
                    if($password->verify($pass, $pswd['dbpass'])){
                        
                        //Cambio il nome del db, host, username e password con cui mi collego (serve per vedere se esiste il db).
                        //Setto questi dati con i dati che ho inserito nei campi del form.
                        $db->setDbname($dbname);
                        $db->setDbhost($hostname);
                        $db->setUser($username);
                        $db->setPass($pswd['pswd']);

                        //Provo a fare una connessione al database 
                        $connDbLink = $db->getConnection();

                        //Se la connessione è andato a buon fine
                        if($connDbLink){
                            //Modifico il collegamento 
                            $db_link->updateDataDbLink($conn, $hostname, $username, $pswd['dbpass'], $dbname);
                            if($db_link){
                                header('Location: ' .URL.'gestione');
                            }
                            else{
                                header('Location: ' .URL.'gestione/modifyDblink/'.$id);
                            }
                        }
                        else{
                            header('Location: ' .URL.'gestione/modifyDblink/'.$id);
                        }
                    }
                    else{
                        $_SESSION['errorMessage'] = "La password è sbagliata e non puoi fare una connessione al database";
                        header('Location: ' .URL.'gestione/modifyDblink/'.$id);
                    }
                }
                else{
                    $_SESSION['errorMessage'] = "Utilizzare come host efof.myd.infomaniak.com";
                    header('Location: ' .URL.'gestione/modifyDblink/'.$id);
                }
            }
        }
        
        /**
         * Permette di ritornare alla views gestione.
         */
        public function backViewsGestione(){
            header('Location: '.URL.'gestione');
        }

       /*
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