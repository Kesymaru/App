$(window).load(function() {

});

$(document).ready(function(){
	var menuStatus;
	var ancho = $(".cover").width() * 0.60;
	$('.imageCover').css('width',ancho);

	/*$('.showMenu').click(function(){
		if(menuStatus != true){				
			$(".ui-page-active").animate({
				marginLeft: "80%",
		  	}, 300, function(){menuStatus = true});
		  	$(".content").css('height','100%');
		  	$(".content").fadeIn();
		  //return false;
		  } else {
			$(".ui-page-active").animate({
				marginLeft: "0px",
		  	}, 300, function(){menuStatus = false});
			$(".content").css('height','0');
			$(".content").fadeOut(10);
			//return false;
		  }
	});*/
	

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
			marginLeft: "80%",
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

	// menu para artistas
	$("#songs").click(function(){

		if ($("#songslist").css('display') == 'none'){
			$("#songslist").css({
				'display'                   : 'table',
			});
			$("#songs").css({
				'border-bottom-right-radius': '0px',
				'border-bottom-left-radius' : '0px',
			});
	  		$("#songslist").animate({
		    	opacity: 1
	  		}, 1500, 'linear');
		}else{
			$("#songslist").css({
				'display': 'none',
				'opacity': '0.2',
			});
			$("#songs").css('border-radius','20px');
		}
	});

	//responsitive para el cover
	$(window).resize(function() {
		var ancho = $(".cover").width() * 0.60;
		//var ancho = $(".menuicon").width() * 0.60;

    	$(window).width() < $('.imageCover').css('width',ancho);
    	//location.reload();
	});

	//actualiza automaticamente los resultados
	/*jQuery(function($){
  		setInterval(function(){ 
	  		//alert('actualiza');
	  		$.get("tv.php",{'func':'1'},function(data){
	  			// data now contains "Hello from 2"
	  			//alert(data);
	  			$(".sidebar").html(data);
	  			$(".sidebar").fadeIn();
	  			var ancho = $(".cover").width() * 0.60;
	  			$(window).width() < $('.imageCover').css('width',ancho);
			});
  		},5000); // 5000ms == 5 seconds
	});*/

});	

//para cambiar la imagen del boton
function menuVotar(id){

	if ($("#"+id).css('display') == 'block'){
		$("#"+id).css('display','none');
		$("#"+id).fadeOut();

		$("#boton"+id).attr('src','images/masDesactivo.png');
		$("#boton"+id).fadeIn();

		$("#album"+id).css({
			'background-color': '#404040',
			'border-bottom': '1px solid rgba(199, 199, 199, 0.5)',
		});
		
	}else{
		$("#"+id).css({
			'display'      : 'block',
			'border-bottom': '1px solid rgba(199, 199, 199, 0.5)',
		});
		$("#"+id).fadeIn();

		$("#boton"+id).attr('src','images/masActivo.png');
		$("#boton"+id).fadeIn();

		$("#album"+id).css({
			'background-color': 'rgba(0, 150, 226, 0.5)',
			'border-bottom': '1px solid rgba(199, 199, 199, 0.5)',
		});
	}
}

//muestra el menu
function move(){
	var menuStatus;

		//alert('click');

		//alert( $('.showMenu').attr('id') );

		if( $('.showMenu').attr('id') == 'menu0' ){				
			$(".ui-page-active").animate({
				marginLeft: "75%",
		  	}, 300);

		  	$(".content").css('height','100%');
		  	$(".content").fadeIn(10);
		  	menuStatus = true; 
		  	$('.showMenu').attr('id', 'menu1');
		  	//alert(menuStatus);
		  } 
		  else {
			$(".ui-page-active").animate({
				marginLeft: "0px",
		  	}, 300);
			$(".content").css('height','0');
			$(".content").fadeOut(10);
			menuStatus = false; 
			$('.showMenu').attr('id', 'menu0');
			//alert(menuStatus);
		  }
}

function redireccionar(link){
	link = "index.php"+link;
	window.location = link;
}

//para publicar en facebook
function facebook(u,i,d){
	var t = 'La Que Sigue';
	window.open('http://fb-share-control.com?u='+u+'&amp;t='+t+'&amp;i='+i+'&amp;d='+d,'sharer','toolbar=0,status=0,width=800,height=400');
        return false;
}

function notifica(text) {
  	var n = noty({
  		text: text,
  		type: 'alert',
    	dismissQueue: true,
  		layout: 'topCenter',
  	});
  	console.log('html: '+n.options.id);
}

//envia artista
function artista(artista){
	
	$.post( "music.php", {'artista': artista, 'func': '2' }, function(data){
		$(".sidebar").html(data);
	});

	move();
}