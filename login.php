<?php
//base de datos
$host      =    "localhost";
$user      =    "laqueusr";
$pass      =    "Trans123@";
$tablename =    "laquesiguedb";

$conecta = mysql_connect($host,$user,$pass);
mysql_select_db($tablename, $conecta);

require_once ('sdk/facebook.php'); // FACEBOOK PHP SDK v3.2.0-0-g98f2be1  


//parametro de la app paa facebook
$facebook = new Facebook(array(
  'appId'  => '369466053131869',
  'secret' => '75715a48ec6547da5477fb9ef6ada8ce', 
));
	
// Obtener el ID del Usuario
$user = $facebook->getUser();

if ($user) {
	try {
	    // errores se guardan en un archivo de texto (error_log)
		$user_profile = $facebook->api('/me');
	} catch (FacebookApiException $e) {
		error_log($e);
		$user = null;
	} 
}

if ($user) {
	$logoutUrl = $facebook->getLogoutUrl();
} else {

	//prmisos solicitados para la app
	$loginUrl = $facebook->getLoginUrl(            
	array(
          'scope' => 'email,publish_stream'
         ));
} 

//no logueado -> pagina de logueo
if ( !$user ) {
	loginBox($loginUrl);
}else{
	//logueado
	session($user_profile); 
	usuario($user_profile); 
}

//revisa si exise el usuario
function usuario($user_profile){

	$sql = 'SELECT * FROM usuarios WHERE id = '.$user_profile['id'];
	$sql = mysql_query($sql);

	if( !mysql_fetch_array($sql) ){ //si no existe el usuario
		grabar($user_profile);
	}
}

//graba datos de usuarios en base de datos
function grabar($user_profile){

	$sql = "INSERT INTO usuarios (nombre, apellido, email, id) VALUES ('".$user_profile['first_name']."', '".$user_profile['last_name']."', '".$user_profile['email']."', '".$user_profile['id']."')";
	
	//graba en la base de datos
	if( !mysql_query($sql) ){
		//error
		die('Error: ' . mysql_error());
	}
}

//datos de la session
function session($user_profile){
	//facebook abre session
	session_start();
	$_SESSION['nombre'] = $user_profile['name'];
	$_SESSION['id'] = $user_profile['id'];
	$_SESSION['email'] = $user_profile['email'];
}

/*
	Para limpiar los parametros enviados desde facebook via GET, se redirecciona a index.php
	Facebook abre sesion automaticamente
*/
function home(){
	$home = "http://www.laquesigue.com/";
	echo "<script type='text/javascript'>top.location.href = '$home';</script>";
	exit;
}

//muestra pagina de mensaje del logueo para facebook
function loginBox($loginUrl){

echo '
<head>
	<title>77Digital</title>

	<meta charset="utf-8" />
	<meta id="extViewportMeta" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	
	<link rel="stylesheet" href="style.css" TYPE="text/css" MEDIA=screen>	
	<link rel="stylesheet" href="css/jquery.mobile-1.0rc2.min.css" />
	<link rel="stylesheet" href="css/main.css" />

	<script type="text/javascript" src="js/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" src="js/jquery.mobile-1.0rc2.min.js"></script>
	
	<!-- notificaciones -->
	<script type="text/javascript" src="js/noty/jquery.noty.js"></script>
	<script type="text/javascript" src="js/noty/layouts/topCenter.js"></script>
	<script type="text/javascript" src="js/noty/themes/default.js"></script>

	<script type="text/javascript" src="js/app.js"></script>
	<!-- para publicar en facebook -->
	<script type="text/javascript" src="js/simpleFacebookGraph.js"></script>
	<script type="text/javascript">
	function loguearse(){
		top.location.href = \''.$loginUrl.'\';
	}
	</script>
</head>

<body>
	<div class="mensajeLogin">
		<h1>Ingresa con facebook</h1>
		<p>Para ingresar se requiere cuenta de facebook<p>
		<button onClick="loguearse()">Ingresar con facebook</button>
	</div>
</body>

</html>';

}

?>