<?php
    
    use Ifsnop\Mysqldump as IMysqldump;
    
    class Dblink_Model {
        
        //Hostname
        private $dbhost = 'efof.myd.infomaniak.com';
        //Nome del database
        private $dbname = 'efof_db1';
        //Porta 
        private $dbport = '3306';
        //Nome utente
        private $dbuser = 'efof_i17caspie';
        //Password
        private $dbpass = "AdminDB2020$";
        
        //connessione
        private $conn;
    

        public function __construct() {
        }
        
        /*
         * Permette connettersi al database gestione_backup (db del progetto)
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
        
        /**
         * Permette di modificare la password.
         */
        public function setDbPass($dbpass){
            $this->dbpass = $dbpass;
        }
        
        /**
         * Permette di modificare lo username.
         */
        public function setDbUser($dbuser){
            $this->dbuser = $dbuser;
        }
        
        /**
         * Permette di modificare il nome del database.
         */
        public function setDbName($dbname){
            $this->dbname = $dbname;
        }
        
       
        /*
         * Permette di leggere la password degli utenti
         * filtrando per username.
         */
        public function getUtentePassword($conn, $user, $pass){
            
            //Preparo lo statement che permette di selezionare la password della tabella 
            // utenti filtrando per username 
            $sth = $conn->prepare("select pswd from utenti where username=:username");
            
            //inserisco i parametri
            $sth->bindParam(':username', $user, PDO::PARAM_STR);
            
            $sth->execute();
            //voglio solo 1 record
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            //controllo che la password corrisponde al hash
            if(password_verify($pass, $result['pswd'])){
                return $result;
            }
            else{
                return false;
            }
        }
        
        /**
         * Permette di ricavare i dati degli utenti.
         */
        private function getDataUser($conn){
            $sth = $conn->prepare("select * from utenti");
            $sth->execute();
            return $sth;
        }

        /**
         * Permette di leggere i dati della colonna dbname della tabella db_link.
         */
        public function getDbLink($conn){
            $sth = $conn->prepare("select * from db_link");
            $sth->execute();
            return $sth;
        }
        
        /**
         * Permette di aggiornare il campo lasBackupTime di un determinato collegamento.
         */
        private function updateLastBackupTime($conn, $backupTime, $id){
            $sth = $conn->prepare("update db_link set lastBackupTime=:backupTime where id=:id");
            $sth->bindParam(':backupTime', $backupTime, PDO::PARAM_STR);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->execute();
        }
        
        /**
         * Permette di contare i file presenti in una cartella.
         */
        private function countFile($path){
            //contatore
            $i = 0;
            //se riesco ad aprire la cartella
            if( $handle = opendir($path) ) { 
                //leggo il contenuto della cartella
                while( ($file = readdir($handle)) !== false ) { 
                    //aumenta il contatore solo se sono file
                    if( !in_array($file, array('.', '..', 'log')) && !is_dir($path.$file))  
                        $i++; 
                } 
            } 
            return $i;
        }
        
        /**
         * Permette di eliminare un file di una specifica cartella
         */
        private function deleteFile($path, $like){
            //se riesco ad aprire la cartella
            if($handle = opendir($path)){
                //permette di leggere il contenuto della cartella
                while (false !== ($entry = readdir($handle))){
                   //se il file corrisponde al pattern (pattern: file sql, file txt).
                   if (strpos($entry, $like) === 0) {
                       //elimino il file (solo l'ultimo)
                       unlink($entry);
                       break;
                   }
                }   
            }
        }
        
        /** 
         * Permette di eseguire un backup del database
         */ 
        public function runBackup($connDB, $conn,$database, $user, $pass, $host, $lastBackupTime, $id){
        
            //require dell'autoload
            require 'vendor/autoload.php';      
            //Percorso directory root
            $rootDorectory = "../gestione_backup/application/sources/backup/";
            //cambio directory
            chdir($rootDorectory);
            //Percorso della directory
            $path = $database;
            //Percorso della directory log
            $path_log = $path.'/log';
            //Se la directory non esiste, creo la directory
            if(!is_dir($path)){
                mkdir($path);
                //cartella dei file log
                mkdir($path_log);
            }
            
            //conto il numero di file presenti nella cartella 
            //di backup dell specifico collegamento.
            $countFile = $this->countFile($path);
            
            //se sono presenti più di 10 file
            if($countFile > 10){
                
                //elimino l'ultimo file (sql e txt).
                $this->deleteFile($path, "dump-".$database);
                $this->deleteFile($path_log, "log-".$database);
              
            }

          
            //nome del file sql
            $dir_name_backup = $path.'/dump-'.$database.'-'.date("d-m-Y").'.sql';
            //nome del file log
            $dir_name_log = $path_log . '/log-'.$database.'-'.date("d-m-Y").'.txt';
        
            try {
                
                //dsn
                $dsn = 'mysql:host='.$host.';dbname='.$database;
                //creazione del comando dump
                $dump = new IMysqldump\Mysqldump($dsn, $user,$pass);
                //inizializzo il timer
                $start_time = microtime(true); 
                $dump->start($dir_name_backup);
                
                $end_time = microtime(true); 
                $tempo = ($end_time - $start_time);   
                
                //Aggiorno il campo lastBackupTime
                $this->updateLastBackupTime($conn, date("Y-m-d H:i:s"), $id);
                      
                //Creazione file log
                $this->write_mysql_log($connDB, $dir_name_log, $database, $user, $host, "", $tempo);  
                //ritorno alla cartella scheduler
                chdir("../../../../scheduler");   
                
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                //Creazione file log con messaggio di errore
                $this->write_mysql_log(null, $dir_name_log, $database, $user, $host, $errorMsg, $tempo);
                //ritorno alla cartella scheduler
                chdir("../../../../scheduler");
            }
        }
        
        /**
         * Permette di mostrare il nome delle tabelle.        
         */
        private function showTables($conn){
            $sth = $conn->prepare("show tables");
            $sth->execute();
            return $sth;
        }
        
        /**
         * Permette di ricavare il numero di righe o record presenti in determinate tabelle.
         */
        private function getRowsTables($conn, $tables){
            $sth = $conn->prepare("select count(*) as 'rows' from ".$tables);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        }
            
        
        /**
         * Permette di creare e scrivere nel file log.
         */
        private function write_mysql_log($conn, $dir_name_log, $database, $user, $host, $errorMsg, $tempo)
        {
            //creo il file log
            $file = fopen($dir_name_log, "w");
            //Nome host
            $message = "Host: ".$host."\n";
            fwrite($file, $message);
            //Data creazione backup
            $message = "Date: " .date('r')."\n\n";
            fwrite($file, $message);
            //Ci deve essere una connessione, altrimenti è successo un errore
            if($conn != null){
                //Comandi eseguiti durante il dump
                $message = "Connection to ".$database." with ".$user."\n\n";
                fwrite($file, $message);
                $sth = $this->showTables($conn);
                $index = "Tables_in_".$database;
                //Ciclo che mostra le tabelle di un collegamento
                while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                    //Colonne presenti nel collegamento
                    $message = "Table structure for table ". $row[$index]."\n";
                    fwrite($file, $message);
                    //Numero di di record presenti in ogni tabella
                    $result = $this->getRowsTables($conn, $row[$index]);
                    if($result['rows'] > 0){
                           $message = "Dumping data for table " .$row[$index]."\n";
                           fwrite($file, $message);
                           $message = "Dumped table " .$row[$index]." with ".$result['rows']." row(s)\n\n";
                           fwrite($file, $message);
                    }
                }
                $message = "Disconnect to " . $database ."\n\n";
                fwrite($file, $message);   
            }
            //messaggio in caso di errore
            if($errorMsg != ""){
                fwrite($file, $errorMsg."\n");
                require 'mail_model.php';
                $conn = $this->getConnection();
                //leggo la tabella utenti
                $sth = $this->getDataUser($conn);
                
                //mando le email agli utenti
                //while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                    $mail = new Mail_Model();
                    $mail->send('pierpaolo.casati@samtrevano.ch', 'pierpaolo.casati', $errorMsg, $database);
                //}
                
                
            }
            //Tempo di esecuzione del dump
            $message = "The backup was performed in " . $tempo . " second\n";
            fwrite($file, $message);
            fclose($file);
        }
        

    }

?>