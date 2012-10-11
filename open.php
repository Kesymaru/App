<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xmlns:fb="https://www.facebook.com/2008/fbml"> 

  <head>
  <meta property="fb:app_id" content="369466053131869" /> 
  <meta property="og:type"   content="laquesigue:cancion" /> 
  <meta property="og:url"    content="http://www.laquesigue.com" /> 
  <meta property="og:title"  content="Cancion de Artista" /> 
  <meta property="og:image"  content="http://www.laquesigue.com/images/album.png" />

 <?php
 $cache_expire = 60*60*24*365;
 header("Pragma: public");
 header("Cache-Control: max-age=".$cache_expire);
 header('Expires: ' . gmdate('D, d M Y H:i:s', time()+$cache_expire) . ' GMT');
 ?>
 <script src="//connect.facebook.net/en_US/all.js"></script>
<title>Votar</title>


  <script type="text/javascript">
  function votar()
  {
      FB.api(
        '/me/laquesigue:cancion',
        'post',
        { votar: 'http://www.laquesigue.com' },
        function(response) {
           if (!response || response.error) {
              alert('Error occured');
           } else {
              alert('Cook was successful! Action ID: ' + response.id);
           }
        });
  }
  </script>

</head>
<body>
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '369466053131869', // App ID
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        xfbml      : true  // parse XFBML
      });
    };

    // Load the SDK Asynchronously
    (function(d){
      var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
      js = d.createElement('script'); js.id = id; js.async = true;
      js.src = "//connect.facebook.net/en_US/all.js";
      d.getElementsByTagName('head')[0].appendChild(js);
    }(document));
  </script>

  <h3>Votar</h3>
  <p>
    <img title="Voto" 
         src="http://www.laquesigue.com/images/album.png" 
    />
  </p>

  <br>
  <form>
    <input type="button" value="Votar" onclick="votar()" />
  </form>

  
</body>
</html>