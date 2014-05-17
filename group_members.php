<?php
    /* vypis clenov danej skupiny */ 
    include 'functions.php';
    image_page_header('Group members');
?>

<script src="http://code.jquery.com/jquery-1.10.2.js"></script>
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
    $(document).ready(function($){
        $('#customerAutocomplete').autocomplete({
	       source:'suggest_name.php', 
	       minLength:2
        });
    });
</script>

<?php
    echo_form_submit_script("#form1", "group_members.php?group_id=".$_GET['group_id']);
?>

</head>
<body>
<div id="content">
<?php    
    echo_logout();    
    
    
    check_if_logged_in();    
    
    if(!isset($_GET['group_id'])){
        echo "ACCESS DENIED";
        return;    
    }    
        
    $link = spoj_s_db();
    
    
    if(isset($_POST['username']) && ($_POST['username'] != $_SESSION['username'])){
        /* presvedcime sa, ze tento clen do skupiny este nebol pridany */
        $result = mysql_query("SELECT COUNT(member_id) as count FROM GroupMembers JOIN `User` ON (User.id = GroupMembers.member_id) WHERE `group_id`=".mysql_escape_string($_GET['group_id'])." AND username='".mysql_escape_string($_POST['username'])."'", $link);
		  /* ak nebol, tak ho tam pridame */        
        if (mysql_fetch_assoc($result)['count'] == 0){
            mysql_query("INSERT INTO `GroupMembers`(`group_id`, `member_id`) SELECT ".mysql_escape_string($_GET['group_id']).",id FROM `User` WHERE username = '".mysql_escape_string($_POST['username'])."'", $link);    
        }
    }   
    
    
    $result = mysql_query("SELECT name FROM `Group` WHERE id = ".$_GET['group_id'], $link);
    echo "<h1>".mysql_fetch_assoc($result)['name']."</h1>";    
?>

<ul class="nav nav-pills">
    <li><a href="./groups.php">back to groups</a></li>
</ul>
<br>

<?php    
    $result = mysql_query("SELECT username, User.id AS id FROM `GroupMembers` JOIN `User` ON (User.id = GroupMembers.member_id) WHERE GroupMembers.group_id = ".mysql_escape_string($_GET['group_id']),$link);
    
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['username']." <a href='./remove_user_from_group.php?user_id=".$row['id']."&amp;group_id=".$_GET['group_id']."' >remove</a><br>";
    }    
?>

<br>

<form method="post" action="group_members.php?group_id=<?php echo $_GET['group_id'];?>" id="form1">
    <input type="text" name="username" placeholder="username" id="customerAutocomplete" class="ui-autocomplete-input" autocomplete="off" />
    <input type="submit" value="Add member"/>
</form>
</div>
<?php
bootstrap_scripts();
?>
</body>
</html>