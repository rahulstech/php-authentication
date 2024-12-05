<?php 
$authenticator = include_once('auth.php');

// step 1: if logged in then go to user profile

if ($authenticator->isLoggedIn()) {
    header('Location: ./');
}

// step 2: perform login

$res = $authenticator->login();
if (!is_null($res) && $res['code'] == 200) {
    header('Location: ./');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
    <div class="content">
    <a class="btn-link" href="./">Home</a>
    <form class="input-form" action="./login.php" method="POST">
        <?php
            if (!is_null($res) && $res['code'] != 200) {
        ?>
        <div class="message-box message-error">
            <p><?php echo $res['message']; ?></p>
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
        <button class="btn btn-primary" type="submit">Login</button>
    </form>
    <p>New user? <a href="./registration.php">Register</a></p>
    </div>
    
</body>
</html>