<div class="container">
  <div  class="myCard">
    <!-- creo il responsive della pagina utilizzando il metodo a colonne -->
    <div class="row">
      <div class="col-md-6">
        <!-- Contenitore sinistro che contiene il form del login -->
        <div class="myLeftCtn">
          <form class="myForm text-center" method="post" action="<?php echo URL;?>login/checkLogin">
            <h1>Login</h1>
            <div class="form-group">
              <i class="fas fa-user"></i>
              <input class="myInput" type="text" placeholder="Username o e-mail" id="username" name="username" required>
            </div>
            <div class="form-group">
              <i class="fas fa-lock"></i>
              <input class="myInput" type="password" placeholder="Password" id="password" name="password" required>
            </div>
            <?php
              if (isset($_SESSION['error'])) {
                echo "<span class='text-danger'>".$_SESSION['error']."</span>";
                unset($_SESSION['error']);
              }
            ?>
            <input type="submit" class="butt mt-3" value="LOGIN">
          </form>
        </div>
      </div>
      <div class="col-md-6">
          <!-- Contenitore destro che contiene la descrizione dell'applicativo -->
          <div class="myRightCtn">
            <div class="box">
              <h1>Gestione backup</h1>
              <p>Gestione backup è un applicativo che permette di gestire i backup dei siti produttivi della SAMT.</p>
                <input type="button" class="butt_out" value="Leran More">
            </div>
          </div>
      </div>
    </div>
  </div>
</div>
