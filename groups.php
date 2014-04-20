<?php
include 'functions.php';
image_page_header('Groups');
?>
<div id="content">
<?php
echo_logout();
?>
<h1> Groups </h1>

<ul class="nav nav-pills">
    <li><a href="./albums.php">back to albums</a></li>
</ul>
<br/>
<?php
$link = spoj_s_db();

check_if_logged_in();


if (isset($_POST['group_name']) && isset($_SESSION['user_id'])){
    if ($_POST['group_name'] == '') 
        echo "empty name not allowed";
    $result = mysql_query("SELECT COUNT(id) as count FROM `Group` WHERE `name`='".$_POST['group_name']."'");
    if (mysql_fetch_assoc($result)['count'] == 0){  
        mysql_query("INSERT INTO `foto_album`.`Group` (`owner_id`, `name`) VALUES ('".$_SESSION['user_id']."', '".$_POST['group_name']."')",$link);
    }
    else{
        echo "group name ".$_POST['group_name']." already exists";    
    }
    unset($_POST);
}

$result = mysql_query("SELECT * FROM `Group` WHERE `owner_id`=".$_SESSION['user_id'], $link);


while ($row = mysql_fetch_assoc($result)) {
        echo "<a href = './group_members.php?group_id=".$row['id']."'>".$row['name']."</a> <a href = './remove_group.php?group_id=".$row['id']."'>remove</a><br/>";
}

?>

<form method="post" action="groups.php">
    <input type="text" name="group_name"/>
    <input type="submit" value="Add group"/>
</form>

<?php
bootstrap_scripts();
?>
</div>
</body>
</html>