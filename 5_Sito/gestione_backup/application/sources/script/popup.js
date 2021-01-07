//Quando il DOM (document object model) viene caricato 
$(document).ready(function() {
    //Quando appare un elemento HTML con id cancelDblinkModal e corrisponde ad un modal.
    $('#modal').on('show.bs.modal', function(e) {
        //corrisponde al url che permette di eseguire un metodo del controller
        var url = '';
        //evento dei pulsanti
        var eventButton = $(e.relatedTarget);
        if (typeof eventButton.data('url') !== 'undefined') {
          //leggo il valore del url
          url = eventButton.data('url');
          //se clicco il pulsante che ha id confirm eseguo l'url
          $("#confirm").attr("href", url);
          //console.log(url);
        }
    })
});



