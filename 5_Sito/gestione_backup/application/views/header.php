<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Header delle pagine web">
    <meta name="keywords" content="HTML,CSS,PHP,JavaScript">
    <meta name="author" content="Pierpaolo Casati">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!--Libreria fontawesone (icone)  -->
        <link rel="stylesheet" href="./application/sources/fontawesone/css/all.min.css">
    
        <!-- Libreria bootstrap -->
        <link rel="stylesheet" href="<?php echo URL; ?>application/sources/bootstrap/css/bootstrap.min.css">
    
        <!-- Login style -->
        <link rel="stylesheet" href="<?php echo URL; ?>application/sources/css/login.css">
        
        <!-- Navbar style -->
        <link rel="stylesheet" href="<?php echo URL; ?>application/sources/css/navbar.css">
    
        <!-- Main style -->
        <link rel="stylesheet" href="<?php echo URL; ?>application/sources/css/main.css">
        
        <!-- Stile della tabella -->
        <link rel="stylesheet" href="<?php echo URL; ?>application/sources/css/table.css">
        
        <!-- Stile dropzone -->
        <link rel="stylesheet" href="<?php echo URL; ?>application/sources/script/dropzone/dropzone.css">
        
        <!-- JQuery 3.5.1 slim -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <!-- Libreria ajax popper -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        
        <!-- Libreria ajax googleapis -->
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    
        <!-- Script che gestisce il popup -->
        <script type="text/javascript" src="<?php echo URL; ?>application/sources/script/popup.js"></script>
        
        <!-- Script che gestisce le password -->
        <script type="text/javascript" src="<?php echo URL;?>application/sources/script/password.js"></script>
        
        <!-- Scripts che gestisce dropzone -->
        <script src="<?php echo URL; ?>application/sources/script/dropzone/dropzone.js"></script>
        
    
    <title>Gestione e backup</title>
</head>
<body>
    <?php 

        //Faccio partire la sessione
        session_start();
        
        $host =(isset($_SERVER['HTTPS'])&& $_SERVER['HTTPS'] === 'on' ? "https://" : "http://").$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        //Se mi trovo nella pagina di login non mostro la navbar (distruggo semplicemente la sessione)
        if($host == URL){
            unset($_SESSION["username"]);
            session_destroy();
        }

        //Se esiste una sessione con chiave username mostro la navabar
        if (isset($_SESSION['username']) && !strpos($host, "utente/") && !strpos($host, "gestione/modifyDblink") && 
        !strpos($host, "aggiungi_utente") && !strpos($host, "getContentFile")) {
        ?>
        <!-- navbar -->
        <nav class="mb-1 navbar navbar-expand-sm navbar-dark default-color">
            <!-- Logo della navbar -->
            <a class="navbar-brand" href="#"><img src="<?php echo URL;?>application/sources/img/logo_sito.png" width="50" height="50"></a>
            <!-- Crea un menu hamburger, cioè il menu di una pagina web quando sei su un telefono -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333" 
            aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Link della navbar -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
                <ul class="navbar-nav mr-auto">
                    <li><a class="nav-item nav-link text-white" href="<?php //echo URL;?>visualizza_backup">Visualizza backup</a></li>
                    <!-- Se l'utente che fa il login è un amministratore può visualizzare i link per gestire backup e utenti -->
                    <?php if($_SESSION['tipo'] == "amministratore") { ?>
                        <li><a class="nav-item nav-link text-white" href="<?php //echo URL;?>gestione">Gestione backup</a></li>
                        <li><a class="nav-item nav-link text-white" href="<?php //echo URL;?>utente">Gestione utenti</a></li>
                    <?php }?>
                </ul>
                <!-- Immagine del profilo -->
                <ul class="navbar-nav ml-auto nav-flex-icons">
                    <!-- se l'utente clicca sopra appare un menu a tendina -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-333" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                            <!-- immagine di profilo -->
                            <img src="<?php echo URL; ?>application/sources/img/users/<?php echo $_SESSION['immagine']?>" width="50" height="50" class="rounded-circle">
                        </a>
                        <!-- vari elementi del dropdown -->
                        <div class="dropdown-menu dropdown-menu-right dropdown-default"
                        aria-labelledby="navbarDropdownMenuLink-333">
                            <a class="dropdown-item" href="<?php echo URL; ?>utente/editProfile">Impostazioni profilo</a>
                            <a class="dropdown-item" href="<?php echo URL;?>visualizza_backup/logout">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

    <?php }
    
    ?>





