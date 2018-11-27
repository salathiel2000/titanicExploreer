$(document).ready(function(){

    var filterDeck; 
    var myUrl = "deckFilter.php?search="+search+"filterDeck="+filterDeck

    $("#filterDeck").change(function(event){
        event.preventDefault(); 
        filterDeck = $(this).val(); 
        // var myUrl = "deckFilter.php?serch=&filterDeck="+filterDeck;
         $.get(myUrl, function(data, status){
            if(status == "success"){
                $("#result").html(data); 
                console.log(data);      
            }
         });
    });

});