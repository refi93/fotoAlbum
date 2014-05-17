<?php
    include 'functions.php';

	define('UPLOAD_DIR', 'images/');
    foreach($_POST as $data)	
	$img = $data;
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR . uniqid() . '.png';
    $success = file_put_contents($file, $data);	
	
	
    $image = imagecreatefrompng($file);
    $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
    imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
    imagealphablending($bg, TRUE);
    imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
    imagedestroy($image);
    $quality = 75; // 0 = worst / smaller file, 100 = better / bigger file 
    imagejpeg($bg, UPLOAD_DIR . uniqid() . ".jpg", $quality);
    ImageDestroy($bg);
    unlink($file);
	
	print $success ? $file : 'Unable to save the file.';
?>