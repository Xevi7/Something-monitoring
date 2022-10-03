<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/style.css?v=<?php echo time(); ?>">
    <script src="./javascript/jquery.js"></script>
    <script defer src="./javascript/script.js"></script>
</head>
<body>
    <div id="body_login">
        <div>
            <div>
                <img src="./assets/person 1.png" alt="no img">
            </div>
            <form method="POST" action="home.php" name="loginForm">
                <div>
                    <input type="text" name="username" placeholder="Enter your username">
                    <div id="errUser"></div>
                </div>
                <div>
                    <input type="password" placeholder="Enter your password">
                </div>
                <button type="button" onclick="loginValidation()">Log in</button>
            </form>
        </div>
    </div>
</body>
</html>