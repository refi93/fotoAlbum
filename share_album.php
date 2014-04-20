<?php
include 'functions.php';
$link = spoj_s_db();


image_page_header('Share album');
?>
<div id="content" style="padding-left: 4em;">
<h1>Share album</h1>


<br/>
<br/>

<ul class="nav nav-pills">
    <li><a href ='./albums.php'>back to albums</a></li>
</ul>

<br/>

<form action="share_album_execute.php?album_id=<?php echo $_GET['album_id']; ?>" method="post">
<?php 
    
    /* chceme, aby skupiny, ktorym je dany album uz zdielany, mali oznaceny checkbox - tento select robi left join, aby skupiny, ktorym album este nebol zdialny mali NULL v stlpci album_id a ostatne tam budu mat cislo albumu */
	$result = mysql_query("SELECT * FROM  `Group` LEFT JOIN `Share` ON (`Share`.group_id=`Group`.id AND `Share`.album_id=".mysql_escape_string($_GET['album_id']).") WHERE `owner_id`=".$_SESSION['user_id']." AND (`album_id`=".$_GET['album_id']." OR `album_id` IS NULL)", $link);
    while ($row = mysql_fetch_assoc($result)) {
        echo "<input type='checkbox' id ='".$row['id']."' name='".$row['id']."' value='".$row['id']."' ";
        if($row['album_id'] == $_GET['album_id']) 
            echo 'checked="checked"';
        echo "/><label for='".$row['id']."'> <a href='./group_members.php?group_id=".$row['id']."'>".$row['name']."</a></label><br/>";
    }
?>
<input type="submit" value="Share to chosen groups">
</form>
</div>
</body>
</html>