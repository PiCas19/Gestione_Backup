<?php
if (!isset($_SESSION['username'])) {
header('Location: '.URL);
exit;
} 

$immagine = "";
if(isset($_SESSION['temp_immagine'])){
	$immagine = $_SESSION['temp_immagine'];
}
else{
	$immagine = $_SESSION['immagine'];
}

?>
<div class="wrapper bg-white mt-sm-5">
	<h4 class="pb-4 border-bottom">Impostazione account</h4>
	<div class="d-flex align-items-start py-3 border-bottom"> 
		<!-- immagine di profilo -->
		<img src="<?php echo URL; ?>application/sources/img/users/<?php echo $immagine;?>" class="img">
		<div class="pl-sm-4 pl-2" id="img-section"> <b>Foto di profilo</b>
			<p>Tipo di file accettato .png. Meno di 1 MB</p> 
			<!-- dropzone -->
			<form action="<?php echo URL ?>utente/uploadImage" class="dropzone"></form>
			<br>
			<!-- pulsante per caricare l'immagine di profilo -->
			<button onclick="window.location.reload(true)" type="submit" class="btn button border"><b>Carica</b></button>
		</div>
	</div>
	<!-- form per potere modificare le impostazioni dell'account -->
	<form action="<?php echo URL; ?>utente/saveSettings" method="post">
		<div class="py-2">
			<div class="row py-2">
				<div class="col-md-6"> <label for="nome">Nome</label> <input type="text" name="nome" class="bg-light form-control" placeholder="<?php echo $_SESSION['nome']; ?>"> </div>
				<div class="col-md-6 pt-md-0 pt-3"> <label for="cognome">Cognome</label> <input type="text" name="cognome" class="bg-light form-control" placeholder="<?php echo $_SESSION['cognome']; ?>"> </div>
			</div>
			<div class="row py-2">
				<div class="col-md-6"> <label for="email">Email</label> <input type="text" name="email" class="bg-light form-control" placeholder="<?php echo $_SESSION['email']; ?>"> </div>
				<div class="col-md-6 pt-md-0 pt-3"> <label for="username">Username</label> <input type="text" name="username" class="bg-light form-control" placeholder="<?php echo $_SESSION['username']; ?>"> </div>
			</div>
			<div class="row py-2">
				<div class="col-md-6"> <label for="password">Password corrente</label> <input type="password" name="password" class="bg-light form-control" placeholder=""> </div>
				<div class="col-md-6 pt-md-0 pt-3"> <label for="cnewPassword">Nuova password</label> <input type="password" name="newPassword" class="bg-light form-control" placeholder=""> </div>
			</div>
			<div class="py-3 pb-4 border-bottom"> <input type="submit" value="Salva modifiche" class="btn btn-primary mr-3"/> <input type="submit" name="cancella" class="btn border button" value="Cancella"/> </div>
			<a class="btn btn-secondary mt-5" href="<?php echo URL; ?>utente/exitEditProfile">ESCI</a>
		</div>
	</form>
</div>
