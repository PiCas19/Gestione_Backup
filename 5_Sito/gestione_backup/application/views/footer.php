            <?php 
            //Se esiste una sessione con chiave username mostro io footer
            if (isset($_SESSION['tipo']) && isset($_SESSION['username']) && !strpos($host, "utente/") && !
            strpos($host, "gestione/modifyDblink") && !strpos($host, "aggiungi_utente")) {
            ?>
            <!-- Footer -->
            <footer id="footer" class="page-footer font-small mt-auto ">
              <!-- Copyright -->
              <div class="footer-copyright text-center py-3 text-white">Copyright &copy; CPT Trevano 2020</div>
              <!-- Copyright -->
            </footer>
            <!-- Footer -->
        <?php }?>
        <!-- Animazioni della navbar(Menu hamburger)-->
        <script src="<?php echo URL; ?>application/sources/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
