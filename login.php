<?php 
include 'functions.php'; 
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
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
	$result = mysql_query("SELECT `id` FROM `User` WHERE `username`='".$_SESSION['username']."'",$link);

    $_SESSION['user_id'] = mysql_fetch_assoc($result)['id'];	
	
	header( 'Location: albums.php?user_id='.$_SESSION['user_id']);
}	
else{
    if (isset($_POST['username']) || isset($_POST['password'])){
        echo "username and password does not match";    
    }
?>
            <div class="content">
                <h2>Login</h2>
                <form id="register" method="post" action="login.php">
                    <label for="username">Username</label><input type="text" id="username" name="username" /> <br />
                    <br />
                    <label for="password">Password</label><input type="password" id="password" name="password" /> <br />
                    <br />
                    <button type="submit">Create account</button>
                </form>
            </div>
<?php
}
?>    
            
        <footer>
            <p>
                Site by me. &copy; 2013.
            </p>
        </footer>
    </body>
</html>

