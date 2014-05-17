<?php
if (session_id() == '') {
    // session isn't started
    session_start();
}
/* spojenie s databazou */
function spoj_s_db() { // spojenie s databazou
    include('config.php');

    $link = mysql_connect('localhost', $php_db_name, $php_db_pwd) or die('Could not connect to mysql server.'); 
    /* $link = mysql_connect('46.36.35.188', $php_db_name, $php_db_pwd) or die('Could not connect to mysql server.'); */
    mysql_select_db($db_name, $link) or die('Could not select database.');
    mysql_query("SET CHARACTER SET 'utf8'", $link);
    return $link;
}

/* overenie, ci je prihlaseny uzivatel */
function check_if_logged_in(){
    if(!isset($_SESSION['username'])){
        header( 'Location: login.php');  
    }
}

/* vrati id admina */
function get_admin_id(){
    $link = spoj_s_db();
    $result = mysql_query("SELECT * FROM `User` WHERE `username`='admin'",$link);
    return mysql_fetch_assoc($result)['id'];
}

/* docasne "oklame" server, aby si myslel, ze je prihlaseny urcity uzivatel v pripade, ze je prihlaseny admin */
function grant_privileges_to_admin($user_id){
    if ($_SESSION['username'] == 'admin'){
        $_SESSION['user_id'] = $user_id;       
    }
}

/* vrati id prihlaseneho pouzivatela do povodneho stavu */
function restore_admin_privileges(){
    if($_SESSION['username'] == 'admin'){
        $_SESSION['user_id'] = get_admin_id();    
    }
}


/* vrati true, ak prihlaseny pouzivatel ma pravo pristupovat k albumu s id=album_id */
function check_login_album($album_id){
    if(!isset($_SESSION['username'])){
        header( 'Location: login.php');  
    }
    $link = spoj_s_db();
    
    /* overime, ci dany album patri prihlasenemu pouzivatelovi */
    $result = mysql_query("SELECT * FROM `Album` JOIN 	`User` ON (User.id = Album.owner_id) WHERE (owner_id = ".$_SESSION['user_id']." AND Album.id = ".mysql_escape_string($album_id).")",$link);    
    
    if(mysql_num_rows($result) == 1){
        return true;   
    }
    
    /* overime, ci dane fotky su zdielane s prihlasenym pouzivatelom, pripadne ci su verejne */  
    
    $result = mysql_query("(SELECT DISTINCT id,name FROM  `Album` JOIN `Share` ON (Share.album_id = Album.id) 
                                                                    JOIN `GroupMembers` ON (GroupMembers.group_id = Share.group_id) 
                                                                    WHERE member_id=".$_SESSION['user_id']." AND id=".$album_id.")".
             " UNION
             (SELECT id,name FROM `Album` WHERE id=".$album_id." AND public=1)", $link);    
    if(mysql_num_rows($result) > 0){
        return true;   
    }   
    
    return false;
}


/* vrati true, ak prihlaseny pouzivatel ma pravo pristupovat k obrazku s id=image_id */
function check_login_image($image_id){
    if(!isset($_SESSION['username'])){
        header( 'Location: login.php');  
    }
    $link = spoj_s_db();
    
    /* overime, ci dany album patri prihlasenemu pouzivatelovi */
    
    $result = mysql_query("SELECT * FROM `Album` JOIN 	`User` ON (User.id = Album.owner_id) JOIN `Photo` ON (`Album`.id = `Photo`.album_id) WHERE (owner_id = ".$_SESSION['user_id']." AND `Photo`.id = ".mysql_escape_string($image_id).")",$link);    
    
    if(mysql_num_rows($result) == 1){
        return true;   
    }
    
    /* overime, ci dana fotka je zdielana s prihlasenym pouzivatelom, pripadne ci je verejna */    
    
    $result = mysql_query("(SELECT DISTINCT Photo.id FROM  `Album` JOIN `Share` ON (Share.album_id = Album.id) JOIN `Photo` ON (`Album`.id = `Photo`.album_id) 
                                                                    JOIN `GroupMembers` ON (GroupMembers.group_id = Share.group_id) 
                                                                    WHERE member_id=".$_SESSION['user_id']." AND `Photo`.id=".$image_id.")".
             " UNION
             (SELECT Photo.id FROM `Photo` JOIN `Album` ON (Photo.album_id = Album.id) WHERE Photo.id=".$image_id." AND public=1)", $link);    
    if(mysql_num_rows($result) > 0){
        return true;   
    }   
    
    return false;
}


