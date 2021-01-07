<?php
if (!isset($_SESSION['username'])) {
    header('Location: '.URL);
    exit;
}

$backupString = array('mai', 'giornaliero', 'settimanale', 'mensile');

?>
<h2 class="mt-2 ml-4">Collegamento ad un database</h2>
<!-- Form che permette di aggiungere o creare dei nuovi collegamenti su cui fare i backup giornalieri  -->
<form class="mt-5 ml-4" method="post" id="dblink-form" action="<?php echo URL; ?>gestione/createLinkDB">
    <span><label><strong>Hostname</strong></label></span><input type="text" name="hostname" placeholder="Hostname" required></br>
    <span><label><strong>Porta</strong></label></span><input type="text" name="port" placeholder="Porta" required></br>
    <span><label><strong>Username</strong></label></span><input type="text" name="username" placeholder="Username" required></br>
    <span><label><strong>Password</strong></label></span><input type="password" name="password" placeholder="Password" required></br>
    <span><label><strong>Nome</strong></label></span><input type="text" name="dbname" placeholder="Nome del database" required></br>
    <?php
    if(isset($_SESSION['errorMessage'])){
        echo '<p class="text-danger">'.$_SESSION['errorMessage'].'</p>';
        unset($_SESSION['errorMessage']);
    }
    ?>
    <input type="submit"  class="mt-4 btn btn-success" value="CREA COLLEGAMENTO">
</form>
<!-- Stampo una tabella che contiene i dati (nome database e username) dei collegamenti -->
<table  id="responsive" class="table table-bordered mt-5">
    <thead>
        <tr>
            <th scope="col">Database</th>
            <th scope="col-md-6 col-xs-6">Username</th>
            <th scope="col-md-5 col-xs-5">Modifica</th>
            <th scope="col-md-4 col-xs-4">Elimina</th>
            <th scope="col-md3 col-xs-3">Backup</th>
            <th scope="col-md2 col-xs-2">Ultimo backup</th>
        </tr>
    </thead>
    <tbody>
        <?php
            //Stampo tutti i collegamenti db creati
            $i = 1;
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <tr>
                    <td scope='row'><?php echo $row['dbname']; ?></td>
                    <td scope='row'><?php echo $row['dbuser']; ?></td>
                    <!-- button che richiama il metodo modifyDblink che permette di modificare il collegamento -->
                    <td scope='row'><a class='btn btn-info' href='<?php echo URL; ?>gestione/modifyDblink/<?php echo $i;?>'>modifica</a></td>
                    <!-- button che richiama il metodo deleteDblink che permette di eliminare il collegamento -->
                    <?php $url = URL."gestione/deleteDblink/".$i; ?>
                    <td scope='row'><a class='btn btn-danger' href='#' data-target='#modal' data-toggle='modal' data-url='<?php echo $url; ?>'>elimina</a></td>
                    <td scope='row'>
                        <!-- menu a tendina che permette si scegliere il tipo di backup (mai, giornaliero, settimanale, mensile) -->
                        <select class='browser-default custom-select' onchange='changeBackupFrequency(<?php echo $i ?>, this.value)'>
                            <?php 
                            $j = 0;
                            foreach($backupString as $value){ 
                                if($row['backupFrequency'] == $j){
                            ?>
                                <option value='<?php echo $j; ?>' selected><?php echo $value; ?></option>
                            <?php 
                                }
                                else{
                             ?>
                             <option value='<?php echo $j; ?>'><?php echo $value; ?></option>
                             <?php   
                                    
                                }
                                ++$j; 
                            }?>
                        </select>
                    </td>
                    <td  scope='row'><?php echo $row['lastBackupTime']; ?></td>
                </tr>
            <?php 
                ++$i;
            }
        ?>
    </tbody>
</table>
<!-- Modal: popup che compare solamente se clicco sul pulsante elimina -->
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
           Sei sicuro di voler eliminare il collegamento?
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
      * Permette di modificare il tipo di backup del collegamento.
      */
      function changeBackupFrequency(index, value){
       
        $.ajax({ 
            url: '<?php echo URL."gestione/modifyBackupFrequency/"; ?>',
            data: {index: index, value:value},
            type: 'post'
        });  
      } 
      
</script>