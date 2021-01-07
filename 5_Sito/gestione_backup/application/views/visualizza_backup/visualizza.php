<?php
if (!isset($_SESSION['username'])) {
	header('Location: '.URL);
	exit;
}
?>
<div class="container">
	<!-- creo il responsive della pagina utilizzando il metodo a colonne -->
	<div class="row">
		<div class="col-md-12">
			<!-- Contenitore sinistro -->
			<div id="visualizzaContent" class="myLeftCtn">
				<?php echo $content; ?>
				<a class="btn btn-secondary mt-5" href="<?php echo URL; ?>visualizza_backup/backVisualizza">ESCI</a>
			</div>
		</div>
	</div>
</div>