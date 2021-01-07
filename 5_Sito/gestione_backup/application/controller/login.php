<?php
class Login
{
    /*
     * Permette di creare l'index della pagina login.
     */ 
    function __construct()
    {
    }

    /*
     * Permette di creare l'index della pagina home
     */
    public function index(){
        require './application/views/header.php';
        require './application/views/login/index.php';
        require './application/views/footer.php';
    }
    


    /*
     * Permette di fare il login (connessione + controlli)
     */ 
    public function checkLogin(){

        require_once './application/models/database_model.php';
        require_once './application/models/login_model.php';

        //Faccio partire una sessione
        session_start();

        //connessione al database gestione_backup
        $db = new Database_Model();
        $conn = $db->getConnection();
        
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if(!empty($_POST['username']) && !empty($_POST['password'])){
                $user = $this->test_input($_POST['username']);
                $pswd = $this->test_input($_POST['password']);

                $loginModel = new Login_Model();
                //se l'utente inserisce un email 
                if(filter_var($user, FILTER_VALIDATE_EMAIL)){
                    //risultato della query filtrando per l'email
                    $result = $loginModel->getUtente($conn, $user, $pswd, "email");   
                }
                else{
                    //risultato della query filtrando per lo username
                    $result = $loginModel->getUtente($conn, $user, $pswd,"username");   
                }

                //se trovo un elemento posso entrare nella pagina gestione
                if ($result) {
                    if($result['statusLogin'] != -1){
                        //username dell'utente
                        $_SESSION["username"] = $result['username'];
                        //email dell'utente
                        $_SESSION['email'] = $result['email'];
                        //tipo di utente
                        $_SESSION["tipo"] = $result['tipo'];
                        //identificativo dell'utente
                        $_SESSION["id"] = $result['id'];
                        //immagine di profilo
                        $_SESSION['immagine'] = $result['immagineProfilo'];
                        //nome dell'utente
                        $_SESSION["nome"] = $result['nome'];
                        //cognome dell'utente
                        $_SESSION["cognome"] = $result['cognome'];
                        header('Location: '.URL.'visualizza_backup');
                    }
                    else{
                        //stato del login
                        $_SESSION["status"] = $result['statusLogin'];
                        //id dell'utente
                        $_SESSION['id-utente'] = $result['id'];
                        header('Location: '.URL.'modifica_password');
                    }
                }
                else{
                    $_SESSION["error"] = "Username, e-mail o password sbagliata"; 
                    header('Location: ' . URL);
                }
                
            }
            else{
               header('Location: ' . URL);
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
