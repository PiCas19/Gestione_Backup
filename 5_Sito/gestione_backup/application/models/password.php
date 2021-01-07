<?php
    class Password {

        function __construct()
        {
        }

        /**
         * Permette di verificare che la password 
         * passata corrisponde al hash.
         */
        public function verify($pswd, $pswd_hash){
            return password_verify($pswd, $pswd_hash);
        }

        /**
         * Permette di codificare la password in hash.
         */
        public function encode($pswd){
            $hash = password_hash($pswd, PASSWORD_DEFAULT);
            return $hash;
        }


    }


?>