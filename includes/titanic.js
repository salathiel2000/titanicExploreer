$(document).ready(function(){

    $("#filterDeck").change(function(event){
        event.preventDefault(); 
        var filterDeck = $(this).val(); 
        var myUrl = "deckFilter.php?serch=&filterDeck="+filterDeck;
         $.get(myUrl, function(data, status){
            if(status == "success"){
                $("#result").html(data); 
                console.log(data);  
                
            }
         });
    });

});