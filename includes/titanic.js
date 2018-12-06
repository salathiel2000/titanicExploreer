$(document).ready(function(){

    var search = $("#search").val(); 
    var filterDeck = $("#filterDeck").val(); 
    var filterClass = $("#filterClass").val(); 
    var pageNum = 27;    
    var currPage = 1; 
    var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum+"&currPage="+currPage; 

    $("tbody").attr("id", "result");

    // AJAX for filtering the deck 
    $("#filterDeck").change(function(event){
        event.preventDefault(); 
        filterDeck = $(this).val(); 
        search = $("#search").val(); 
        filterClass = $("#filterClass").val();
        currPage = 1; //ensure that results always start on first page
        myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum+"&currPage="+currPage; 
        $.get(myUrl, function(data, status){
            if(status == "success"){
                var res = $.parseJSON(data); 
                $("#result").html(res['output']);
				$("#pages").html("/ ");
                $("#pages").append(res['pages']);
                pageNum = res['pages'];
                //remove page numbers 
                $("#chooseNumber option").remove(); 
                //add new page numbers based on amount of results
                for(var i=1; i<=pageNum; i++){
                    $("#chooseNumber").append("<option id=\""+i+"\">"+i+"</option>")
                }
                var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum+"&currPage="+currPage;
                console.log(myUrl);     
            }
         });
    });
                   
    //AJAX for filtering the class
    $("#filterClass").change(function(event){ 
        event.preventDefault(); 
        filterClass = $(this).val(); 
        search = $("#search").val(); 
        filterDeck = $("#filterDeck").val();  
        pageNum = $("#pages").text();
        currPage = 1; //ensure that results always start on first page
        myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum+"&currPage="+currPage; 
        $.get(myUrl, function(data, status){
            if(status == "success"){
                var res = $.parseJSON(data); 
                $("#result").html(res['output']);
				$("#pages").html("/ ");
                $("#pages").append(res['pages']);
				pageNum = res['pages'];
                //remove page numbers 
                $("#chooseNumber option").remove(); 
                //add new page numbers based on amount of results
                for(var i=1; i<=pageNum; i++){
                    $("#chooseNumber").append("<option id=\""+i+"\">"+i+"</option>")
                }
                myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum+"&currPage="+currPage; 
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
        pageNum = $("#pages").text();
        currPage = 1; //ensure that results always start on first page
        myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum+"&currPage="+currPage; 
         $.get(myUrl, function(data, status){
            if(status == "success"){
                var res = $.parseJSON(data); 
                $("#result").html(res['output']);
				$("#pages").html("/ ");
                $("#pages").append(res['pages']);
				pageNum = res['pages'];
                //remove page numbers 
                $("#chooseNumber option").remove(); 
                //add new page numbers based on amount of results
                for(var i=1; i<=pageNum; i++){
                    $("#chooseNumber").append("<option id=\""+i+"\">"+i+"</option>")
                }
                myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum+"&currPage="+currPage; 
                console.log("search:"+myUrl);      
            }
         });
    });

    //AJAX for choosing a page number to go to from dropdown
    $("#chooseNumber").change(function(event){
        currPage = $(this).val(); 
        myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum+"&currPage="+currPage; 
        $.get(myUrl, function(data, status){
            if(status == "success"){
                var res = $.parseJSON(data); 
                $("#result").html(res['output']); 
				$("#pages").html("/ ");
                $("#pages").append(res['pages']);
				var pageNum = res['pages'];
                var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum+"&currPage="+currPage;
                console.log(myUrl);   
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
                $("#bottom-half").html(data); 
                //console.log("data:"+data);      
            }
        });
    });

    //AJAX for showing ticket when row in explore table is clicked
	$('.manifest-table').on('click', '.row-listener', function(event){
        event.preventDefault(); // Prevents default action of event being triggered.
		console.log("Opening...");
		var pid = $(this).attr("id");
        var myUrl = "includes/ticketModal.php";
        
        $('html, body').css({
            overflow: 'hidden',
            height: '100%'
        }); 

        $.post(myUrl, { ticketPid: pid }).done(function(data, status) {
			
            if(status == "success"){
                $("#resulting-ticket").html(data); 
                //console.log("data:"+data);   
                $("#modal").click(function(){
                    $(".ticket").hide(); 
                    $("#modal").hide(); 
                    $('html, body').css({
                        overflow: 'auto',
                        height: '100%'
                    }); 
                }); 
            }
        });
    });

    //AJAX for showing ticket when row in address book is clicked
    $('.ab-table').on('click', '.row-listener', function(event){
        event.preventDefault(); // Prevents default action of event being triggered.
		console.log("Opening...");
		var pid = $(this).attr("id");
        var myUrl = "includes/ticketModal.php";

        $('html, body').css({
            overflow: 'hidden',
            height: '100%'
        }); 

        $.post(myUrl, { ticketPid: pid }).done(function(data, status) {
			
            if(status == "success"){
                $("#resulting-ticket").html(data); 
                //console.log("data:"+data);  
                $("#modal").click(function(){
                    $(".ticket").hide(); 
                    $("#modal").hide(); 
                    $('html, body').css({
                        overflow: 'auto',
                        height: '100%'
                    }); 
                });     
            }
        });
    });

    //AJAX request for showing tickets when More Info is clicked on lobby page
    $('.lobbyNames').on('click', '.individualLink', function(event){
        event.preventDefault(); // Prevents default action of event being triggered.
		console.log("Opening...");
		var pid = $(this).attr("id");
        var myUrl = "includes/ticketModal.php";
        
        $('html, body').css({
            overflow: 'hidden',
            height: '100%'
        }); 
        $.post(myUrl, { ticketPid: pid }).done(function(data, status) {
			
            if(status == "success"){
                $("#resulting-ticket").html(data); 
                //console.log("data:"+data);   
                $("#modal").click(function(){
                    $(".ticket").hide(); 
                    $("#modal").hide(); 
                    $('html, body').css({
                        overflow: 'auto',
                        height: '100%'
                    }); 
                }); 
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

    //Close menu when clicked
    $("#closeMenu").click(function(){
        console.log("hi");
        $("#menuOverlay").hide(); 
        $('html, body').css({
            overflow: 'auto',
            height: '100%'
        }); 
    }); 
    
    //open menu when menu is clicked
    $("#openMenu").click(function(){
        $("#menuOverlay").show(); 
        $('html, body').css({
            overflow: 'hidden',
            height: '100%'
        }); 
    })


});


