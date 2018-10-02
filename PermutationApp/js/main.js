$(document).ready(function(){
	//set time element to current time
	$('#time').html(moment().format('dddd, D MMMM YYYY H:mm a'));
	//operate hide and open of results
	$('#hide').click(function(){
		$('#results').slideUp();
		$('#hide').hide();
		$('#show').show();
	});
	$('#show').click(function(){
		$('#results').slideDown();
		$('#show').hide();
		$('#hide').show();
	});
});