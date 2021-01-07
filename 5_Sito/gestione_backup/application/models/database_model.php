<?php
    class Database_Model {

        
        //Variabili di configurazioni per accedere al db
        private $dbhost = DBHOST;
        private $dbname = DBNAME;
        private $dbport = DBPORT;
        private $dbuser = DBUSER;
        private $dbpass = DBPASS;

        private $conn;


        /*
         * Permette di settare il nome dell'utente per accedere al db
         */ 
        public function setUser($dbuser){
            $this->dbuser = $dbuser;
        }

        /*
         * Permette di settare la password per accedere al db.
         */ 
        public function setPass($dbpass){
            $this->dbpass = $dbpass;
        }

        /* 
         * Permette di settare il nome del db
         */
        public function setDbname($dbname){
            $this->dbname = $dbname;
        }

        /*
         * Permette di settare l'host del db
         */ 
        public function setDbhost($dbhost){
            $this->dbhost = $dbhost;
        }
        
        /*
         * Permette di settare la porta
         */ 
        public function setDbport($dbport){
            $this->dbport = $dbport;
        }


        public function __construct() {
        }

        /*
         * Permette connettersi al database (db del progetto)
         */
        public function getConnection(){
           
            try{
                //Permette di creare una sola connessione
                if(!$this->conn){
                    $dsn = 'mysql:host='.$this->dbhost.';dbname='.$this->dbname.';port='.$this->dbport;
                    $this->conn = new PDO($dsn,$this->dbuser, $this->dbpass);      
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }

                return $this->conn;
            }
            catch (PDOException $e){
                echo $e->getMessage(); 
            }
        }
    }


?>