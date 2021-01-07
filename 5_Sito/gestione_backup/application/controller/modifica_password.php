<?php
    class Modifica_Password {

    	function __construct()
	    {
	    }

	    public function index(){
	    	require './application/views/header.php';
	    	require './application/views/modifica_password/index.php';
		}

		public function modifyPassword($id){
			require_once './application/models/database_model.php';
        	require_once './application/models/login_model.php';

	        //Faccio partire una sessione
	        session_start();

	        //connessione al database gestione_backup
	        $db = new Database_Model();
        	$conn = $db->getConnection();

        	$password = $confirmPassword = $username =  "";


			if($_SERVER["REQUEST_METHOD"] == "POST") {
				if(!empty($_POST['confirmPassword']) && !empty($_POST['password'])){
					$password = $this->test_input($_POST['password']);
					$confirmPassword = $this->test_input($_POST['confirmPassword']);
					$username = $this->test_input($_POST['username']);
					
					$loginModel = new Login_Model();
					
					$result = $loginModel->checkUsername($conn, $username);
					
					//pattern della password
					$pattern = "/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/";
					//pattern dello username
					$patternUsername = "/[a-z]{0,9}\.[a,z]{0,9}/";
					
					if(preg_match($pattern, $password) && $result == null && preg_match($patternUsername, $username)){
						//La password di conferma deve essere uguale alla nuova password
						if($confirmPassword == $password){
								//Aggiorno la password dell'utente
								$loginModel->updatePasswordAndUsernameUtente($conn, $id, $password, $username);
								//distruggo le sessioni
								session_destroy();
								//ritorno alla pagina di login
								header('Location: '.URL);
						}
						else{
							header('Location: ' . URL . 'modifica_password');
						}
					}
					else{
						$_SESSION['errUsername'] = "Esiste già un utente con lo stesso username";
						header('Location: ' . URL . 'modifica_password');
					}
				}
			}
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