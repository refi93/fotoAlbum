    <!DOCTYPE html>
    <head>
        <meta charset="utf-8">
        <title>Register</title>
        
        
        
    </head>

    <body>
            <?php
            include('functions.php');
            include('constants.php');
            
            $link = spoj_s_db();
            if ((isset_everything()) && (check_username($_POST['username'])) && (check_email($_POST['email'])) && (check_password($_POST['password'], $_POST['password2'])) ) {
                $result = mysql_query("SELECT * FROM  `User` WHERE  `username` =  '" . $_POST['username'] . "'", $link);
 
                
                if ($_POST['username'] == '') {
                    echo '<div style="color:white; margin: 10px 10px 10px 10px;">Empty username not allowed</div>';
                    print_register_form();
                } else
                if (mysql_num_rows($result) > 0) {
                    echo '<div style="color:white; margin: 10px 10px 10px 10px;">' . $_POST['username'] . " already exists</div>";
                    print_register_form();
                } else if ($_POST['password'] != $_POST['password2']) {
                    echo '<div style="color:white; margin: 10px 10px 10px 10px;">retyped password does not match</div>';
                    print_register_form();
                } else {
                    $result = mysql_query("INSERT INTO  `User` (`username` , `password`, `email`) VALUES ('" . $_POST['username'] . "',  '" . md5($_POST['password']) . "','".$_POST['email']."');", $link);
                    
                    echo '<div style="color:white; margin: 10px 10px 10px 10px;">Sucessfully registered</div>';
                    echo '<br/><div style="color:white; margin: 10px 10px 10px 10px;"> <a href="./albums.php">return</a></div>';
                }
            }
            else
                print_register_form();
            ?>
    </body>
</html>
