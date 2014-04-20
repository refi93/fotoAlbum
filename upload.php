<?php
include 'functions.php';

if ((!isset($_GET['album_id'])) || (!check_login_album($_GET['album_id']))) {
    header('HTTP/1.0 403 Forbidden');
    die('You are not allowed to access this file.');     
}

/*If directory doesnot exists create it.*/
include 'functions.php';
$output_dir = "./images/";


$link = spoj_s_db();

if(isset($_FILES["myfile"]))
{
	$ret = array();

	$error =$_FILES["myfile"]["error"];
	$query = "INSERT INTO `Photo` (`album_id`) VALUES (".mysql_escape_string($_GET['album_id']).");";
    {
    	if(!is_array($_FILES["myfile"]['name'])) /*single file*/
    	{
    	    /* vlozenie fotky do databazy */
    	    mysql_query($query, $link);
            $result = mysql_insert_id($link);           
            
       	 	$fileName = $result.'.jpg'; /*$_FILES["myfile"]["name"];*/
            move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir. $fileName /*$_FILES["myfile"]["name"]*/);
            chmod($output_dir.$fileName, 0777); // nastavime povolenia, aby sa dalo nacitavat  	 	 
       	 	$ret[$fileName]= $output_dir.$fileName;
        }
        else
		{
            $fileCount = count($_FILES["myfile"]['name']);
            for($i=0; $i < $fileCount; $i++)
       	    {
                /*$fileName = $_FILES["myfile"]["name"][$i];*/

                mysql_query($query,$link);
                $result = mysql_insert_id($link);
                $fileName = $result.'.jpg';
                                       
                move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName );
                chmod($output_dir.$fileName, 0777);
                $ret[$fileName]= $output_dir.$fileName;
            }
		}
    }
    echo json_encode($ret);
}

?>