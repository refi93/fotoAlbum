<?php

include 'functions.php';


header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Access-Control-Allow-Origin: *");

$username = $_GET['fieldValue'];

$out = new stdClass();
$out->status = true;
$out->message = "OK";



$link = spoj_s_db();
$result = mysql_query("SELECT COUNT(`id`) as count FROM `User` WHERE `username`='".mysql_escape_string($username)."'",$link);
$count = mysql_fetch_assoc($result)['count'];

if ($count > 0)
{
    $out->status = false;
    $out->message = "This username is already taken.";
}

echo json_encode($out);