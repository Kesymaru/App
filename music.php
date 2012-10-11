<?php

/*
	Conecta la base de datos, procesa los datos y los muestra

*/

$host      =    "localhost";
$user      =    "laqueusr";
$pass      =    "Trans123@";
$tablename =    "laquesiguedb";

$conecta = mysql_connect($host,$user,$pass);

mysql_select_db($tablename, $conecta);

if(isset($_POST['func'])){
	switch($_POST['func']) {
	  case '1':
	    album();
	    break;
	  //muestra artista
	  case '2':
	    if( isset($_POST['artista']) ){
	    	soloArtista($_POST['artista']);
	    }
	    break;
	  //votar
	  case '3':
	    if( isset($_POST['id']) ){
	    	//realiza el voto
	    	voto($_POST['id']);
	    }
	    break;
	  //actualizar votos del album
	  case '4':
	    if( isset($_POST['id']) ){
	    	//realiza el voto
	    	votosTotal($_POST['id']);
	    }
	  default:
	    // Do nothing?
	}
}


//para el home. muestra los albums ordenados por la cantidad de votos desendientemente
function album(){
	$contador = 0;
	$sql = 'SELECT * FROM votos ORDER BY votos DESC';
	$resultado = mysql_query($sql);
	
	while($row = mysql_fetch_array($resultado)){
		$contador++;
		artista($row['cancion']);
		contador($contador, $row['cancion']);
  	}
}

//muestra un album con el id de la cancion
function artista($idArtista){

  	$sql = 'SELECT * FROM musica WHERE id ='.$idArtista;
	$resultado = mysql_query($sql);

	while($row = mysql_fetch_array($resultado)){
		showArtista($row);
  	}
}

//realiza el voto. actualiza la tabla de la base de datos de voto
function voto($idCancion){

	$votos = votos($idCancion);
	$votos = $votos + 1;
	//actualiza votos
	$sql = 'UPDATE votos SET votos ='.$votos.' WHERE cancion ='.$idCancion;

	mysql_query($sql);
	//echo '<SCRIPT TYPE="text/javascript">alert(\'El voto se ha echo.\');</SCRIPT>';
	//$link = '';
	echo '<SCRIPT TYPE="text/javascript">window.location = \"index.php\";</SCRIPT>';
}

//muestra todos la lista artistas agrupando los artistas para hacer una lista
function artistas(){
	$sql = 'SELECT * FROM musica GROUP BY artista';
	$resultado = mysql_query($sql);

	while($row = mysql_fetch_array($resultado)){
		
		echo '<h2 onClick="artista( \''.$row['artista'].'\' )">'.$row['artista'].'</h2>';
	}
}

//devuelve votos de la cancion
function votos($idCancion){
	$sql = 'SELECT * FROM votos WHERE cancion = '.$idCancion;
	$resultado = mysql_query($sql);
	$resultado = mysql_fetch_array($resultado);
	
	if(isset($resultado['votos'])){
		return $resultado['votos'];
	}else{
		return '0';
	}
}

//para AJAX, devuelve los votos ocupa id de la cancion
function votosTotal($idCancion){
	$sql = 'SELECT * FROM votos WHERE cancion = '.$idCancion;
	$resultado = mysql_query($sql);
	$resultado = mysql_fetch_array($resultado);
	
	if(isset($resultado['votos'])){
		echo $resultado['votos'] . ' votos';
	}else{
		return '0';
	}
}

//muestra las canciones de un solo artista
function soloArtista($nombre){

	$error = true;
	$sql = "SELECT * FROM musica WHERE artista LIKE '%$nombre%' LIMIT 0, 30";
	$resultado = mysql_query($sql);

	while($row = mysql_fetch_array($resultado)){
		showArtista($row);
		$error = false;
	}
	//mensaje de error
	if($error){
		echo '<div class="error">
			
				<h1>No se encontraron resultados para <br>'.$nombre.'</h1>

		</div>';
	}
}

function buscar($busca){
	$encontro = false;

	for ($i = 0; $i <= 2; $i++){
		if($i == 0){
			$sql = mysql_query("SELECT * FROM musica WHERE artista LIKE '%".$busca."%' LIMIT 0, 30 ");
		}
		if($i == 1){
			$sql = mysql_query("SELECT * FROM musica WHERE cancion LIKE '%".$busca."%' LIMIT 0, 30 ");
		}
		if($i == 2){
			$sql = mysql_query("SELECT * FROM musica WHERE album LIKE '%".$busca."%' LIMIT 0, 30 ");
		}

		while ($row = mysql_fetch_array($sql)){
			
			if($row['artista'] == $busca || $row['cancion'] == $busca || $row['album'] == $busca){
				$encontro = true;
				showArtista($row);
			}

		}
	}
	if(!$encontro){
		echo '<div class="error">
			
				<h1>No se hay resultados para <br>'.$busca.'</h1>
		</div>';
	}

	
	//echo '<SCRIPT TYPE="text/javascript">alert (\'Resultado '.$resultado.' \');</SCRIPT>';
}


//muestra formatedo el artista, requiere el arreglo de la consulta de la tabla de la base de datos
function showArtista($row){
		echo '<div class="album" id="album'.$row['id'].'" >
				<div class="cover">';
		//cover
		if ($row['cover'] == 'default'){
			echo '<img class="imageCover" src="images/album.png" title="'.$row['artista'].'">';
		}else{
			echo '<img class="imageCover" src="images/cover/'.$row['cover'].'" title="'.$row['artista'].'">';
		}

		echo '</div>
				<div class="info">
					<div class="contador" id="contador'.$row['id'].'">
						<span></span>
					</div>
					<div class="infosong">
						<img src="images/disco.png">';
		//artista
		echo '<h2>'.$row['cancion'].'</h2>';
		//album
		echo '<h3>'.$row['artista'].'</h3>';

		echo '</div>
					<div class="infovotos">
<img src="images/like.png" onClick="votar( \''.$row['id'].'\',\''.str_replace("'","",$row['artista']).'\',\''.str_replace("'","",$row['cancion']).'\')" /> ';
		//id para el jquery
		//echo '<img src="images/masDesactivo.png"  class="mas" id="boton'.$row['id'].'">';
		
		//votos
		echo '<p id="votos'.$row['id'].'">'.votos($row['id']).' votos</p>
				</div>
				</div>
			</div>';
}

function contador($numero, $id){
	echo '<SCRIPT TYPE="text/javascript">
			$("#contador'.$id.'").html("<h1>'.$numero.'</h1>");
			$("#contador'.$id.'").css("display","inline-block");
		</SCRIPT>';
}

?>