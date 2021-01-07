<?php
    class Utente_Model{

       

        public function __construct() {           
        }

        /*
         * Permette di leggere i dati della tabella utenti 
         */
        public function getUtenti($conn){

            //Preparo lo statement che mi seleziona tutti i dati
            $sth = $conn->prepare("select * from utenti");   
            $sth->execute();
            return $sth;
        }

        /**
         * Permette di leggere i dati di un determinato utente 
         */
        public function getUserById($conn, $id){
            $sth = $conn->prepare('select * from utenti where id=:id');
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->execute();

            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result; 

        }
        
        /** 
         * Permette di aggiornare il profilo di un utente.
         */
        public function updateProfilo($conn, $immagine, $nome, $cognome, $email, $username, $password, $id){
            $sth = $conn->prepare('update utenti set immagineProfilo=:immagine, nome=:nome, cognome=:cognome, email=:email, username=:username, pswd=:password where id=:id');
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->bindParam(':nome', $nome, PDO::PARAM_STR);
            $sth->bindParam(':cognome', $cognome, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->bindParam(':password', $password, PDO::PARAM_STR);
            $sth->bindParam(':immagine', $immagine, PDO::PARAM_STR);
            $sth->execute();
        }
        
        /** 
         * Permette di aggiornare l'immagine di profilo profilo di un utente.
         */
        public function updateImmagine($conn, $immagine, $id){
            $sth = $conn->prepare('update utenti set immagineProfilo=:immagine where id=:id');
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->bindParam(':immagine', $immagine, PDO::PARAM_STR);
            $sth->execute();
        }
        
        /**
         * Permette di leggere i dati di un utente secondo lo email.
         */
        public function getUserByEmail($conn, $email){
            $sth = $conn->prepare('select * from utenti where email=:email');
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->execute();
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            return $result;
        }

        /**
         * Permette di modificare il tipo di utente di un determinato id
         */
        public function updateTypeUser($conn, $id, $tipo){
            $sth = $conn->prepare('update utenti set tipo=:type where id=:id');
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->bindParam(':type', $tipo, PDO::PARAM_STR);
            $sth->execute();
        }

        /**
         * Permette di aggiungere un nuovo utente.
         */
        public function addUser($conn, $nome, $cognome, $username, $pswd, $email, $tipo){
            //Preparo lo statement che permette di inserire un nuovo utente
            $sth = $conn->prepare('insert into utenti (nome, cognome, username, pswd, email, tipo) values (:nome, :cognome, :username, :pswd, :email, :tipo)');
            //Inserisco i dati
            $sth->bindParam(':nome', $nome, PDO::PARAM_STR);
            $sth->bindParam(':cognome', $cognome, PDO::PARAM_STR);
            $sth->bindParam(':username', $username, PDO::PARAM_STR);
            $sth->bindParam(':pswd', $pswd, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':tipo', $tipo, PDO::PARAM_STR);
            $sth->execute();

        }
        
        /**
         * Permette di eliminare un utente
         */
        public function deleteUser($conn, $id){
            //Preparo lo statement che permette di eliminare un utente
            $sth = $conn->prepare('delete from utenti where id = :id');
            $sth->bindParam(':id', $id, PDO::PARAM_INT);
            $sth->execute();
        }

        /*
         * Permette di effettuare il reset degli id, 
         * in modo che non ci siano dei "buchi" di numerazione.
         */ 
        public function resetIdUser($conn){
            $sth = $conn->prepare("set @num := 0");
            $sth->execute();
            $sth = $conn->prepare("update utenti set id = @num := (@num+1)");
            $sth->execute();
            $sth = $conn->prepare("alter table utenti auto_increment = 1");
            $sth->execute();
        }
    }

?>