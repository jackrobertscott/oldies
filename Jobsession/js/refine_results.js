$(document).ready(function(){

	var location_js, category_js, salary_js;

	$('.ref_overflow li').click(function() {
	  if( $(this).parents().hasClass('over_loc') ){
	  	if( $(this).hasClass('ref_lime_select') ){
	  		$('.over_loc li').removeClass('ref_lime_select');
	  		location_js = 'null';
	  		$('[name=gen_loc_form]').val(location_js);
	  	}else{
			$('.over_loc li').removeClass('ref_lime_select');
			$(this).addClass('ref_lime_select');
			location_js = $(this).find('p').html();
			$('[name=gen_loc_form]').val(location_js);
		}
	  }else if( $(this).parents().hasClass('over_sal') ){
	  	if( $(this).hasClass('ref_lime_select') ){
	  		$('.over_sal li').removeClass('ref_lime_select');
	  		salary_js = 'null';
	  		$('[name=gen_sal_form]').val(salary_js);
	  	}else{
			$('.over_sal li').removeClass('ref_lime_select');
			$(this).addClass('ref_lime_select');
			salary_js = $(this).find('p').html();
			$('[name=gen_sal_form]').val(salary_js);
		}
	  }else{
	  	if( $(this).hasClass('ref_lime_select') ){
	  		$('.over_cat li').removeClass('ref_lime_select');
	  		category_js = 'null';
	  		$('[name=gen_cat_form]').val(category_js);
	  	}else{
			$('.over_cat li').removeClass('ref_lime_select');
			$(this).addClass('ref_lime_select');
			category_js = $(this).find('p').html();
			$('[name=gen_cat_form]').val(category_js);
		}
	  }
	});    

	function addHighlight($over){
		if( $over.hasClass('over_highlight') ){
		  $over.removeClass('over_highlight');
  	    } else {
   		  $over.addClass('over_highlight');
	    }
	}

	$('.over_loc_click').click(function() {
	  $('.over_loc').slideToggle();
	  var $over = $(this);
	  addHighlight($over);
	});

	$('.over_sal_click').click(function() {
	  $('.over_sal').slideToggle();
	  var $over = $(this);
	  addHighlight($over);
	});

	$('.over_cat_click').click(function() {
	  $('.over_cat').slideToggle();
	  var $over = $(this);
	  addHighlight($over);
	});

	$('.ref_submit').click(function() {
	  $('.random_c').text(category_js);
	  $('.random_s').text(salary_js);
	  $('.random_l').text(location_js);
	});

	var searchOpen = 'closed';
	if ($.session.get('searchOpen') != undefined) {
        searchOpen = $.session.get('searchOpen');
    }
	console.log(searchOpen);
	if( searchOpen == 'open' ){
		$(".toggle_search").hide();
	}

	$('.ref_title').click(function(){
		searchOpen = $.session.get('searchOpen');
		if( searchOpen == 'open' ){
			$.session.set('searchOpen', 'closed');
		}else if( searchOpen == 'closed' ){
			$.session.set('searchOpen', 'open');
		}else{
			$.session.set('searchOpen', 'open');
		}
		$('.toggle_search').toggle();
	});

});