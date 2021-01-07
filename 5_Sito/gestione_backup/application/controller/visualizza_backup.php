<?php
    class Visualizza_backup{

        /**
         * Permette di creare l'index della pagina visualizza_backup
         */
        function __construct()
        {
        }

        /*
        * Permette di creare l'index della pagina visualizza backup
        */
        public function index(){
            require './application/models/backup_model.php';
            require './application/models/database_model.php';
            //Connessione database
            $db = new Database_Model();
            $conn = $db->getConnection();
            //Leggo i dati che contiene la tabella db_link
            $db_link =  new Backup_Model();
            $sth = $db_link->getAllDataDbLink($conn);  
            //Salvo in un array bidimensionale tutti i file di backup dei vari collegamenti
            $files = $this->getDbLinksFileBackup($sth,$db_link, $conn);
            
            
            
            require './application/views/header.php';
            require './application/views/visualizza_backup/index.php';
            require './application/views/footer.php';
        }


        /**
         * Permette di ricavare tutti i file di backup di ogni collegamento
         */ 
        public function getDbLinksFileBackup($sth, $db_link){
            //array bidimensionale che conterrà tutti i file di backup dei collegamenti
            $all_file = array();
            //indice.
            $i = 0;
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                //Ritorna i file (SQL + log) del collegamento
                $all_file[$i] =  $db_link->getDbLinkBackupFile($row['dbname']);
                ++$i;
            }
            return $all_file;
        }

        /**
         * Permette di visualizzare il contenuto del file SQL.
         */
        public function getContentFile($db_link, $file){
            $filename = './application/sources/backup/'.$db_link .'/'.$file;
            $content = file_get_contents($filename);
            $content =  nl2br($content);
            require './application/views/header.php';
            require './application/views/visualizza_backup/visualizza.php';
        }

        /**
         * Permette di visualizzare il contenuto del file log.
         */
        public function getContentFileLog($db_link, $file){
            $filename = './application/sources/backup/'.$db_link .'/log/'.$file;
            $content = file_get_contents($filename);
            $content = nl2br($content);
            require './application/views/header.php';
            require './application/views/visualizza_backup/visualizza.php';
        }
           
        /**
         * Permette di creare il report.
         */
        public function createReport(){

            require './application/sources/fpdf/fpdf.php';
            require './application/models/backup_model.php';
            require './application/models/database_model.php';

            //Connessione database
            $db = new Database_Model();
            $conn = $db->getConnection();
            //Leggo i dati che contiene la tabella db_link
            $db_link =  new Backup_Model();
            $sth = $db_link->getAllDataDbLink($conn); 
            $sthData =  $db_link->getAllDataDbLink($conn); 

            //Nome del file pdf
            $filenamePDF = "report-backup.pdf";
            $this->structureFilePDF($filenamePDF, $sth,$sthData, $db_link);
            

        }
        
        public function backVisualizza(){
            header("Location: ".URL."visualizza_backup");
        }

        
        /**
         * Permette dci creare la struttura di una semplice tabella. 
         */ 
        function BasicTable($array_username, $data, $pdf)
        {
            $index = 0;
            // Dati
           for($i = 0; $i < count($data); $i++){
               foreach($data[$i] as $key => $value){
            
                    for($j = 0; $j < count($value['name']); $j++){
                        $pdf->SetFont('Arial','',12);
                        $pdf->Cell(10);
                        $pdf->Cell(42,8,$value['name'][$j],1);
                        $pdf->Cell(42,8, $array_username[$index],1);
                        $pdf->Cell(42,8,utf8_decode($value['status'][$j]==1?'Sì':'No'),1);
                        $pdf->Cell(42,8,$value['date'][$j],1);
                        $pdf->Ln();
                    }
                    ++$index;
                }
            }
        }
        
        /**
         * Implementa la struttura del pdf.
         */
        public function structureFilePDF($filenamePDF, $sth,$sthData, $db_link){

            $pdf = new FPDF();
            //Aggiungo un numero delle pagine
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $array_username = array();
            $data = array();
            $j = 0;
            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                $array_username[$j] = $row['dbuser'];
                ++$j;
            }
            $data = $this->getDbLinksFileBackup($sthData, $db_link);
            $this->BasicTable($array_username, $data, $pdf);
            $pdf->Output("I", $filenamePDF);
        }

        /*
         * Permette di fare il logout
         */
        public function logout(){
            unset($_SESSION['tipo']);
            unset($_SESSION['username']);
            //distruggo tutte le sessioni
            session_destroy();
            //Ritorno alla pagina di login
            header('Location: '.URL);
        }
    }
?>