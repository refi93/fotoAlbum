<?php 
include 'functions.php'; 
?>


<!DOCTYPE html>
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="raf" >
    <link rel="shortcut icon" href="http://getbootstrap.com/assets/ico/favicon.ico">

    <title>fotoAlbum</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  <style type="text/css">
  </style></head>

  <body>
        <header>
            <h1>fotoAlbum</h1>
        </header>


<?php 

if (isset($_SESSION['username'])){
    header( 'Location: albums.php?user_id='.$_SESSION['user_id']);
}

/* ak boli poslane data z formulara a potrebujeme ich overit */
if (login($_POST['username'], $_POST['password'])){
	$_SESSION['username'] = $_POST['username'];
	$link = spoj_s_db();
	$result = mysql_query("SELECT `id` FROM `User` WHERE `username`='".mysql_escape_string($_SESSION['username'])."'",$link);

    $_SESSION['user_id'] = mysql_fetch_assoc($result)['id'];	
	
	header( 'Location: albums.php?user_id='.$_SESSION['user_id']);
}	
else{
    if (isset($_POST['username']) || isset($_POST['password'])){
        echo "username and password does not match";    
    }
?>
            <div class="container">
                <form class="form-signin" id="register" method="post" action="login.php" required="" autofocus="">
                	  <h2 class="form-signin-heading">Login</h2>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required=""/> 
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password" required=""> 
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
                    Don't have an account? <a href="./register.php">register</a>
                </form>
            </div>
<?php
}
bootstrap_scripts();

?>    
        
    </body>
</html>
