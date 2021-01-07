
<?php
	if (isset($_SESSION['username'])) {
		header('Location: '.URL);
		exit;
	}
	
	if(isset($_SESSION['status']) &&  isset($_SESSION['id-utente'])){
?>
<div class="container">
	<div class="col-md-10">
        <!-- Contenitore sinistro -->
        <div class="myLeftCtn">
        	<h2>Modifica la password provvisoria</h2>
			<form class="mt-4" id="changePassword" method="post" action="<?php echo URL; ?>modifica_password/modifyPassword/<?php echo $_SESSION['id-utente']; ?>">
				<label id="labelUsername">Username:</label><input id="username" type="text" placeholder="Username" name="username" title="Lo username deve essere: nome.cognome" required/></br>
				<?php 
					if(isset($_SESSION['errUsername'])){
						echo "<span class='text-danger'>".$_SESSION['errUsername']."</span><br><br>";
						unset($_SESSION['errUsername']);
					}
				?>
				<label id="labelNewPassword">Nuova password: </label><input id="txtNewPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Deve contenere almeno un numero e una lettera maiuscola e minuscola e almeno 8 caratteri" type="password" name="password" placeholder="nuova password" required></br>
				<label id="labelConfirmPassword">Conferma password:</label><input id="txtConfirmPassword" type="password" name="confirmPassword" placeholder="conferma password" required></br>
				<span class="mb-4" id="CheckPasswordMatch"></span></br>
				<input class="mt-4 btn btn-success rounded" type="submit" name="submit" value="invia">
			</form>
		</div>
	</div>
</div>
<?php
}
else{
	header("Location: ".URL);
}
?>