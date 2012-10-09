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

if ($user) {
	$logoutUrl = $facebook->getLogoutUrl();
}

//no logueado -> pagina de logueo\
$loginpage = "http://www.laquesigue.com/login.php";
if (!$user) {
	echo "<script type='text/javascript'>top.location.href = '$loginpage';</script>";
        exit;
}else{ //loqueado
	
//status 0 -> libre, 1 -> voto, 2-> artista, 3 -> busqueda
$status = 0;
$like = '';
$artista = '';
$search = '';

//envio
if (isset($_GET['like']) || isset($_GET['artista']) || isset($_GET['search'])){
	
	if(isset($_GET['like'])){
		$like = $_GET['like'];
		$status = 1;
		//echo '<SCRIPT TYPE="text/javascript">alert (\'Voto ha '.$_GET['like'].'\');</SCRIPT>';
	}
	if(isset($_GET['artista'])){
		$artista = $_GET['artista'];
		$status = 2;
		//echo '<SCRIPT TYPE="text/javascript">alert (\'Artista escojido '.$_GET['artista'].'\');</SCRIPT>';
	}
	if(isset($_GET['search']) && $_GET['search'] != '' ){
		$search = $_GET['search'];
		$status = 3;
		//echo '<SCRIPT TYPE="text/javascript">alert (\'Buscando '.$_GET['search'].'\');</SCRIPT>';
	}
	//echo '<SCRIPT TYPE="text/javascript">alert (\'Status '.$status.'\');</SCRIPT>';
}


?>

<!DOCTYPE html>
<html>

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
	<script type="text/javascript" src="js/app.js"></script>
	

</head>

<body>

<form action="index.php" method="get">

	<div data-role="page" class="page right" id="home">
		<div class="app">
			<!-- boton del menu -->
			<div class="menuicon" >
				<!-- id -> 1 activo, id -> 0 no activo -->
				<img src="images/appmenu.png" class="showMenu" onClick="move()" id="menu0">
			</div>
			<!-- logo -->
			<img class="logo" src="images/logo.png" onClick="redireccionar('')">

		</div>
		
		<div class="sidebar">
			
			<?php 
				//presenta los albums, resultados o canciones de un artista
				switch ($status) {
					case 0:
						album();
						$status = 0;
						break;
					
					case 1:
						voto($like);
						album();
						$status = 0;
						break;

					case 2:
						soloArtista($artista);
						$status = 0;
						break;

					case 3:
						//$_GET['search'] = '';
						$status = 0;
						buscar($search); 
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
				<h3>vote</h3>
			</div>

			<div class="list">
				<div class="populares">
					<img src="images/populares.png">
					<h3> mas populares </h3>
				</div>
				<div class="songs">

					<div id="songs">
						<img src="images/dropdown.png" id="dropdown">
						<h3>artistas..</h3>
					</div>

					<div id="songslist"> 
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


</body>

</html>
<?

}

?>

<!--
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
-->