<?php
if (!isset($_SESSION['username'])) {
    header('Location: '.URL);
    exit;
}
?>
<div class="container">
    <div  class="myCard">
        <!-- creo il responsive della pagina utilizzando il metodo a colonne -->
        <div class="row">
            <div class="col-md-10">
                <!-- Contenitore sinistro -->
                <div class="myLeftCtn">
                    <!-- Form che permette di creare un nuovo utente -->
                    <form method="post" action="<?php echo URL; ?>aggiungi_utente/checkAddUsers">
                        <h1>Creazione utente</h1>
                        <p>Compila questo modulo per creare un nuovo utente.</p>
                            <label for="nome"><b>Nome</b></label>
                            <input name="nome" placeholder="Nome" type="text" title="La prima lettera deve essere maiuscola e non ci devono essere degli spazi"></br>
                            <?php 
                            if(isset($_SESSION['nameErr'])){
                               echo "<span class='text-danger'>".$_SESSION['nameErr']."</span><br>";
                               unset($_SESSION['nameErr']);
                            }
                            ?>
                            <label class="mt-2" for="cognome"><b>Cognome</b></label>
                            <input class="mt-2" name="cognome" placeholder="Cognome" type="text" title="La prima lettera deve essere maiuscola e non ci devono essere degli spazi"></br>
                            <?php 
                            if(isset($_SESSION['surnameErr'])){
                               echo "<span class='text-danger'>".$_SESSION['surnameErr']."</span><br>";
                               unset($_SESSION['surnameErr']);
                            }
                            ?>
                            <label class="mt-2" for="pswd"><b>Password</b></label>
                            <input class="mt-2" name="password" placeholder="Password" type="password" title="Password provvisoria"></br>
                            <?php 
                            if(isset($_SESSION['passwordErr'])){
                               echo "<span class='text-danger'>".$_SESSION['passwordErr']."</span></br>";
                               unset($_SESSION['passwordErr']);
                            }
                            ?>
                            <label class="mt-2" for="email"><b>Email</b></label>
                            <input class="mt-2" name="email" placeholder="Email" type="email"></br>
                            <?php 
                            if(isset($_SESSION['emailErr'])){
                               echo "<span class='text-danger'>".$_SESSION['emailErr']."</span><br>";
                               unset($_SESSION['emailErr']);
                            }
                            
                            if(isset($_SESSION['accountErr'])){
                                echo "<span class='text-danger'>".$_SESSION['accountErr']."</span><br>";
                                unset($_SESSION['accountErr']);
                            }
                            ?>
                            <input class="mt-2" type="radio" name="type" value="amministratore">
                            <label class="mt-2" for="amministratore">Amministratore</label>
                            <input class="mt-2" type="radio" name="type" value="responsabile">
                            <label class="mt-2" for="responsabile">Responsabile</label>
                            <?php 
                            if(isset($_SESSION['typeErr'])){
                               echo "<span class='text-danger ml-2'>".$_SESSION['typeErr']."</span>";
                               unset($_SESSION['typeErr']);
                            }
                            ?>
                            <br>
                            <button type="submit" class="btn btn-success rounded ">CREA</button>
                            <a class="btn btn-secondary" href="<?php echo URL; ?>aggiungi_utente/backViewsUsers">ESCI</a>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>