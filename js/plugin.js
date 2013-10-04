var keywordVal = "";
var typeVal = "all";
var sortVal = "recent";
var payVal = "any";
var newQVal = false;
var timeoutReference;

(function ($) {
	$(function () {			
			// We use the change attribute so that the event handler fires
			// whenever the checkbox or its associated label is clicked.
			if($(".visible-phone").css("display") != "none"){
				$("#keywordSearchSubmit").hide();
			}
			
			$("#paySubmit").hide();
			
			$('#keywordSearch').keypress(function() {
				var el = this; // copy of this object for further usage

				if (timeoutReference) clearTimeout(timeoutReference);
				timeoutReference = setTimeout(function() {
					$("#jobsFound").text("loading jobs");
					doneTyping.call(el);
				}, 2000);
			});
			$('#keywordSearch').blur(function(){
				$("#jobsFound").text("loading jobs");
				doneTyping.call(this);
			});
			
			$("#keywordSearchSubmit").click(function(e){
				e.preventDefault();
				$("#jobsFound").text("loading jobs");
				keywordVal = $("#keywordSearch").val();
				sendAndProcess();
			});
			
			$(".typeButton").click(function(e){
				e.preventDefault();
				$("#jobsFound").text("loading jobs");
				typeVal = $(this).attr("id");
				$(".typeButton").removeClass("active");
				$(this).addClass("active");
				sendAndProcess();
			});
			
			$(".sortButton").click(function(e){
				e.preventDefault();
				$("#jobsFound").text("loading jobs");
				sortVal = $(this).attr("id");
				$(".sortButton").removeClass("active");
				$(this).addClass("active");
				sendAndProcess();
			});
			
			$("#pay").change(function(e){
				$("#jobsFound").text("loading jobs");
				payVal = $(this).val();
				sendAndProcess();
			});
			
			$("#paySubmit").click(function(e){
				e.preventDefault();
				$("#jobsFound").text("loading jobs");
				payVal = $("#pay").val();
				sendAndProcess();
			});
			
			$("#newSubmit").click(function(e){
				e.preventDefault();
				$("#jobsFound").text("loading jobs");
				newQVal = $(this).hasClass("true");
				if(newQVal == true){
					$(this).removeClass("true");
					$(this).addClass("false");
					$(this).html('<i class="icon-remove"></i> Show all posts');
				}else{
					$(this).removeClass("false");
					$(this).addClass("true");
					$(this).html('Show posts suitable for newly qualified staff');
				}
				sendAndProcess();
			});
			$("#resetSearch").click(function(e){
				e.preventDefault();
				$("#jobsFound").text("loading jobs");
				keywordVal = "";
				typeVal = "all";
				sortVal = "recent";
				payVal = "any";
				newQVal = false;
				
				
				$("#keywordSearch").val("");
				$(".typeButton").removeClass("active");
				$(".typeButton#all").addClass("active");
				$(".sortButton").removeClass("active");
				$(".sortButton#recent").addClass("active");
				$("#pay").val("any");
				$("#newSubmit").removeClass("false");
				$("#newSubmit").addClass("true");
				$("#newSubmit").html("Show posts suitable for newly qualified staff");
				sendAndProcess();
			});
	});
	
	function sendAndProcess(){
		// Initial the request to mark this this particular post as read
		console.log("keyword:"+keywordVal+" type:"+typeVal+" sort:"+sortVal+" pay:"+payVal+" newq:"+newQVal);
		$.post(ajaxurl, {
			action: 'refresh_job_search',
			keyword: keywordVal,
			type: typeVal,
			sort: sortVal,
			pay: payVal,
			newq: newQVal
		}, function (data) {
			var respJSON = $.parseJSON(data);
			console.log(respJSON.status);
			
			if(respJSON.status != "error"){
				var htmlToWrite = "";
				$.each(respJSON.items, function(key, val) {
				
					//build the html to return	
					htmlToWrite += "<div class=\"row job\">";
					htmlToWrite += "<h3><a href=\"" + val.url + "\">" + val.title + "</a></h3>";
					htmlToWrite += "<p>" + val.desc + "</p>";
					htmlToWrite += "<dl class=\"dl-horizontal\">";
					htmlToWrite += "<dt>Job Type</dt><dd>" + val.type + "</dd>";
					htmlToWrite += "<dt>Salary</dt><dd>" + val.salary + "</dd>";
					htmlToWrite += "<dt>Close date</dt><dd>" + val.close + "</dd>";
					htmlToWrite += "</dl>";
					htmlToWrite += "<p><a href=\"" + val.url + "\" class=\"btn btn-info pull-right\">Apply now</a></p>";
					htmlToWrite += "</div>";
				});
				
				//$("#jobsListing").html("").fadeOut("slow");
				$("#jobsListing").html(htmlToWrite).fadeIn("slow");
				
				$("#jobsFound").text(respJSON.status).fadeIn("slow");
			}else{
				$("#jobsFound").text("0 jobs found");
				$("#jobsListing").html("<h3>No results found</h3><p>We currently don't have any positions in the criteria your searched for. Please broaden your search criteria and try again.</p>");
			}
			
			
			
			// If the server returns '1', then we can mark this post as read, so we'll hide the checkbox
			// container. Next time the user browses the index, this post won't appear
					
		});
	}
	
	function doneTyping(){
    	// we only want to execute if a timer is pending
		if (!timeoutReference){
        	return;
		}
		// reset the timeout then continue on with the code
		timeoutReference = null;

		//
		// Code to execute here
    	//
		keywordVal = $(this).val();
		sendAndProcess();
	}
}(jQuery));

