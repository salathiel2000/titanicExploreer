$(document).ready(function(){

    var search = $("#search").val(); 
    var filterDeck = $("#filterDeck").val(); 
    var filterClass = $("#filterClass").val(); 
    var pageNum = 27;  
    // var myUrl = "deckFilter.php?search="+search+"filterDeck="+filterDeck

    $("tbody").attr("id", "result"); 

    // AJAX for filtering the deck 
    $("#filterDeck").change(function(event){
        event.preventDefault(); 
        filterDeck = $(this).val(); 
        search = $("#search").val(); 
        filterClass = $("#filterClass").val(); 
        pageNum = parseInt($("#pages").text());
        var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum;
         $.get(myUrl, function(data, status){
            if(status == "success"){
                var res = $.parseJSON(data); 
                $("#result").html(res['output']); 
                $("#pages").html(res['pages']);
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
        pageNum = parseInt($("#pages").text());
        var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum;
        $.get(myUrl, function(data, status){
            if(status == "success"){
                var res = $.parseJSON(data); 
                $("#result").html(res['output']);
                $("#pages").html(res['pages']);
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
        pageNum = parseInt($("#pages").text());
        var myUrl = "deckFilter.php?search="+search+"&filterDeck="+filterDeck+"&filterClass="+filterClass+"&pageNum="+pageNum;
         $.get(myUrl, function(data, status){
            if(status == "success"){
                var res = $.parseJSON(data); 
                $("#result").html(res['output']);
                $("#pages").html(res['pages']);
                // $("#testCode").html(data); //update query with AJAX for debugging purpose
                console.log("search:"+myUrl);      
            }
         });
    });

});