function image_page_header($title){
    ?>
<!DOCTYPE html>
<html>
<head>
<meta name="generator" content="Bluefish 2.2.4" >
<title><?php echo $title; ?></title>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" type="text/javascript">
</script>
<script type="text/javascript" src="scripts/fancybox/source/jquery.fancybox.js">
</script>
<script src="scripts/jquery.uploadfile.js" type="text/javascript">
</script>
<link rel="stylesheet" type="text/css" href="scripts/fancybox/source/jquery.fancybox.css">
<link rel="stylesheet" type="text/css" href="scripts/style.css">
<link href="scripts/uploadfile.css" rel="stylesheet" type="text/css">
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="css/image_page.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="scripts/multiupload.js">
</script>
<script type="text/javascript">
$(document).ready(function(){
        $("a[rel=lightbox]").fancybox({
                'transitionIn'          : 'elastic',
                'transitionOut'         : 'elastic'             
        });
        $("ul li").append("<span><\/span>");
});
</script>
    
    <?php
}

function echo_form_submit_script($id, $submit_action){
    ?>
<script>
    $(function () {
        $('<?php echo $id; ?>').on('submit', function (e) {
            me = $(this);
            $.ajax({
                type: 'post',
                url: '<?php echo $submit_action; ?>',
                data: me.serialize(),
                success: function () {
                    location.reload();
                }
            });
            e.preventDefault();
        });
    });
</script>
<?php
}

/* scritp na submit formulara pomocou kliku na link */
function echo_link_click_script($id, $submit_action){
    ?>
<script>
    $(function () {
        $('<?php echo $id; ?>').on('click', function (e) {
            me = $(this);
            $.ajax({
                type: 'get',
                url: '<?php echo $submit_action; ?>',
                data: {comment_id: me.attr("data-comment_id")},
                success: function () {
                    location.reload();
                }
            });
            e.preventDefault();
        });
    });
</script>
<?php
}


/* zmazanie fotky fyzicky aj z databazy */
function delete_image($id){
    include 'config.php';
    $path = $IMAGES_LOCATION.$id.'.jpg';
    $link = spoj_s_db();
    $result = mysql_query("DELETE FROM `Photo` WHERE `id`=".$id, $link);
    if (file_exists($path)) {
        unlink($path);
    }
}

/* vrati id vlastnika danej fotografie */
function get_image_owner($image_id){
    $link = spoj_s_db();
    $result = mysql_query("SELECT `owner_id` FROM `Album` JOIN `Photo` ON `Photo`.album_id = `Album`.id WHERE `Photo`.id = ".mysql_escape_string($image_id),$link);
    return mysql_fetch_assoc($result)['owner_id'];
}


function print_register_form(){
    if (!isset($_POST['username'])) $_POST['username']='';?>
      <div class="content">
                <h2>Register</h2>
 
                <form id="register" method="post" action="register.php">
                    Required fields are in <strong>bold</strong>.<br>
                    <br>
 
                    <label for="username"><strong>Username:&nbsp; </strong></label><input type="text" id="username" name="username" value="<?php echo $_POST['username']; ?>" /> <strong class="error" data-field="username"></strong><br>
                    <br>
                    <label for="password"><strong>Password:&nbsp; </strong></label><input type="password" id="password" name="password" /> <strong class="error" data-field="password"></strong><br>
                    <br>
                    <label for="password2"><strong>Password again:&nbsp; </strong></label><input type="password" id="password2" name="password2" /> <strong class="error" data-field="password2"></strong><br>
                    <br>
                    <br>
                    <label><strong>E-mail:&nbsp; </strong></label><input type="text" id="email" name="email" value="<?php echo $_POST['email']; ?>" /> <strong class="error" data-field="email"></strong><br>
                    <br>
                    <button type="submit">Create account</button>
                </form>
      </div>
    <?php
}

/* overenie, ci sedi username */
function check_username($username){
    $link = spoj_s_db();
    $result = mysql_query("SELECT COUNT(`id`) as count FROM `User` WHERE `username`='".mysql_escape_string($username)."'",$link);
    $count = mysql_fetch_assoc($result)['count'];
    if (count > 0) return false;   
    
    if(!isset($username)) return false;
    if ((strlen($username) < 5) || (strlen($username) > 20)) return false;
    return true;
}

/* overenie, ci sedi heslo */
function check_password($password1, $password2){
    if (strlen($password1) < 5) return false;
    if(strcmp($password1,$password2) != 0) return false; 
    return true;
}

