<?php

//TODO: Authentication basic
//$username = $_SERVER['PHP_AUTH_USER'];
//$pass = $_SERVER['PHP_AUTH_PW'];

require 'dblink_model.php';

//password dei due accessi.
//aggiungere all'array le password necessarie e togliere il commento
//$password = array("");

$db_link = new Dblink_Model();

//connessione al database
$conn = $db_link->getConnection();


//TODO: leggo i dati degli utenti e verifica anche la password data corrisponde alla password dell'amministratore
//$result = $db_link->getUtentePassword($conn, $username, $pass);

//leggo i dati della tabella db_link
$sth = $db_link->getDbLink($conn);



//Tempo passato
$timePassed = " ";

//TODO: se la password corrisponde all'autenticazione
//if($result){
    
    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {    
        $pass = '';
        
        foreach($password as $p){
            //verifico che la password che conosco corrisponde alla password hash.
            if(password_verify($p, $row['dbpass'])){
                $pass = $p;
                break;
            }
        }
        
        $db = new Dblink_Model();
        
        $db->setDbPass($pass);
        $db->setDbUser($row['dbuser']);
        $db->setDbName($row['dbname']);
        

        //connessione al database
        $connDB = $db->getConnection();
        
        
        
        if($row['backupFrequency'] != 0){
            if($row['lastBackupTime'] == '0000-00-00 00:00:00'){
                $db_link->runBackup($connDB,$conn, $row['dbname'], 
                $row['dbuser'], $pass, $row['dbhost'], $row['lastBackupTime'], $row['id']);  
            }
            else{
                //Se il backup giornaliero 
                if($row['backupFrequency'] == 1){
                    //passato un giorno
                    $timePassed = strtotime("-1 day");
                }
                
                //Se il backup è settimanale
                if($row['backupFrequency'] == 2){
                    //passato una settimana
                    $timePassed = strtotime("-7 days");
                }
                
                //Se il backup è mensile
                if($row['backupFrequency'] == 3){
                    //passato un mese
                    $timePassed = strtotime("-1 month");
                }
                
                //Se è passato un certo tempo
                if(strtotime($row['lastBackupTime']) < $timePassed){
                    $db_link->runBackup($connDB,$conn, $row['dbname'], 
                    $row['dbuser'], $pass, $row['dbhost'], $row['lastBackupTime'], $row['id']);  
                }   
            }
        }
    }       
//}
/*else{
    //TODO: utente non ha accesso alla pagina
    http_response_code(401);
}*/

?>