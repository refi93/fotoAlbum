<!DOCTYPE html>
<html>
    <head>
        <title>Form validation</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" href="scripts/form_validation.css" media="all" />
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
    <body>
        <header>
            <h1>fotoAlbum</h1>
        </header>
        <nav>
            <a href="#">Home</a>
            <a href="#">Products</a>
            <a href="#">Contact</a>
            <a href="#">About</a>
            <div style="clear: both;"></div>
        </nav>
        <div class="page">
            <div class="content">
                <h2>Register</h2>
 
                <form id="register" method="post" action="register.php">
                    Required fields are in <strong>bold</strong>.<br />
                    <br />
 
                    <label for="username"><strong>Username</strong></label><input type="text" id="username" name="username" /> <strong class="error" data-field="username"></strong><br />
                    <br />
                    <label for="password"><strong>Password</strong></label><input type="password" id="password" name="password" /> <strong class="error" data-field="password"></strong><br />
                    <br />
                    <label for="password2"><strong>Password again</strong></label><input type="password" id="password2" name="password2" /> <strong class="error" data-field="password2"></strong><br />
                    <br />
                    <br />
                    <label><strong>E-mail</strong></label><input type="text" id="email" name="email" /> <strong class="error" data-field="email"></strong><br />
                    <br />
                    <button type="submit">Create account</button>
                </form>
            </div>
        </div>
        <footer>
            <p>
                Site by me. &copy; 2013.
            </p>
        </footer>
    </body>
</html>

