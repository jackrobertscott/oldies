$(document).ready(function(){
	$('.hour ul li').click(function(){
		var $hour = $(this);
		var cns = $hour.attr('class').split(/\s+/);
		$.ajax({
			type: 'POST',
			url: 'includes/ajax/AjaxHour.class.php',
			data: { courseId : cns[0], uniId : cns[1], unitCode : cns[2], courseCode : cns[3] },
			dataType: 'json',
			success: function (data) {
				if(data.message != "")
				{
					console.log(data.message);
				}else{
					$hour.parent().children().removeClass("highlight");
					if(data.status == "inserted")
					{
						$hour.addClass("highlight");
						$hour.parent().parent().css("background-color", "#dff5fa");
					}else{
						$hour.removeClass("highlight");
						$hour.parent().parent().css("background-color", "");
					}
				}
			}
		});
	});
	$('.friendReq').submit(function(event){
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'includes/ajax/AjaxFriend.class.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				if(data.message != "")
				{
					console.log(data.message);
				}else{
					if(data.status == "inserted")
					{
						$(".friend"+data.receiver).addClass("sent").val("Pending");
					}else{
						$(".friend"+data.receiver).removeClass("sent").removeClass("friends").val("Add");
					}
				}
			}
		});
	});
	$('.friendAdd').submit(function(event){
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'includes/ajax/AjaxAddFriend.class.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				if(data.message != "")
				{
					console.log(data.message);
				}else{
					$(".friendrem"+data.receiver).addClass("friends").val("Friends");
					$(".friendadd"+data.receiver).remove();
				}
			}
		});
	});
	$('.friendRem').submit(function(event){
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'includes/ajax/AjaxRemFriend.class.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				if(data.message != "")
				{
					console.log(data.message);
				}else{
					$(".reqNum"+data.receiver).remove();
				}
			}
		});
	});
	$('.course-search-options').on('click', 'li', function(){
		$.ajax({
			type: 'POST',
			url: 'includes/ajax/AjaxAddPref.class.php',
			data: "courseId=" + $(this).attr('class'),
			dataType: 'json',
			success: function (data) {
				if(data.message != "")
				{
					console.log(data.message);
				}else{
					if(data.optArray != 'null'){
						var optString = "", cCode = "", unitId = "";
						var parsed = JSON.parse(data.optArray);
						$.each(parsed, function(){
							$.each(this, function(k, v) {
					  			if(k == 'CourseCode'){
					  				cCode = v;
					  			}else{
					  				unitId = v;
					  			}	
						  	});
						  	optString += '<li><p>' + cCode + '</p><div class="unitId_' + unitId + '"><p>REMOVE</p></div></li>';
						});
						$(".course-active").html(optString);
					}else{
						$(".course-active").html("");
					}
				}
			}
		});
	});
	$('.course-active').on('click', 'div', function(){
		$.ajax({
			type: 'POST',
			url: 'includes/ajax/AjaxRemPref.class.php',
			data: "courseId=" + $(this).attr('class'),
			dataType: 'json',
			success: function (data) {
				if(data.message != "")
				{
					console.log(data.message);
				}else{
					if(data.optArray != 'null' && data.optArray != '[]' && data.optArray != ''){
						var optString = "", cCode = "", unitId = "";
						var parsed = JSON.parse(data.optArray);
						$.each(parsed, function(){
							$.each(this, function(k, v) {
					  			if(k == 'CourseCode'){
					  				cCode = v;
					  			}else{
					  				unitId = v;
					  			}	
						  	});
						  	optString += '<li><p>' + cCode + '</p><div class="unitId_' + unitId + '"><p>REMOVE</p></div></li>';
						});
						$(".course-active").html(optString);
					}else{
						$(".course-active").html("");
					}
				}
			}
		});
	});
	$('#courseSearch').keyup(function(event){
		if (event.which == 13) {
   			event.preventDefault();
  		}
		$.ajax({
			type: 'POST',
			url: 'includes/ajax/AjaxRetrieveCourses.class.php',
			data: "courseSearch=" + $(this).val(),
			dataType: 'json',
			success: function (data) {
				if(data.message != "")
				{
					console.log(data.message);
				}else{
					if(data.optArray != 'null'){
						var optString = "", i = 0, cCode = "", unitId = "", cName = "";
						var parsed = JSON.parse(data.optArray);
						$.each(parsed, function(){
							if(i < 5) //must be an even number or the id will not be placed in last item
						  	{
							  	$.each(this, function(k, v) {
						  			if(k == 'CourseCode'){
						  				cCode = v;
						  			}else if(k == 'CourseName'){
						  				cName = v;
						  			}else{
						  				unitId = v;
						  			}	
							  	});
							  	optString += '<li class="unitId_' + unitId + '"><p>' + cCode + '<br><span style="font-size: 11px;">' + cName + '</span></p><p style="float: right;font-size: 14px;"><span class="icon-plus-outline"></span></p></li>';
							}
						  	i++;
						});
						$(".course-search-options").html(optString);
					}else{
						$(".course-search-options").html("");
					}
				}
			}
		});
	});
});