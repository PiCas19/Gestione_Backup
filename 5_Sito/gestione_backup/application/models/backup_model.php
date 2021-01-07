<?php

    class Backup_Model {

        public function __construct() {           
        }
        
        /**
         * Permette di leggere tutti dati della tabella db_link.
         */
        public function getDbLink($conn){
            $sth = $conn->prepare("select * from db_link");
            $sth->execute();
            return $sth;
        }

        /** 
         * Permette di selezionare i dati (dbuser, dbname, backupFrequency, lastBackupTime) della tabella db_link
         */ 
        public function getDataDbLink($conn){

            $sth = $conn->prepare("select dbname, dbuser, backupFrequency, lastBackupTime from db_link");
            $sth->execute();
            return $sth;
        }
        
        /**
         * Permette di selezionare il nome del database di un determinato collegamento.
         */
        public function getDbnameDblink($conn, $id){
            $sth = $conn->prepare("select dbname from db_link where id=:id");
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result; 
        }
        
        /**
         * Permette leggere la password (hash) di un determinato collegamento (filtro: username).
         */
        public function getDbLinkPassword($conn, $username){
            $sth = $conn->prepare("select dbpass from db_link where dbuser=:username");
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result; 
        }

        /**
         * Permette di selezionare tutti i della tabella db_link
         */ 
        public function getAllDataDbLink($conn){

            $sth = $conn->prepare("select * from db_link");
            $sth->execute();
            return $sth;
        }

        /**
         * Permette di inserire dei nuovi collegamenti nella tabella db_link.
         */ 
        public function insertDataDbLink($conn, $dbhost, $dbuser, $dbpass, $dbname, $dbport){
        
            try{
                //Preparo lo statement che mi permette di inserire dei dati nel tabella db_link
                $sth = $conn->prepare("insert into db_link (dbname, dbhost, dbuser, dbpass, dbport) values (:name, :host, :user, :pass, :port)");
                //Inserisco tutti i dati
                $sth->bindParam(':name', $dbname, PDO::PARAM_STR);
                $sth->bindParam(':host', $dbhost, PDO::PARAM_STR);
                $sth->bindParam(':user', $dbuser, PDO::PARAM_STR);
                $sth->bindParam(':pass', $dbpass, PDO::PARAM_STR);
                $sth->bindParam(':port', $dbport, PDO::PARAM_STR);
                $sth->execute();
                return true;
            }
            catch (PDOException $e){
                return false;
            }
        }

        /**
         * Permette di cancellare un determinato collegamento.
         */
        public function deleteDbLink($conn, $id){
            //Cerco il nome del database rispetto al suo identificativo
            $dbname = $this->getDataDbLinkById($conn, $id);

            //Rimuovo la cartella di backup del collegamento.
            $path = './application/sources/backup/'.$dbname['dbname'];
                    
            $sth = $conn->prepare("delete from db_link where id = :id");
            //Inserisco i dati
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->execute();

        }

        /**
         * Permette di effettuare il reset degli id, 
         * in modo che non ci siano dei "buchi" di numerazione.
         */ 
        public function resetIdDblink($conn){
            $sth = $conn->prepare("set @num := 0");
            $sth->execute();
            $sth = $conn->prepare("update db_link set id = @num := (@num+1)");
            $sth->execute();
            $sth = $conn->prepare("alter table db_link auto_increment = 1");
            $sth->execute();
        }


        /**
         * Permette di selezionare tutti i dato di un specifico collegamento
         */
        public function getDataDbLinkById($conn, $id){
            $sth = $conn->prepare("select * from db_link where id = :id");
            $sth->bindParam(':id', $id, PDO::PARAM_STR);
            $sth->execute();
            //voglio solo 1 record
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        /**
         * Permette di aggiornare il collegamento cambiando il nome dell'host,
         * nome dell'utente, la password e il nome del database.
         */
        public function updateDataDbLink($conn, $dbhost, $dbuser, $dbpass, $dbname){
            try{
                //Preparo lo statement che permette di aggiornare i campi (dbhost, dbuser, dbpass, dbname) del collegamento.
                $sth = $conn->prepare("update db_link set dbhost = :host, dbuser = :user, dbpass = :pass, dbname = :name");
                //Inserisco tutti i dati
                $sth->bindParam(':name', $dbname, PDO::PARAM_STR);
                $sth->bindParam(':host', $dbhost, PDO::PARAM_STR);
                $sth->bindParam(':user', $dbuser, PDO::PARAM_STR);
                $sth->bindParam(':pass', $dbpass, PDO::PARAM_STR);

                $sth->execute();
                return true;
            }
            catch (PDOException $e){
                return false;
            }
        }
        
        
        /**
         * Permette di aggiornare un determinato collegamento il tipo di backup.
         */
        public function updateBackupFrequency($conn, $id, $value){
            //Preparo uno statement che permette di aggiornare il campo
            //backupFrequency (tipo di backup) di un determinato collegamento.
            $sth = $conn->prepare('update db_link set backupFrequency = :value where id = :id');
            
            //Inserisco i dati
            $sth->bindParam(':value', $value , PDO::PARAM_INT);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->execute();
            
        }

        /**
         * Permette di visualizzare i file del backup (SQL + LOG)
         */
        public function getDbLinkBackupFile($dir_dblink){
            $files = array();

            //Percorso della directory
            $path = "./application/sources/backup/" .$dir_dblink;
            //percorso della directory nel quale ci sono i file log
            $path_dir_log = "./application/sources/backup/" .$dir_dblink .'/log';
            
            if(file_exists($path)){
                
        
                    
                //Se riesco aprire la directory nel quale ci sono i file SQL di backup
                if ($handle = opendir($path)){
                    $j = 0;
                    //Leggo il contenuto della directory
                    while (false !== ($entry = readdir($handle))) {
                        //Salvo nell'array files SQL (file di backup)
                        $like = "dump-".$dir_dblink;
                        if(strpos($entry, $like) === 0){
                            //nome del database del corrispettivo backup
                            $files[$dir_dblink]["name"][$j] = $dir_dblink;
                            //salvo nell'array il file sql
                            $files[$dir_dblink]["backup"][$j] = $entry;
                            $j++;
                        }
                    }
                }
                   
                //Se riesco aprire la directory log nel quale ci sono i file log di backup
                if($handle = opendir($path_dir_log)){
                    $j = 0;
                    //Leggo il contenuto della directory log
                    while (false !== ($entry = readdir($handle))) {
                        //Salvo nell'array files txt (file di log)
                        $like = 'log-'.$dir_dblink.'-';
                        if (strpos($entry, $like) === 0) {
                            //salvo nell'array il file log
                            $files[$dir_dblink]['log'][$j] = $entry;
                            
                            //leggo il contenuto del file log
                            $file = file_get_contents($path_dir_log."/".$files[$dir_dblink]['log'][$j]);
                            //trasformo la stringa in una data
                            $date = strtotime(substr($file, 40, 21));
                            //formattazione data: Y-m-d H:i:s
                            $dateBackup = date('Y-m-d H:i:s', $date);
                            //salvo nell'array lo stato del backup
                            $files[$dir_dblink]['status'][$j] = $this->statusBackup($path."/log/".$files[$dir_dblink]["log"][$j]);
                            //salvo nell'array la data del backup
                            $files[$dir_dblink]['date'][$j] = $dateBackup;
                            $j++;
                        }
                    }
                }  
            }
            return $files;
        }

        /**
         * Stato del backup.
         */ 
        public function statusBackup($filename){
            $fileContent = file_get_contents($filename); 
            //Se nel file log viene scritto "Connection", vuole dire che è stato eseguito il backup
            if(strpos(substr($fileContent, 69, 10), "Connection") === 0 ){
               return true;
            }
            else{
               return false;
            }
    
        }
        
        
    }

?>