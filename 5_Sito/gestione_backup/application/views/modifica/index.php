<?php
if (!isset($_SESSION['username'])) {
    header('Location: '.URL);
    exit;
}
?>
<div class="container">
    <div class="col-md-10">
        <!-- Contenitore sinistro -->
        <div class="myLeftCtn">
            <h2 class="mt-3">Modifica il collegamento</h2>
            <!-- Form che permette di modificare i dati del collegamento -->
            <form id="modifyDblink" class="mt-4" method="post" action="<?php echo URL; ?>modifica/modify/<?php echo $this->id; ?>">
                <label>Host:</label>
                <input type="text" name="dbhost" placeholder="Host"  value="<?php echo $result['dbhost'];?>" required>
                </br>
                <label>Username:</label>
                <input type="text" name="dbuser" placeholder="Username" value="<?php echo $result['dbuser']; ?>" required> 
                </br>
                <label>Password:</label>
                <input type="password" name="dbpass" placeholder="Password" required> 
                </br>
                <label>Name:</label>
                <input type="text" name="dbname" placeholder="Name" value="<?php echo $result['dbname']; ?>"  required>
                </br>
                </br>
                <?php
                if(isset($_SESSION['errorMessage'])){
                    echo '<p class="text-danger">'.$_SESSION['errorMessage'].'</p>';
                    unset($_SESSION['errorMessage']);
                }
                ?>
                <button type="submit" class="btn btn-success rounded ">SALVA</button>
                <a class="btn btn-secondary" href="<?php echo URL; ?>modifica/backViewsGestione">ESCI</a>
            </form>
        </div>
    </div>
</div>
