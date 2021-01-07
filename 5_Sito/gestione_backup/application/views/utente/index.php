<?php
if (!isset($_SESSION['username'])) {
    header('Location: '.URL);
    exit;
}
else{
    unset($_SESSION["temp_immagine"]);
}
?>
<!-- Tabella con tutti gli utenti dell'applicativo web -->
<!-- La tabella è di colore nero e quando passo sopra la riga il colore di sfondo diventa grigio -->
<table id="utente" class="table table-bordered mt-5">
    <thead>
        <tr>
            <th scope="col">Nome</th>
            <th scope="col-md-5 col-xs-5">Cognome</th>
            <th scope="col-md-4 col-xs-4">Email</th>
            <th scope="col-md-3 col-xs-3">Tipo</th>
            <th scope="col-md-2 col-xs-2">Elimina</th>
        </tr>
    </thread>
    <tbody>
        <?php
            $i = 1;
            //Stampo tutti gli utenti dell'applicativo
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr>
                <td scope='row'> <?php echo $row['nome'];?></td>
                <td scope="row"><?php echo $row['cognome']; ?></td>
                <td scope="row"><?php echo $row['email']; ?></td>
                <?php
                //L'utente amministratore può cancellare e modificare i permessi degli altri utenti
                if($_SESSION['id'] != $row['id']){
                    $url = URL."utente/deleteUsers/".$row['id'];
                ?>
                    <td scope='row'>
                        <select class='browser-default custom-select' onchange='changeTypeUser(<?php echo $i ?>, this.value)'>
                            <?php 
                            if($row['tipo'] == "amministratore"){
                            ?>
                            <option value='amministratore' selected>amministratore</option>
                            <option value='responsabile'>responsabile</option>
                            <?php  
                            }
                            else{
                            ?>
                            <option value='amministratore' >amministratore</option>
                            <option value='responsabile' selected>responsabile</option>
                            <?php
                            }
                            ?>
                       </select>
                    </td>
                    <td scope='row'><a class='btn btn-danger' href='#' data-target='#modal' data-toggle='modal' data-url='<?php echo $url; ?>'>elimina<a></td>
                <?php
                }
                else{
                ?>
                    <td scope="row"><?php echo $row['tipo'] ?></td>
                    <td scope='row'></td>
                <?php
                }
                ?>
                </tr>
            <?php
            $i++;
            }   
        ?>
    </tbody>
</table>
<div class="ml-2 mb-5">
    <a class="btn btn-success" href="<?php echo URL;?>utente/addUsers">AGGIUNGI</a>
</div>
<!-- Modal: popup che compare se clicco il pulsante CREA -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <!-- Contenitore che contiene le informazioni del popup -->
        <div class="modal-content">
            <!-- Contenitore header del popup -->
            <div class="modal-header btn-danger text-white">
                <!-- Titolo del popup -->
                <h5 class="modal-title" id="exampleModalLongTitle">Conferma</h5>
                <!-- Button per chiudere il popup -->
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Corpo del popup -->
            <div class="modal-body">
                Sei sicuro di voler eliminare l'utente?
            </div>
            <!-- Contenitore che contiene i pulsanti per confermare-->
            <div class="modal-footer">
                <!-- Pulsante per annullare -->
                <button type="button" class="btn border-danger text-danger" data-dismiss="modal">No, annulla</button>
                <!-- Pulsante per accettare -->
                <a class='btn btn-danger' id="confirm">Sì, elimina</a>
            </div>
        </div>
    </div>
</div>
<script>
     /**
      * Permette di modificare il tipo di utente.
      */
      function changeTypeUser(index, value){
        $.ajax({ 
            //metodo che permette di cambiare il tipo di utente (amministratore o responsabile).
            url: '<?php echo URL ."utente/changeType/"; ?>',
            data: {index: index, value:value},
            //utilizzo il metodo POST
            type: 'post'
        });  
      }
      
        
</script>


