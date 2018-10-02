$(document).ready(function(){
	$(".friend-op-button").click(function(){
		$(".friend-op-wrap").slideToggle();
	});
	var titleTop, viewTop, wrapTop, hH;
	$(window).on("load resize scroll",function(e){
	    titleTop = $(".wrap-day-title").offset().top;
	    wrapTop = $(".wrap-days").offset().top;
	    viewTop = $(this).scrollTop();
	    hH = $(".wrap-header").height();
	    tH = $(".wrap-day-title").height();
	    console.log("days top: " + wrapTop + ", Title top: " + titleTop + ", view Top: " + viewTop);
	    if(viewTop >= wrapTop){
	    	$(".wrap-day-title").css({top: (viewTop - wrapTop)});
	    }else if(viewTop < wrapTop){
	    	$(".wrap-day-title").css({top: 0});
	    }
	});
	$(".sent").mouseover(function(){
		if($(this).hasClass("sent"))
		{
			$(this).val("Delete");
		}
	});
	$(".sent").mouseout(function(){
		if($(this).hasClass("sent"))
		{
			$(this).val("Pending");
		}
	});
	$(".friends").mouseover(function(){
		if($(this).hasClass("friends"))
		{
			$(this).val("Delete");
		}
	});
	$(".friends").mouseout(function(){
		if($(this).hasClass("friends"))
		{
			$(this).val("Friends");
		}
	});
	$('select').each(function(){
	    var $this = $(this), numberOfOptions = $(this).children('option').length;
	  
	    $this.addClass('select-hidden'); 
	    $this.wrap('<div class="select"></div>');
	    $this.after('<div class="select-styled"></div>');

	    var $styledSelect = $this.next('div.select-styled');
	    $styledSelect.text($this.children('option').eq(0).text());
	  
	    var $list = $('<ul />', {
	        'class': 'select-options'
	    }).insertAfter($styledSelect);
	  
	    for (var i = 0; i < numberOfOptions; i++) {
	        $('<li />', {
	            text: $this.children('option').eq(i).text(),
	            rel: $this.children('option').eq(i).val()
	        }).appendTo($list);
	    }
	  
	    var $listItems = $list.children('li');
	  
	    $styledSelect.click(function(e) {
	        e.stopPropagation();
	        $('div.select-styled.active').each(function(){
	            $(this).removeClass('active').next('ul.select-options').hide();
	        });
	        $(this).toggleClass('active').next('ul.select-options').toggle();
	    });
	  
	    $listItems.click(function(e) {
	        e.stopPropagation();
	        $styledSelect.text($(this).text()).removeClass('active');
	        $this.val($(this).attr('rel'));
	        $list.hide();
	        //console.log($this.val());
	    });
	  
	    $(document).click(function() {
	        $styledSelect.removeClass('active');
	        $list.hide();
	    });
	});
});