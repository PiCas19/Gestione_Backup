<?php
    class Login_Model{

       

        public function __construct() {           
        }

        /*
         * Permette di leggere i dati della tabella utenti 
         * filtrando per username e password
         */
        public function getUtente($conn, $user, $pass, $col){
        
            //Preparo lo statement che permette di selezionare i dati 
            // della tabella utenti filtrando per username (username e email).
            $sth = $conn->prepare("select * from utenti where ".$col."=:username");
            
            //inserisco i parametri
            $sth->bindParam(':username', $user, PDO::PARAM_STR);
            
            $sth->execute();

            //voglio solo 1 record
            $result = $sth->fetch(PDO::FETCH_ASSOC);

            require './application/models/password.php';
            $password = new Password();
            if($password->verify($pass, $result['pswd'])){
                return $result;
            }
            else{
                return false;
            }
        }
        
        /**
         * Controllo che nel database non esiste già lo stesso username.
         */
        public function checkUsername($conn, $username){
            $sth = $conn->prepare("select * from utenti where username=:username");
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        /**
         * Permette di aggiornare la password e lo username di un specifico utente.
         */
        public function updatePasswordAndUsernameUtente($conn, $id, $password, $username){
            //Preparo un statement che permette di aggiornare la password e 
            //lo username di uno specifico utente.
            $sth = $conn->prepare("update utenti set pswd=:password, username=:username, statusLogin=:statusLogin where id=:id");

            //stato del login 
            $statusLogin = 0;

            //Codifico la password
            require './application/models/password.php';
            $pass = new Password();
            $pswd = $pass->encode($password);

            //inserisco i parametri
            $sth->bindParam(':password', $pswd, PDO::PARAM_STR);
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->bindParam(':statusLogin', $statusLogin, PDO::PARAM_INT);
            $sth->bindParam(':id', $id, PDO::PARAM_INT);

            $sth->execute();
        }

    }

?>