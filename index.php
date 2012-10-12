
<!DOCTYPE html>
<html>

<head>
	<title>77Digital</title>
	<meta charset="utf-8" />
	<meta id="extViewportMeta" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	
	<link rel="stylesheet" href="css/style.css" TYPE="text/css" MEDIA=screen>	
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
</head>

<body>

<?php

require_once ('sdk/facebook.php'); // FACEBOOK PHP SDK v3.2.0-0-g98f2be1  
require_once ('music.php'); //base de datos app

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

//toma datos del logueo
if ($user) {
	$logoutUrl = $facebook->getLogoutUrl();
}

//no logueado -> pagina de logueo\
$loginpage = "http://www.laquesigue.com/login.php";

if (!$user) {
	echo "<script type='text/javascript'>top.location.href = '$loginpage';</script>";
    exit;
}else{ 
//loqueado

//mensaje de bienvenida
if( !isset($_SESSION['bienvenida']) ){
	echo '<script type="text/javascript">notifica("Bienvenido '.$_SESSION['nombre'].'")</script>';
	$_SESSION['bienvenida'] = true; //ha sido notificado
}
	
//status 0 -> libre, 1 -> voto, 2-> artista, 3 -> busqueda
$status = 0;
$like = '';
$artista = '';
$search = '';

//envio
if (isset($_POST['like']) || isset($_POST['search'])){
	
	if(isset($_POST['like'])){
		$like = $_POST['like'];
		$status = 1;
		//echo '<SCRIPT TYPE="text/javascript">alert (\'Voto ha '.$_POST['like'].'\');</SCRIPT>';
	}
	if(isset($_POST['search']) && $_POST['search'] != '' ){
		$search = $_POST['search'];
		$status = 2;
		//echo '<SCRIPT TYPE="text/javascript">alert (\'Buscando '.$_POST['search'].'\');</SCRIPT>';
	}
	//echo '<SCRIPT TYPE="text/javascript">alert (\'Status '.$status.'\');</SCRIPT>';
}


?>

<form id="index" action="index.php" method="post">

	<div data-role="page" class="page right" id="home">
		<div class="app">
			<!-- boton del menu -->
			<div class="menuicon" >
				<!-- id -> 1 activo, id -> 0 no activo -->
				<img src="images/appmenu.png" class="showMenu" onClick="move()" id="menu0">
			</div>
			<!-- logo -->
			<a href="http://77digital.com/" target="_blank">
				<img class="logo" src="images/logo.png" onClick="redireccionar('')">
			</a>
		</div>
		
		<div class="sidebar">
			
			<?php 
				//presenta los albums, resultados o canciones de un artista
				switch ($status) {
					case 0:
						album();
						$status = 0;
						echo '<div class="limpiar" onClick="sube(\'\')">Subir</div>';
						break;
					
					case 1:
						voto($like);
						album();
						$status = 0;
						break;

					case 2:
						$status = 0;
						buscar($search); 
						echo '<div class="limpiar" onClick="redireccionar(\'\')">Limpiar</div>';
						break;
				}
			?>

		<!-- fin sidebar -->
		</div>

	<!-- fin page and home -->
	</div>

	<div class="left" id="menu">

		<div class="topbar">
			<div class="search">
				<input type="text" value="" name="search" autocomplete="on" placeholder="buscar...">
				<img src="images/search.png" id="searchimg">
				<!-- <input type="bottom" src="images/search.png" id="searchimg" value="search" > -->
			</div>
		</div>

		<div class="content">

			<div class="vote">	
				<img src="images/like.png">
				<h1>vote</h1>
			</div>

			<div class="list">
				<div class="populares" onClick="redireccionar('')" >
					<img src="images/populares.png">
					<h1> mas populares </h1>
				</div>
				<div class="songs">

					<div id="songs">
						<img src="images/dropdown.png" id="dropdown">
						<h1>artistas..</h1>
					</div>

					<div id="songslist" action="index.php"> 
						<?php 
							artistas();
						?>
					</div>
				</div>

			</div>

		</div>

	</div>

<!-- fin formulario -->
</form>

<?

}

?>

</body>

</html>