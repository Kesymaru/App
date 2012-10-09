<?php
//Procesa todo lo relacionado a facebook

// FACEBOOK PHP SDK v3.2.0-0-g98f2be1  
require 'sdk/facebook.php';

//parametro de la app
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
          'scope' => 'email,publish_stream,user_birthday,user_location,user_work_history,user_about_me,user_hometown'
         ));
} 

//no logueado -> pagina de logueo
if (!$user) {
	echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
        exit;
}

?>