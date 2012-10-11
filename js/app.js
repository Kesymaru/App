$(window).load(function() {

});

$(document).ready(function(){

	responsitive();

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
		responsitive();
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
function facebook(cancion, artista) {
    /*fb.publish({
      message : "Voté en www.laquesigue.com para que Kurt Dyer toque "+cancion+".",
      picture : "http://www.laquesigue.com/images/album.png",
      link : "http://www.laquesigue.com",
      name : "La Que Sigue",
      description : "Vota en www.laquesigue.com para que Kurt Dyer toque tu cancion favorita. Patrocinado por 77Digital"
    },function(published){ 
      
      if (published)
        //alert("publicado!");
   		notifica('Se ha realizado tu voto.<br/>'+cancion+'<br/>'+artista);
      else
        //alert("No publicado :(, seguramente porque no estas identificado o no diste permisos");
       	notifica("Ocurrio un error :(<br/>No se ha publicado.<br/>Posiblemente por no diste permisos.");
    }); */ 
	notificaE('Se ha realizado tu voto.<br/>'+cancion+'<br/>'+artista);		
}

//usa noty (jquery plugin) para notificar 
function notifica(text) {
  	var n = noty({
  		text: text,
  		type: 'alert',
    	dismissQueue: true,
  		layout: 'topCenter',
  		closeWith: ['click'], // ['click', 'button', 'hover']
  	});
  	console.log('html: '+n.options.id);
  	
  	//tiempo para desaparecerlo solo 
  	setTimeout(function (){
		n.close();
	},3000);
}

//notifica errores o warnings
function notificaE(text) {
  	var n = noty({
  		text: text,
  		type: 'error',
    	dismissQueue: true,
  		layout: 'topCenter',
  		closeWith: ['click'], // ['click', 'button', 'hover']
  	});
  	console.log('html: '+n.options.id);
  	
  	//tiempo para desaparecerlo solo 
  	setTimeout(function (){
		n.close();
	},5000);
}

//envia artista
function artista(artista){
	
	$.post( "music.php", {'artista': artista, 'func': '2' }, function(data){
		$(".sidebar").html(data);
	});

	move();
	responsitive();
	setTimeout(function (){
		$('html, body').animate({scrollTop:0}, 'slow');
	},500);
}

//para imagenes adaptables
function responsitive(){
	var ancho = $(".cover").width() * 0.60;
	$('.imageCover').css('width',ancho);
}

//para realizar el voto 
function votar(id, artista, cancion){

	//vota
	if( $.post( "music.php", {'id': id, 'func': '3' } ) ){
		facebook(cancion, artista); //publica en facebook
	}else{
		notificaE('Ocurrio un error :(<br/>Intentalo de nuevo.');
	}

	//actualiza el album del artista
	$.post( "music.php", {'id': id, 'func': '4'}, function(data){
		if( data.length > 0 ){
			$("#album"+id+" #votos"+id).html(data);
			$("#album"+id+" #votos"+id).fadeIn();
		}else{
			notificaE('Error al actualizar artista');
		}
	});	

}