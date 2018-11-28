$(document).ready(function(){

    var search = ""; 
    var filterDeck = ""; 
    var filterClass = ""; 
    // var myUrl = "deckFilter.php?search="+search+"filterDeck="+filterDeck

    // AJAX for filtering the deck 
    $("#filterDeck").change(function(event){
        event.preventDefault(); 
        filterDeck = $(this).val(); 
        var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass=";
         $.get(myUrl, function(data, status){
            if(status == "success"){
                $("#result").html(data); 
                console.log("filterDeck:"+myUrl);     
            }
         });
    });

    // $("#filterDeck").change(function(event){
    //     event.preventDefault(); 
    //     filterDeck = $(this).val(); 
    //     var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass=";
    //     $.ajax({
    //         type     : 'GET',
    //         url      : myUrl,
    //         data     : {name : name , homeDest : homeDest , cabinNumber : cabinNumber}, 
    //         success  : function(response){
    //                     // now update user record in table 
    //                     $('#result').children('#passengerName').text(name);
    //                     $('#result').children('#homeDest').text(homeDest);
    //                     $('#result').children('#cabinNumber').text(cabinNumber);
    //                     // $('#result').children('#class').text(class);

    //                }
    //         }); 
    //     });

                   
    //AJAX for filtering the cabin
    $("#filterClass").change(function(event){
        event.preventDefault(); 
        filterClass = $(this).val(); 
        var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass;
         $.get(myUrl, function(data, status){
            if(status == "success"){
                $("#result").html(data); 
                console.log("filterClass:"+myUrl);      
            }
         });
    });

    //AJAX for filtering the cabin
    $("#search").keyup(function(event){
        event.preventDefault(); 
        search = $(this).val(); 
        var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass;
         $.get(myUrl, function(data, status){
            if(status == "success"){
                $("#result").html(data); 
                console.log("search:"+myUrl);      
            }
         });
    });

});



// });