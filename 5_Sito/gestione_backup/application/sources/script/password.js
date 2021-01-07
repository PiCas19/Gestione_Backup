   
    //Quando il DOM (document object model) viene caricato 
    $(document).ready(function () {
       //se l'utente scrive nel campo che permette di confermare la password
       // (key della tastiera viene rilasciata) viene richiamato la funzione checkPasswordMatch
       $("#txtConfirmPassword").keyup(checkPasswordMatch);
    });    


    /**
     * Permette di verificare che le password dei due campi corrispondono.
     */
    function checkPasswordMatch() {
        //valore del campo che permette di inserire la nuova password
        var password = $("#txtNewPassword").val();
        //valore del campo che permette di confermare password
        var confirmPassword = $("#txtConfirmPassword").val();

        //se le due password non corrispondono
        if (password != confirmPassword)
            //viene modificato l'elemento html che ha come id CheckPasswordMatch
            //Viene modificato il valore testuale e il colore del testo
            $("#CheckPasswordMatch").html("Le password non corrispondono!").css('color', 'red');
        else
            $("#CheckPasswordMatch").html("Le password corrispondono").css('color', 'green');
    }