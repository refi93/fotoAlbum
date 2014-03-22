<?php
    include 'functions.php';
    include 'config.php';    
    
    image_page_header();    
?>


<script>
function renameAlbum(album_id){
    $(document).ready(function(){
        $("#rename"+album_id).css({
            'display' : 'inline'      
        });
    });
}

</script>

<?php 
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images/'; 
$path_to_album = 'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images.php'
?>
<h1> Albums </h1>

<ul>


<?php
    $link = spoj_s_db();
	$result = mysql_query("SELECT * FROM  `Album`", $link);	
	
    while ($row = mysql_fetch_assoc($result)) {
        $link_photo = spoj_s_db();
	    $result_photo = mysql_query("SELECT * FROM  `Photo` WHERE album_id = ".$row['id']." LIMIT 1", $link);
        
        $file = mysql_fetch_assoc($result_photo)['id'].'.jpg';
?>  
        <li >         
            <span style="position: absolute; left: 30px; top: 220px;"><?php echo $row['name']; ?> <button id="rename_button<?php echo $row['id'];?>" onclick="renameAlbum(<?php echo $row['id']?>)">rename</button></span>
            <div id="rename<?php echo $row['id'] ?>" style="display: none">            
            <form action="rename_album.php?album_id=<?php echo $row['id'];?>" method="post">
                <input type="text" name="name" value="<?php echo $row['name']; ?>">
                <input type="submit" value="rename">            
            </form>
            </div>
            <a href="<?php echo $path_to_album .'?album_id='.$row['id']; ?>" rel='lightbox' > 
                <img src="scripts/timthumb.php?src=<?php echo $path . $file; ?>&h=194&w=224&zc=1&q=100" /> 
            </a>   
        </li>
<?php
    }
?>
</ul>