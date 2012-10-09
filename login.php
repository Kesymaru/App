<?php

require_once ('sdk/facebook.php'); // FACEBOOK PHP SDK v3.2.0-0-g98f2be1  
require_once ('music.php');

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
	$loginUrl = $facebook->getLoginUrl(     
	//prmisos solicitados para la app       
	array(
          'scope' => 'email,publish_stream'
         ));
} 

//no logueado -> pagina de logueo
if (!$user) {
	echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
        exit;
}else{

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
      <?php foreach($user_profile as $key=>$value){
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

?>