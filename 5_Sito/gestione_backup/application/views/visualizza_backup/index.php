<?php
if (!isset($_SESSION['username'])) {
    header('Location: '.URL);
    exit;
} else {
    echo "<p class='ml-2'>Benvenuto utente ".$_SESSION['username']."</p></br>";
 
}

?>
<div class="mr-2">
    <label class="ml-2">Nome database: </label>
    <select class="browser-default custom-select" style="width:135px;" id="filterByNameInput">
       <option value="" selected="true">Nessun filtro</option>
    <?php
    //permette di ciclare l'array files che contiene 
    // altri array (uno per ogni collegamento)
    for($i = 0; $i < count($files); $i++){
      //permette di ciclare gli array di ogni collegamento
      foreach($files[$i] as $key => $value){
         //permette di ciclare i vari backup di ogni collegamento
         for($j = 0; $j < count($value['name']); $j++){?>
            <!-- opzioni del filtro: nome del collegamento -->
            <option value="<?php echo $value["name"][$j]; ?>"><?php echo $value["name"][$j]; ?></option>
         <?php
         break;
         }
      }
    }
    ?>
    </select>
    <input type="text" class="float-right mb-2" id="filterInput" placeholder="Cerca">
</div>
<table id="backup" class="table table-bordered mt-3">
        <thead>
            <tr>
                <th scope="col">Nome database</th>
                <th scope="col-md-5 col-xs-5">Backup</th>
                <th scope="col-md-4 col-xs-4">Log</th>
                <th scope="col-md-3 col-xs-3">Stato</th>
                <th scope="col-md-2 col-xs-2">Data backup</th>
            </tr>
        </thead>
        <tbody id="backupTable">
        <?php 
            //permette di ciclare l'array files che contiene 
            // altri array (uno per ogni collegamento)
            for($i = 0; $i < count($files); $i++){
               //permette di ciclare gli array di ogni collegamento
               foreach($files[$i] as $key => $value){
                  //permette di ciclare i vari backup di ogni collegamento
                  for($j = 0; $j < count($value['name']); $j++){
                     echo "<tr>";
                     //nome del collegamento
                     echo "<td scope='row'>".$value["name"][$j]."</td>";
                     //verifico che esiste il backup
                     if($value["backup"][$j] != " " && $value["log"][$j] != " "){
                        //file di backup (file sql)
                        echo "<td scope='row'><a href='".URL."visualizza_backup/getContentFile/".$value["name"][$j]."/".$value["backup"][$j]."'>".$value["backup"][$j]."</a></td>";
                        //file log (file txt)
                        echo "<td scope='row'><a href='".URL."visualizza_backup/getContentFileLog/".$value["name"][$j]."/".$value["log"][$j]."'>".$value["log"][$j]."</a></td>";
                     }
                     else{
                        echo "<td scope='row'>Nesuno file di backup</td>";
                        echo "<td scope='row'>Nessuno file log</td>";
    
                     }
                     //colore dello stato
                     $colorStatus = "";
                     //messaggio stato
                     $message = "";
                     //se il backup è andato a buon fine 
                     if($value["status"][$j]){
                        //colore verde 
                        $colorStatus = "btn-success";
                        $message = "Sì";
    
                     }
                     else{
                        //colore rosso
                        $colorStatus = "btn-danger"; 
                        $message = "No";
                     }
                     //segnale dello stato del backup
                     echo "<td scope='row'><button type='button' class='btn " . $colorStatus . " rounded-circle rounded-xl'>".$message."</button></td>";
                     //data del backup
                     echo "<td scope='row'>".$value["date"][$j]."</td>";
                     echo "<tr>";
                  }
               }
            }   
        ?>
    </tbody>
</table>
<div class="ml-2 mb-5">
    <a class='btn btn-secondary' href="<?php URL ?>visualizza_backup/createReport">REPORT</a><br><br>
</div>

<script>
   
   
   $(document).ready(function(){
         //Quando l'utente scrive nel campo ricerca (id: filterInput)
         $("#filterInput").on("keyup", function() {
           //leggo il contenuto del campo
           var value = $(this).val().toLowerCase();
           //Applico il filtro per ogni riga della tabella
           $("#backupTable tr").filter(function() {
             //visualizzo solamente le righe della tabella 
             //che corrisponde al valore inserito nel campo ricerca
             $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
           });
         });
         
         //Quando l'utente scrive nel campo ricerca (id: filterInput)
         $("#filterByNameInput").change(function() {
           //leggo il contenuto del campo
           var value = $(this).val().toLowerCase();
           //Applico il filtro per ogni riga della tabella
           $("#backupTable tr").filter(function() {
             //visualizzo solamente le righe della tabella 
             //che corrisponde al valore inserito nel campo ricerca
             $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
           });
         });
         
         var x = document.getElementById("snackbar");
         x.className = "show";
         setTimeout(function(){ x.className = x.className.replace("show", ""); }, 6000);
   });
       
</script>