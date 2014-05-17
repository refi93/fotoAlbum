<?php
    include 'functions.php';
    include 'config.php';    
    image_page_header('Albums shared with you'); 
    check_if_logged_in();   
?>


<?php 
$path =  $server_path.'images/'; 
$path_to_album = 'http://' . $_SERVER['SERVER_NAME'] . '~korbas4/fotoAlbum/images.php';
echo "logged in as ".$_SESSION['username']." <a href='./logout.php'>logout</a>";
if (!isset($_GET['user_id'])){
    $_GET['user_id'] = $_SESSION['user_id']; //ak neni nastavene v GET, cie albumy chceme, tak zobrazime albumy prihlaseneho pouzivatela
}

?>
<h1> Albums shared with you</h1>

<br/>
<ul class="nav nav-pills">
    <li><a href="./albums.php">back to albums</a></li>
</ul>
<br/>
<br/>


<ul>


<?php
    $link = spoj_s_db();


    /* vyberame albumy pouzivatela $_SESSION['user_id'] ktore su zdielane s prihlasenym pouzivatelom */
    $result = mysql_query("(SELECT DISTINCT Album.id,name,username FROM  `Album` JOIN `Share` ON (Share.album_id = Album.id) 
                                                                  JOIN `GroupMembers` ON (GroupMembers.group_id = Share.group_id)
                                                                  JOIN `User` ON (`Album`.owner_id = `User`.id) 
                                                                  WHERE member_id=".$_SESSION['user_id'].")", $link); 
    while ($row = mysql_fetch_assoc($result)) {
        $link_photo = spoj_s_db();
        /* vyberieme reprezentativnu fotku pre album */
        $result_photo = mysql_query("SELECT * FROM  `Photo` WHERE album_id = ".mysql_escape_string($row['id'])." LIMIT 1", $link);
        
        
        $image_id = mysql_fetch_assoc($result_photo)['id'];
        $file = $image_id.'.jpg';
?>  
        <li >
      
            <span style="position: absolute; left: 30px; top: 220px;"><?php echo $row['name']." (".$row['username'].")"; ?> </span>
            
            <a href="./images.php<?php echo '?album_id='.$row['id']; ?>" rel='lightbox' > 
                <img src="scripts/timthumb.php?id=<?php echo $image_id; ?>" /> 
            </a>   
        </li>
<?php
    }
?>
</ul>
