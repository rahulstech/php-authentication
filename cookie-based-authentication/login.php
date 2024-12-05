<?php 
require_once('auth.php');

// step 1: if logged in then go to user profile

if (!is_null(isLoggedIn())) {
    header('Location: ./');
}

// step 2: perform login

$error = login();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="content">
    <a class="btn-link" href="./">Home</a>
    <form class="input-form" action="./login.php" method="POST">
        <?php
            if (!is_null($error)) {
        ?>
        <div class="message-box message-error">
            <p><?php echo $error['message']; ?></p>
        </div>
        <?php
            }
        ?>
        <div class="input-form-section">
            <label class="form-label" for="username">Username</label>
            <input class="form-input" type="text" name="username" />
        </div>
        <div class="input-form-section">
            <label class="form-label" for="password">Password</label>
            <input class="form-input" type="password" name="password" />
        </div>
        <button class="form-button form-primary-button" type="submit">Login</button>
    </form>
    </div>
    
</body>
</html>