/* overenie emailu */
function check_email($email){
    $link = spoj_s_db();
    $result = mysql_query("SELECT COUNT(`id`) as count FROM `User` WHERE `email`='".mysql_escape_string($email)."'",$link);
    $count = mysql_fetch_assoc($result)['count'];
    if (count > 0) return false;    
    
    if(!isset($email)) return false;
    return true;
}

/* overenie, ci pri registracii uzivatel zadal skutocne vsetko */
function isset_everything(){
    if ((isset($_POST['username'])) && (isset($_POST['email'])) && (isset($_POST['password'])) && (isset($_POST['password2'])) ) return true;
    return false;
}


function login($username, $password) {
    $link = spoj_s_db();
    $result = mysql_query("SELECT * FROM  `User` WHERE username =  '" . mysql_escape_string($username) . "'AND password =  '" . md5($password) . "'", $link);
    if (mysql_num_rows($result) > 0){
        return true;
    }
    else{
        return false;
    }
}


/* vrati id vlastnika albumu */
function get_owner_id($album_id){
    $link = spoj_s_db();
    $result = mysql_query("SELECT `owner_id` FROM `Album` WHERE `id`=".mysql_escape_string($album_id), $link);
    return mysql_fetch_assoc($result)['owner_id'];
        
}


/* vrati meno albumu na zaklade jeho id */
function get_album_name($album_id){
    $link = spoj_s_db();
    $result = mysql_query("SELECT name FROM `Album` WHERE id = ".mysql_escape_string($album_id), $link);
    return mysql_fetch_assoc($result)['name'];
}


/* vrati komenty k albumu s album_id */
function get_comments($album_id){
	$link = spoj_s_db();
	$result = mysql_query("SELECT `user_id`,`time`,`text`,`id` FROM `Comment` WHERE `album_id`=".mysql_escape_string($album_id), $link);
	return $result;
}


/* vrati meno pouzivatela k jeho id */
function get_username($user_id){
    $link = spoj_s_db();
    $result = mysql_query("SELECT `username` FROM `User` WHERE `id`=".mysql_escape_string($user_id), $link);
    return mysql_fetch_assoc($result)['username'];
}


function bootstrap_header($title){
?>
<!DOCTYPE html>
<html lang="en">
    <head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="raf" >
    <link rel="shortcut icon" href="http://getbootstrap.com/assets/ico/favicon.ico">

    <title><?php echo $title; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  <style type="text/css">
  </style>
  <?php
  }


function bootstrap_scripts(){
?>
   
         <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <iframe style="display: none !important; position: fixed !important; padding: 0px !important; margin: 0px !important; left: 0px !important; top: 0px !important; width: 100% !important; height: 100% !important; background-color: transparent !important; z-index: 2147483647 !important; border: none !important;"></iframe>

<?php
}


function echo_logout(){
	echo "logged in as ".$_SESSION['username']." <a href='logout.php'>logout</a>";
    if (!isset($_GET['user_id'])){
        $_GET['user_id'] = $_SESSION['user_id']; /*ak neni nastavene v GET, cie albumy chceme, tak zobrazime albumy prihlaseneho pouzivatela */
    }
}

/* vypisanie commentu */
function echo_comment($user_id, $time, $text, $comment_id){
    if (($user_id == $_SESSION['user_id']) || ($_SESSION['username'] == 'admin') || (get_album_owner_of_comment($comment_id) == $_SESSION['user_id'])){
        echo "<a href='delete_comment.php?comment_id=".$comment_id."' class='comment' data-comment_id='".$comment_id."'>x</a><br> \n";    
    }
    echo get_username($user_id)." said: <br>\n";
    echo $text."<br>\n";
    echo date($time)."<br>\n";
}


/* vrati vlastnika albumu, ku ktoremu prislucha comment s danym id */
function get_album_owner_of_comment($comment_id){
    $link = spoj_s_db();
    $result = mysql_query("SELECT owner_id FROM `Comment` JOIN `Album` ON (`Comment`.album_id = `Album`.id) WHERE `Comment`.id = ".mysql_escape_string($comment_id), $link); 
    return mysql_fetch_assoc($result)['owner_id'];
}


function png2jpg($originalFile, $outputFile, $quality) {
    $image = imagecreatefrompng($originalFile);
    imagejpeg($image, $outputFile, $quality);
    imagedestroy($image);
}

?>