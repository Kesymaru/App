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
if (!$user) {
	echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
        exit;
}else{
	//logueado
	session($user_profile); 
	usuario($user_profile); 
}

/*
	Para limpiar los parametros enviados desde facebook via GET, se redirecciona a index.php
	Facebook abre sesion automaticamente
*/

$home = "http://www.laquesigue.com/";
echo "<script type='text/javascript'>top.location.href = '$home';</script>";
exit;

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

?>