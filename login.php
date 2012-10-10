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
	usuario();
}

?>

<! html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>php-sdk 3.0.0</title>
        <style typ="text/css">
            html, body { width: 520px;}
        </style>    
  </head>
  <body>
    <h1>php-sdk</h1>
    <h3>PHP Session</h3>
      <?php foreach($_SESSION as $key=>$value){
          echo '<strong>' . $key . '</strong> => ' . $value . '<br />';
      }
      ?>
      <h3>Tu</h3>
      <img src="https://graph.facebook.com/<?php echo $user; ?>/picture">
      <h3>Tus datos (/me)</h3>
      <?php 
      	$keys = array_keys($user_profile);
      	
      	$values = array_values($user_profile);

      	echo '<hr>Keys';
      	print_r($keys);
      	echo '<hr>Values';
      	print_r($values);
      	echo "<hr><center>mail 2</center>".array_search('@', $user_profile)."</hr>";
      	echo '<hr>';

      	foreach($user_profile as $key => $value){
        	echo '<strong>' . $key . '</strong> => ' . $value . '<br />';
      }
      ?>
  </body>
</html>

<?php

//re direccion al home, esto limpia los parametros enviados por facebook via GET
$home = "http://www.laquesigue.com/";
//echo "<script type='text/javascript'>top.location.href = '$home';</script>";
//exit;


//revisa si exise el usuario
function usuario(){
	//$_SESSION['fb_369466053131869_user_id'] -> 369466053131869 -> numero de la app
	$sql = 'SELECT * FROM usuarios WHERE id = '.$_SESSION['fb_369466053131869_user_id'];
	$sql = mysql_query($sql);

	//echo "<script type='text/javascript'> alert('Resultado".$user_profile as '' => $value." idFace ".$_SESSION['fb_369466053131869_user_id']."'); </script>";

	if( mysql_fetch_array($sql) ){
		//echo "<script type='text/javascript'> alert('Existe'); </script>";
	}else{
		//echo "<script type='text/javascript'> alert('No existe Grabando...'); </script>";	
		grabar();
	}
}

//graba datos de usuarios en base de datos
function grabar(){
	//cargaDatos();
	$sql = "INSERT INTO usuarios (nombre, apellido, email, id) VALUES (".$values[2].", ".$values[3].", $".$values[2].", ".$id.")";
	echo array_keys($user, "id");
	echo "<script type='text/javascript'> alert('Nombre:".array_keys($user_profile, "id")."'); </script>";
	
	//$resultado = mysql_query($sql);

	if($resultado){
		//echo "<script type='text/javascript'> alert('Grabado'); </script>";
	}else{
		//echo "<script type='text/javascript'> alert('No grabado error bd'); </script>";
	}
	echo "<hr>".$sql;
}

function cargaDatos(){
	$datos = array();
	$i = 0;
	/*foreach($user_profile as $key => $value){
    	$datos[$i] = $value;
    	echo $datos[$i];
    	$i++;s
    }*/
}

?>