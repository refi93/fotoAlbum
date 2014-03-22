<?php

function spoj_s_db() { // spojenie s databazou
    include('config.php');

    $link = mysql_connect('localhost', $php_db_name, $php_db_pwd) or die('Could not connect to mysql server.');
    mysql_select_db($db_name, $link) or die('Could not select database.');
    mysql_query("SET CHARACTER SET 'utf8'", $link);
    return $link;
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

?>