<?php
class Gestione
{

    /**
     * Permette di creare l'index della pagina gestione.
     */
    function __construct()
    {
    }

    /*
     * Permette di creare l'index della pagina gestione backup
     */
    public function index(){
        require './application/models/database_model.php';
        $db = new Database_Model();
        $conn = $db->getConnection();

        require './application/models/backup_model.php';
        //Leggo i dati che contiene la tabella db_link
        $db_link =  new Backup_Model();
        $updateIdDblink = $db_link->resetIdDblink($conn);
        $sth = $db_link->getDataDbLink($conn);
        

        require './application/views/header.php';
        require './application/views/gestione/index.php';
        require './application/views/footer.php';
    }



    /*
     * Permette di creare un nuovo link ad un db.
     */ 
    public function createLinkDB(){
        session_start();
        $hostname = $dbname = $username = $pass = $port =  "";
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            //l'hostname può essere solamente efof.myd.infomaniak.com
            if($_POST['hostname'] == DBHOST){
                $hostname = $this->test_input($_POST['hostname']);
                $username = $this->test_input($_POST['username']);
                $pass = $this->test_input($_POST['password']);
                $dbname = $this->test_input($_POST['dbname']);
                $port = $this->test_input($_POST['port']);

                require './application/models/database_model.php';
                require './application/models/password.php';
                
                $password = new Password();
                
                
                $db = new Database_Model();
                //Creo una connessione al database gestione_backup
                $conn = $db->getConnection();
                   
                $db = new Database_Model();           
                //Cambio il nome del db, host, username e password con cui mi collego 
                //(serve per vedere se esiste il db). In effetti setto questi dati 
                //con i valori che ho inserito nei campi del form.
                $db->setDbname($dbname);
                $db->setDbhost($hostname);
                $db->setUser($username);
                $db->setPass($pass);
                $db->setDbport($port);
                
                //Provo a fare una connessione al database dopo che ho modificato i parametri
                $connDbLink = $db->getConnection();
            
                
                //Se la connessione è andato a buon fine
                if($connDbLink){
                   require './application/models/backup_model.php';
                   $db_link = new Backup_Model();
                   $encodePassword = $password->encode($pass);
                   //Aggiungo il nuovo collegamento alla tabella db_link
                   $db_link->insertDataDbLink($conn, $hostname, $username, $encodePassword, $dbname, $port);
                    if($db_link){
                        header('Location: '.URL.'gestione');
                    }
                   
                }
                else{
                    $_SESSION['errorMessage'] = "Questo collegamento non esiste nel host efof.myd.infomaniak.com";
                    header('Location: '.URL.'gestione');
                }
            }
            else{
                $_SESSION['errorMessage'] = "Utilizzare come host efof.myd.infomaniak.com";
                header('Location: '.URL.'gestione');
            }
        }
    }

    /*
     * Permette di cancellare un collegamento.
     */
    public function deleteDblink($i){
        require './application/models/database_model.php';
        require './application/models/backup_model.php';
        $db = new Database_Model();
        //creo una connessione
        $conn = $db->getConnection();
        $db_link = new Backup_Model();
        //Cancello il collegamento rispetto al suo id
        $db_link->deleteDbLink($conn, $i);
        //nome del database in base all'id
        $dbname = $db_link->getDbnameDblink($conn, $i);
        $path = "./application/sources/backup/".$dbname['dbname'];
        
        if($path != './application/sources/backup/'){
                $this->removeDirectory($path);   
        }
        header('Location: '.URL.'gestione');
    }
    
    /**
     * Permette di rimuovere delle directory 
     * in modo recursivo.
     */
    private function removeDirectory($path) {
        //permette di scansionare tutto il contenuto della cartella 
        $files = glob($path . '/*');
        foreach ($files as $file) {
            //rimuovo tutti i file e le sotto cartelle
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        //elimina la cartella
        rmdir($path);
    }

    /*
     * Permette di modificare il collegamento.
     */ 
    public function modifyDblink($id){
        require './application/controller/modifica.php';
        //Apro la pagina modifica nella quale posso modificare il collegamento.
        $modifica = new Modifica();
        $modifica->setId($id);
        $modifica->index();

    }
    
    /**
     * Permette di modificare il tipo di backup per un determinato collegamento.
     */
    public function modifyBackupFrequency(){
        require './application/models/database_model.php';
        require './application/models/backup_model.php';
        
        $index = $this->test_input($_POST['index']);
        $value = $this->test_input($_POST['value']);
        
        
        $db = new Database_Model();
        //creo una connessione
        $conn =  $db->getConnection();
        $db_link = new Backup_Model();
        
    
        
        //aggiorno il campo backupFrequency
        $db_link->updateBackupFrequency($conn, $index, $value);
          
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