$(document).ready(function(){

	$('.favourite').submit(function(event) {
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'includes/fav_job_ajax.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data.message != ""){
					if( !$(".errMesDiv").length ){
						var errMes = '<div class="right_art"><div class="text errMesDiv"><p>' + data.message + '</p></div><!-- text --></div><!-- art_right -->';
						$(".main_right").prepend( errMes );
					} else {
							var errMes = '<p>' + data.message + '</p>';
							$(".errMesDiv").html( errMes );
					}       
				} else {
					if(data.state == true){
						$("input[value='" + data.job_id + "']").parent().find(".fav_ajax").addClass("fav_true");									
					} else if(data.state == false) {
						$("input[value='" + data.job_id +"']").parent().find(".fav_ajax").removeClass("fav_true");
					}   
				}
							
			}
		});
	});

	$('.app_form').submit(function(event) {
		event.preventDefault();
		$.ajax({
			type: 'POST',
			url: 'includes/quickCV_ajax.php',
			data: $(this).serialize(),
			dataType: 'json',
			success: function (data) {
				console.log(data);
				if(data.message != ""){
					var appHtml = 	'<div class="black_screen"><div class="quickCV">';
					appHtml += '<div class="title"><h1>An error has occured</h1><div class="exit" onclick="deleteScreen()"><p>X</p></div></div>';
					appHtml += '<br><p>' + data.message + '</p></div></div>';
					$(".app_holder").html( appHtml );    
				} else {
					var appHtml = 	'<div class="black_screen"><div class="quickCV">';
					appHtml += '<div class="title"><h1>' + data.fname + ' ' + data.lname + '</h1><div class="exit" onclick="deleteScreen()"><p>X</p></div></div>';
					appHtml += '<p>' + data.email + '</p><hr>';
					if(data.res != ""){
						appHtml += '<p><a href="' + data.res + '" target="_blank">Get Uploaded Resume</a></p>';
					}
					appHtml += '<br><h3>About</h3><br><p>' + data.about + '</p><br>';
					appHtml += '<h3>Education</h3><br><p>' + data.edu + '</p><br>';
					appHtml += '<h3>Experience</h3><br><p>' + data.exp + '</p></div></div>';
					$(".app_holder").html( appHtml );
				}
							
			}
		});
	});

});

function deleteScreen() {
	$('.black_screen').remove()
}