<?php
if (session_id() == '') {
    // session isn't started
    session_start();
}

function spoj_s_db() { // spojenie s databazou
    include('config.php');

    $link = mysql_connect('localhost', $php_db_name, $php_db_pwd) or die('Could not connect to mysql server.');
    mysql_select_db($db_name, $link) or die('Could not select database.');
    mysql_query("SET CHARACTER SET 'utf8'", $link);
    return $link;
}


function check_if_logged_in(){
    if(!isset($_SESSION['username'])){
        header( 'Location: login.php');  
    }
}


function check_login_album($album_id){
    if(!isset($_SESSION['username'])){
        header( 'Location: login.php');  
    }
    $link = spoj_s_db();
    $result = mysql_query("SELECT * FROM `Album` JOIN 	`User` ON (User.id = Album.owner_id) WHERE (owner_id = ".$_SESSION['user_id']." AND Album.id = ".$album_id.")",$link);    
    
    if(mysql_num_rows($result) == 0){
        return false;   
    }
    return true;
}


function image_page_header(){
    ?>
<html>

<head>
<title>fotoAlbum</title>
</head>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<script type="text/javascript" src="scripts/fancybox/source/jquery.fancybox.js"></script>
<script src="scripts/jquery.uploadfile.js"></script>

<link rel="stylesheet" type="text/css" href="scripts/fancybox/source/jquery.fancybox.css" />
<link rel="stylesheet" type="text/css" href="scripts/style.css"  />
<link href="scripts/uploadfile.css" rel="stylesheet">


<script type="text/javascript" src="scripts/multiupload.js"></script>


<script>
$(document).ready(function(){
	$("a[rel=lightbox]").fancybox({
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic'		
	});
	$("ul li").append("<span></span>");
});
</script>
    
    <?php
}


function delete_image($id){
    include 'config.php';
    $path = $images_path.$id.'.jpg';
    $link = spoj_s_db();
    $result = mysql_query("DELETE FROM `Photo` WHERE `id`=".$id, $link);
    if (file_exists($path)) {
        unlink($path);
    }
}


function print_register_form(){
    if (!isset($_POST['username'])) $_POST['username']='';?>
      <div class="content">
                <h2>Register</h2>
 
                <form id="register" method="post" action="register.php">
                    Required fields are in <strong>bold</strong>.<br />
                    <br />
 
                    <label for="username"><strong>Username</strong></label><input type="text" id="username" name="username" value="<?php echo $_POST['username']; ?>" /> <strong class="error" data-field="username"></strong><br />
                    <br />
                    <label for="password"><strong>Password</strong></label><input type="password" id="password" name="password" /> <strong class="error" data-field="password"></strong><br />
                    <br />
                    <label for="password2"><strong>Password again</strong></label><input type="password" id="password2" name="password2" /> <strong class="error" data-field="password2"></strong><br />
                    <br />
                    <br />
                    <label><strong>E-mail</strong></label><input type="text" id="email" name="email" value="<?php echo $_POST['email']; ?>" /> <strong class="error" data-field="email"></strong><br />
                    <br />
                    <button type="submit">Create account</button>
                </form>
      </div>
    <?php
}


function check_username($username){
    $link = spoj_s_db();
    $result = mysql_query("SELECT COUNT(`id`) as count FROM `User` WHERE `username`='".$username."'",$link);
    $count = mysql_fetch_assoc($result)['count'];
    if (count > 0) return false;   
    
    if(!isset($username)) return false;
    if ((strlen($username) < 5) || (strlen($username) > 20)) return false;
    return true;
}


function check_password($password1, $password2){
    if (strlen($password1) < 5) return false;
    if(strcmp($password1,$password2) != 0) return false; 
    return true;
}


function check_email($email){
    $link = spoj_s_db();
    $result = mysql_query("SELECT COUNT(`id`) as count FROM `User` WHERE `email`='".$email."'",$link);
    $count = mysql_fetch_assoc($result)['count'];
    if (count > 0) return false;    
    
    if(!isset($email)) return false;
    return true;
}


function isset_everything(){
    if ((isset($_POST['username'])) && (isset($_POST['email'])) && (isset($_POST['password'])) && (isset($_POST['password2'])) ) return true;
    return false;
}


function login($username, $password) {
    $link = spoj_s_db();
    $result = mysql_query("SELECT * FROM  `User` WHERE username =  '" . $username . "'AND password =  '" . md5($password) . "'", $link);
    if (mysql_num_rows($result) > 0){
        return true;
    }
    else{
        return false;
    }
}


?>