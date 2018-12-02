$(document).ready(function(){

    var search = $("#search").val(); 
    var filterDeck = $("#filterDeck").val(); 
    var filterClass = $("#filterClass").val();

    // var myUrl = "deckFilter.php?search="+search+"filterDeck="+filterDeck

    $("tbody").attr("id", "result"); 

    // AJAX for filtering the deck 
    $("#filterDeck").change(function(event){
        event.preventDefault(); 
        filterDeck = $(this).val(); 
        search = $("#search").val(); 
        filterClass = $("#filterClass").val(); 
        var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass;
         $.get(myUrl, function(data, status){
            if(status == "success"){
                $("#result").html(data); 
                // $("#testCode").html(data);  //update query with AJAX for debugging purpose
                console.log(myUrl);     
            }
         });
    });
                   
    //AJAX for filtering the cabin
    $("#filterClass").change(function(event){
        event.preventDefault(); 
        filterClass = $(this).val(); 
        search = $("#search").val(); 
        filterDeck = $("#filterDeck").val();  
        var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass;
        $.get(myUrl, function(data, status){
            if(status == "success"){
                $("#result").html(data); 
                // $(".#testCode").html(data); //update query with AJAX for debugging purpose
                console.log(myUrl);       
            }
         });
    });

    //AJAX for filtering the cabin
    $("#search").keyup(function(event){
        event.preventDefault(); 
        search = $(this).val(); 
        filterDeck = $("#filterDeck").val(); 
        filterClass = $("#filterClass").val();   
        var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass;
         $.get(myUrl, function(data, status){
            if(status == "success"){
                $("#result").html(data); 
                // $("#testCode").html(data); //update query with AJAX for debugging purpose
                console.log("search:"+myUrl);      
            }
         });
    });

	//AJAX for modifying profile
    $("#editProfile").click(function(event){
        event.preventDefault(); // Prevents default action of event being triggered.
		console.log("Editing profile...");
        var myUrl = "includes/editProfile.php";
        $.post(myUrl, function(data, status) {
			
            if(status == "success"){
                $("#result").html(data); 
                console.log("data:"+data);      
            }
        });
    });
		//AJAX for submitting modified profile
     $('#result').on('click','#saveProfile', function(event){
        //event.preventDefault(); // Prevents default action of event being triggered.
		console.log("Saving profile...");
        var myUrl = "includes/displayProfile.php";
        $.post(myUrl, function(data, status) {
			
            if(status == "success"){
                $("#result").html(data); 
                console.log("data:"+data);      
            }
        });
    });

});





// });