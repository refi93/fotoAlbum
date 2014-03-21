<?php 
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images/'; 
$files = scandir('images/'); 
?>

<h1> Delete photographs</h1>

<form action="delete.php" method="get">
<?php foreach ($files as $file){ 
    if ($file == '.' || $file == '..'){ 
        echo ''; 
    } else {
?>
    <img src="scripts/timthumb.php?src=<?php echo $path . $file; ?>&h=194&w=224&zc=1&q=100" />
    <input type="checkbox" name="<?php echo $file; ?>" value= "<?php echo $file; ?>" >
<?php } }?>
<br/>
<input type="submit" value="Delete selected photgraphs">
</form>