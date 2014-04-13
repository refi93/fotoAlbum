<?php
    include 'functions.php';
    include 'config.php';    
    image_page_header(); 
    check_if_logged_in();   
?>


<?php 
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images/'; 
$path_to_album = 'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images.php';
echo "logged in as ".$_SESSION['username']." <a href='logout.php'>logout</a>";
if (!isset($_GET['user_id'])){
    $_GET['user_id'] = $_SESSION['user_id']; //ak neni nastavene v GET, cie albumy chceme, tak zobrazime albumy prihlaseneho pouzivatela
}

?>
<h1> Albums shared with you</h1>


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
        $result_photo = mysql_query("SELECT * FROM  `Photo` WHERE album_id = ".$row['id']." LIMIT 1", $link);
        
        $file = mysql_fetch_assoc($result_photo)['id'].'.jpg';
?>  
        <li >
      
            <span style="position: absolute; left: 30px; top: 220px;"><?php echo $row['name']." (".$row['username'].")"; ?> </span>
            
            <a href="<?php echo $path_to_album .'?album_id='.$row['id']; ?>" rel='lightbox' > 
                <img src="scripts/timthumb.php?src=<?php echo $path . $file; ?>&h=194&w=224&zc=1&q=100" /> 
            </a>   
        </li>
<?php
    }
?>
</ul>