
               
<?php
include 'functions.php';
bootstrap_header("Register"); 
?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
        <script type="text/javascript" src="scripts/form_validation.js"></script>
        <script type="text/javascript">
            $(function()
            {
                $("#register").formValidation({             // Volame nas plugin, ktory je v subore form_validation.js
                    "username": [                           // Prvok username a jeho validacne pravidla
                        ["required"],                       // Musi mat nejaku hodnotu
                        ["min_length", 5],                  // Hodnota musi mat aspon 5 znakov
                        ["max_length", 20],                 // Ale nie viac ako 20
                        ["alphanumeric"],                   // Musi byt alfanumericka
                        ["ajax", "check_username.php"]      // Doplnena funkcionalita AJAXu
                    ],
                    "password": [
                        ["required"],                       // Heslo musi byt
                        ["min_length", 5]                   // Ale nech ma aspon 5 znakov
                    ],
                    "password2": [
                        ["required"],                       // Heslo musi byt znova
                        ["min_length", 5],                  // Opat aspon 5 znakov
                        ["matches", "password"]             // A musi sa rovnat prvemu heslu
                    ],
                    "email": [
                        ["required"],                       // Email musi byt
                        ["valid_email"],                    // A musi byt validny
                        ["ajax", "check_email.php"]
                    ],
                    "name": [
                        ["min_length", 3]                   // Meno byt nemusi, ale aspon 3 pismena musi mat (ak bude prazdne, validacia bude OK)
                    ]
                });
            });
        </script>
        
        
    </head>

    <body style="padding-left: 4em">
            <?php
            include('functions.php');
            include('constants.php');
            
            $link = spoj_s_db();

            if ((isset_everything()) && (check_username($_POST['username'])) && (check_email($_POST['email'])) && (check_password($_POST['password'], $_POST['password2'])) ) {
                $result = mysql_query("SELECT * FROM  `User` WHERE  `username` =  '" . mysql_escape_string($_POST['username']) . "'", $link);
                
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
            bootstrap_scripts();
            ?>
    </body>
</html>
