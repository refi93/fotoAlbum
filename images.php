<?php 
/* stranka obsahujuca fotky albumu */
$number_of_displayed_comments = 4;



if (!isset($_GET['album_id'])) {
    header('HTTP/1.0 403 Forbidden');
    die('You are not allowed to access this file.');     
}


include 'config.php'; 
include 'functions.php';


grant_privileges_to_admin(get_owner_id($_GET['album_id']));

if (!check_login_album($_GET['album_id'])){
    echo "ACCESS DENIED";
    return;
}


image_page_header('Images');
?>



<script type="text/javascript">

$(document).ready(function()
{

var settings = {
	url: "upload.php?album_id=<?php echo $_GET['album_id']; ?>",
	method: "POST",
	allowedTypes:"jpg,png,gif,doc,pdf,zip",
	fileName: "myfile",
	multiple: true,
	onSuccess:function(files,data,xhr)
	{
		$("#status").html("<font color='green'>Upload successful</font>");
	},
	onError: function(files,status,errMsg)
	{		
		$("#status").html("<font color='red'>Upload failed</font>");
	}
}
$("#mulitplefileuploader").uploadFile(settings);

});
</script>

<script type="text/javascript">
displayedRestOfComments = false;
function displayRestOfComments()
{
    if (displayedRestOfComments){
        $('.comment_type2').css({
          'display' : 'none'     
        });
        document.getElementById("displayRestOfComments").innerHTML="Display rest of Comments";
    }
    else{
       $('.comment_type2').css({
         'display' : 'inline'     
       }); 
       document.getElementById("displayRestOfComments").innerHTML="Hide rest of Comments";          
    }  
    displayedRestOfComments = !displayedRestOfComments;
       
}
</script>
<?php
    echo_form_submit_script("#comment_form","add_comment.php");
    echo_link_click_script(".comment","delete_comment.php");
?>
</head>
<body>

<div class="imagePageTop">
<?php echo_logout(); ?>

<h1><?php echo get_album_name($_GET['album_id']); ?></h1>
<br>
<br>

<?php
/* toto vypisujeme, len ak album patri prihlasenemu pouzivatelovi */
if (get_owner_id($_GET['album_id'])==$_SESSION['user_id']){
?>


<ul class="nav nav-pills">
    <li><a href ='./albums.php'>back to albums</a></li>
    <li><a href ='./share_album.php?album_id=<?php echo $_GET['album_id'];?>'>share album</a></li>
    <li><a href ='./delete_image_list.php?album_id=<?php echo $_GET['album_id']?> '>delete images</a></li>
    <li><a href ='./edit_image_list.php?album_id=<?php echo $_GET['album_id']?> '>edit image</a></li>
</ul>

<br>
<br>

<div id="mulitplefileuploader">Upload</div>

<div id="status"></div>
</div>
<?php 
}
?>

<?php 
	function random_orientation(){		
		$orientation = array('&h=194&w=224', '&h=224&w=194' );
		shuffle($orientation);
		foreach($orientation as $o){
			return $o;
		}		
	};
	
?>

<br>
<br>


<?php 
//$real_path =  'http://' . $_SERVER['SERVER_NAME'] . '/~korbas4/fotoAlbum/images/'; 
$path_to_image = './get_image.php?image_id=';
//$files = scandir('images/'); 
?>
<div id ="images" style="display: inline-block">
<ul>


<?php 
    $link = spoj_s_db();
	$result = mysql_query("SELECT * FROM  `Photo` WHERE album_id =".mysql_escape_string($_GET['album_id']), $link);
    while ($row = mysql_fetch_assoc($result)) {
        $file = $row['id'].'.jpg';
?>
    <li ><a href="<?php echo $path_to_image . $file; ?>" rel='lightbox' ><img src="timthumb.php?id=<?php echo $row['id'];?>"<?php echo $TIMTHUMB_PATH.$file; ?>&amp;h=194&amp;w=224&amp;zc=1&amp;q=100" alt="<?php echo $file; ?>" /></a></li>
	
<?php
    }
?>
</ul>
</div>
<br>

<div id="comment_section">
<h2>Comments</h2>
<br>

<?php
    $result = get_comments($_GET['album_id']);  
    $counter_of_comments = 0;  
    while($row = mysql_fetch_assoc($result)){
        if ($counter_of_comments > $number_of_displayed_comments){
            echo "<div class='comment_type2' style='display: none'>";   
        }
        else echo "<div class='comment_type1' style='display: inline'>";
?>

<?php
        echo_comment($row['user_id'], $row['time'], $row['text'],$row['id']);
        $counter_of_comments++;
?>
<hr style='width:30em; position: absolute; left:4em;'>
<br><br>

<?php
        echo "</div>";  
    }    
    if ($counter_of_comments > ($number_of_displayed_comments + 1)){
        echo "<br>";
        echo '<a id="displayRestOfComments" href="" onclick="displayRestOfComments(); return false;">Display rest of comments </a>';
    }    
    /* ak ide o prihlaseneho pouzivatela, dame mu moznost komentovat */
    if (isset($_SESSION['user_id'])){
?>
<br>
<br>
    <div id ="comment">
        <form action="add_comment.php?album_id=<?php echo $_GET['album_id']?>" method="post" id="comment_form">
				<textarea id="comment_text" name="comment_text" placeholder="Comment here..." style="width: 30em; height:5em;" ></textarea> <br/>
                <input type="hidden" name="album_id" value="<?php echo $_GET['album_id'];?>"> 				
				<input type="submit" value="Comment">        
        </form>
    </div>    
<?php  
    }
?>
</div>
<?php
bootstrap_scripts();
restore_admin_privileges();
?>

</body>

</html>

