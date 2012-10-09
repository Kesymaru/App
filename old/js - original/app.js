$(function(){
	var menuStatus;
	
	$("a.showMenu").click(function(){
		if(menuStatus != true){				
		$(".ui-page-active").animate({
			marginLeft: "165px",
		  }, 300, function(){menuStatus = true});
		  return false;
		  } else {
			$(".ui-page-active").animate({
			marginLeft: "0px",
		  }, 300, function(){menuStatus = false});
			return false;
		  }
	});

	$('.pages').live("swipeleft", function(){
		if (menuStatus){	
		$(".ui-page-active").animate({
			marginLeft: "0px",
		  }, 300, function(){menuStatus = false});
		  }
	});
	
	$('.pages').live("swiperight", function(){
		if (!menuStatus){	
		$(".ui-page-active").animate({
			marginLeft: "165px",
		  }, 300, function(){menuStatus = true});
		  }
	});
	
	$("#menu li a").click(function(){
		var p = $(this).parent();
		if($(p).hasClass('active')){
			$("#menu li").removeClass('active');
		} else {
			$("#menu li").removeClass('active');
			$(p).addClass('active');
		}
	});
		
});	