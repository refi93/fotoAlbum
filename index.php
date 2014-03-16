
<html>

<head>
<title>fotoAlbum</title>
</head>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<script type="text/javascript" src="scripts/fancybox/source/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="scripts/fancybox/source/jquery.fancybox.css" />
<link rel="stylesheet" type="text/css" href="scripts/style.css"  />

<script>
$(document).ready(function(){
	$("a[rel=lightbox]").fancybox({
		'transitionIn'		: 'elastic',
		'transitionOut'		: 'elastic'		
	});
	$("ul li").append("<span></span>");
});</script>


<body>

<h1>fotoAlbum</h1>
<?php 
	function random_orientation(){		
		$orientation = array('&h=194&w=224', '&h=224&w=194' );
		shuffle($orientation);
		foreach($orientation as $o){
			return $o;
		}		
	};
?>


<?php 
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/images/'; 
$files = scandir('images/'); 
?>

<ul>
<?php foreach ($files as $file){ 
	if ($file == '.' || $file == '..'){ 
		echo ''; 
	} else {
?>
<li ><a href="<?php echo $path . $file; ?>" rel='lightbox' ><img src="scripts/timthumb.php?src=<?php echo $path . $file; ?>&h=194&w=224&zc=1&q=100" /></a></li>
<?php } }?>
</ul>



</body>

</html>
