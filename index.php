<?php include 'config.php'; ?>


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


<script>

$(document).ready(function()
{

var settings = {
	url: "scripts/upload.php",
	method: "POST",
	allowedTypes:"jpg,png,gif,doc,pdf,zip",
	fileName: "myfile",
	multiple: true,
	onSuccess:function(files,data,xhr)
	{
		$("#status").html("<font color='green'>Upload successful</font>");
		location.reload();
	},
	onError: function(files,status,errMsg)
	{		
		$("#status").html("<font color='red'>Upload Failed</font>");
	}
}
$("#mulitplefileuploader").uploadFile(settings);
});
</script>


<body>

<h1>fotoAlbum</h1>

<div id="mulitplefileuploader">Upload</div>

<div id="status"></div>

<a href ='delete_list.php'>delete images</a>

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
$path =  'http://' . $_SERVER['SERVER_NAME'] . '/fotoAlbum/images/'; 
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

