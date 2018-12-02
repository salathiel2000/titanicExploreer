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
    $('#result').on('click', '#editProfile', function(event){
        event.preventDefault(); // Prevents default action of event being triggered.
		console.log("Editing profile...");
        var myUrl = "includes/editProfile.php";
        $.post(myUrl, function(data, status) {
			
            if(status == "success"){
                $("#result").html(data); 
                //console.log("data:"+data);      
            }
        });
    });

	var fNameEdit;
	var lNameEdit;
	var incomeEdit;
	var ageEdit;
	var genderEdit;

	//AJAX for submitting modified profile
    $('#result').on('click','#saveProfile', function(event){
		event.preventDefault(); // Prevents default action of event being triggered.
		console.log("Saving profile...");

		fNameEdit = $("#fName").val();
		lNameEdit = $("#lName").val();
		incomeEdit = $("#annualIncome").val();
		ageEdit = $("#age").val();

		if ($('input[id=male]:checked').val() == "male") {
			genderEdit = "male";
		} else {
			genderEdit = "female";
		}

		var myUrl = "includes/saveProfile.php";

		$.post(myUrl, { fName: fNameEdit, lName: lNameEdit, income: incomeEdit, age: ageEdit, gender: genderEdit}).done(function(data, status) {
        if (status == "success") {
			console.log(data);
		    $("#result").append(data);
            console.log("Saved...");      
        }
    });

	myUrl = "includes/displayProfile.php";
    $.post(myUrl, function(data, status) {
			
        if (status == "success") {
            $("#result").html(data); 
            //console.log("data:"+data);      
        }
    });
    });

});





// });