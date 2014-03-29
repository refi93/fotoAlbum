<?php 
    include 'functions.php';
    
    check_if_logged_in();    
    
    if(!isset($_GET['group_id'])){
        echo "ACCESS DENIED";
        return;    
    }    
        
    $link = spoj_s_db();
    
    
    if(isset($_POST['username']) && ($_POST['username'] != $_SESSION['username'])){
        /* presvedcime sa, ze tento clen do skupiny este nebol pridany */
        $result = mysql_query("SELECT COUNT(member_id) as count FROM GroupMembers WHERE `group_id`=".$_GET['group_id'], $link);
		  /* ak nebol, tak ho tam pridame */        
        if (mysql_fetch_assoc($result)['count'] == 0){
            mysql_query("INSERT INTO `GroupMembers`(`group_id`, `member_id`) SELECT ".$_GET['group_id'].",id FROM `User` WHERE username = '".$_POST['username']."'", $link);    
        }
    }   
    
    
    $result = mysql_query("SELECT name FROM `Group` WHERE id = ".$_GET['group_id'], $link);
    echo "<h1>".mysql_fetch_assoc($result)['name']."</h1>";    
    
    $result = mysql_query("SELECT username, User.id AS id FROM `GroupMembers` JOIN `User` ON (User.id = GroupMembers.member_id) WHERE GroupMembers.group_id = ".$_GET['group_id'],$link);
    
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['username']." <a href='remove_user_from_group.php?user_id=".$row['id']."&group_id=".$_GET['group_id']."'>remove</a><br/>";
    }    
?>

<form method="post" action="group_members.php?group_id=<?php echo $_GET['group_id'];?>">
    <input type="text" name="username"/>
    <input type="submit" value="Add member"/>
</form>