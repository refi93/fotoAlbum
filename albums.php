<?php

    /* stranka, kde su zobrazene albumy pouzivatela */
    include 'functions.php';
    include 'config.php';    
    image_page_header('Albums'); 
    check_if_logged_in(); /* overime, ci si albumy ide prezerat prihlaseny pouzivatel */  
    echo_form_submit_script("#create_album","create_album.php");
    echo_form_submit_script(".rename_album","rename_album.php" );
    grant_privileges_to_admin($_GET['user_id']);
?>


<script>
    displayCreateAlbumForm = false; /* flag pre formular pre novy album - defaultne je skryty, po kliku sa striedavo zobrazuje a skryva */
        
    
    function renameAlbum(album_id){
        $(document).ready(function(){
            $("#rename"+album_id).css({
                'display' : 'inline'      
            });
            
            $("#rename_button_span"+album_id).css({
                'display' : 'none'      
            });
        });
    }
    
    
    function showCreateAlbumForm(){
        displayCreateAlbumForm = !displayCreateAlbumForm;
        
        if(displayCreateAlbumForm){
            $('#create_album').css({
                'display' : 'inline'      
            });
        }
        else{
            $('#create_album').css({
               'display' : 'none'      
            });
        }
        return false;
    }
</script>
</head>
<body>

<div id="imagePageTop">

<?php 
$path =  $server_path.'get_image.php?image_id='; /*'http://' . $_SERVER['SERVER_NAME'] . '/~korbas4/fotoAlbum/get_image.php?image_id='; */
$path_to_album = $server_path.'images.php'; /*'http://' . $_SERVER['SERVER_NAME'] . '/~korbas4/fotoAlbum/images.php'; */

echo_logout();

?>
<h1> Albums </h1>
<br/>
<br/>

<?php
/* ak prihlaseny pouzivatel je ten isty, ktoremu patria albumy */ 
if ($_GET['user_id'] == $_SESSION['user_id']){ 
?>

<br/>
<ul class="nav nav-pills">
    <li><a href="./delete_album_list.php">delete album</a></li>
    <li><a href="" id="create_album_trigger" onclick="showCreateAlbumForm(); return false;">create new album</a></li>
    <li><a href="./groups.php">manage groups</a></li>
    <li><a href="./shared_with.php">albums shared with you</a></li>
</ul>
<br/>
<br/>

<div id="create_album" style="display: none">            
    <form action="create_album.php" method="post">
        <input type="text" name="name">
        <input type="submit" value="create">            
    </form>
</div>
</div>

<?php } ?>
<ul>


<?php
    $link = spoj_s_db();

    if ($_GET['user_id'] == $_SESSION['user_id']){
       /* ak pouzivatel ide zobrazit vlastne albumy, zobrazia sa vsetky */	
	   $result = mysql_query("SELECT id,name FROM  `Album` WHERE owner_id = ".mysql_escape_string($_GET['user_id']), $link);	
	}
    else{
       /* vyberame albumy pouzivatela $_GET['user_id'] ktore su bud verejne alebo zdielane s prihlasenym pouzivatelom */
       $result = mysql_query("(SELECT DISTINCT id,name FROM  `Album` JOIN `Share` ON (Share.album_id = Album.id) 
                                                                    JOIN `GroupMembers` ON (GroupMembers.group_id = Share.group_id) 
                                                                    WHERE member_id=".$_SESSION['user_id']." AND owner_id=".mysql_escape_string($_GET['user_id']).")".
             " UNION
             (SELECT id,name FROM `Album` WHERE owner_id=".mysql_escape_string($_GET['user_id'])." AND public=1)", $link);        
    }
    while ($row = mysql_fetch_assoc($result)) {
        $link_photo = spoj_s_db();
        $result_photo = mysql_query("SELECT * FROM  `Photo` WHERE album_id = ".$row['id']." LIMIT 1", $link);
        
        $image_id = mysql_fetch_assoc($result_photo)['id'];        
        $file = $image_id.'.jpg';
?>  
        <li >
<?php
        /* ak prihlaseny pouzivatel je ten isty, ktoremu patria albumy */ 
        if ($_GET['user_id'] == $_SESSION['user_id']){ 
?>         
            <span id="rename_button_span<?php echo $row['id'] ?>" style="position: absolute; left: 30px; top: 220px;"><?php echo $row['name']; ?> <button id="rename_button<?php echo $row['id'];?>" onclick="renameAlbum(<?php echo $row['id']?>);">rename</button></span>
            <div id="rename<?php echo $row['id'] ?>" style="display: none; position: absolute; left: 30px; top: 220px">            
            <form action="rename_album.php" method="post" class="rename_album">
                <input type="text" name="name" value="<?php echo $row['name']; ?>">
                <input type="hidden" name="album_id" value="<?php echo $row['id'];?>"> 
                <input type="submit" value="rename">           
            </form>
            </div>
<?php 
        } 
?>
            <a href="<?php echo $path_to_album .'?album_id='.$row['id']; ?>" rel='lightbox' > 
                <img src="./timthumb.php?id=<?php echo $image_id; ?>" alt="<?php echo $file; ?>" /> 
            </a>   
        </li>
<?php
    }
    restore_admin_privileges();
?>
</ul>
</body>
</html>