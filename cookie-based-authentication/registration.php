
<?php 

$authenticator = include_once('auth.php');

if ($authenticator->isLoggedIn()) {
    header('Location: ./');
}

$res = $authenticator->register();
if (!is_null($res) && $res['code'] == 200) {
    header('Location: ./');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <title>Registration</title>
</head>
<body>
    <div class="content">
        <form class="input-form" action="./registration.php" method="POST">
            <?php
                if (!is_null($res) && $res['code'] != 200) {
            ?>
            <div class="message-box message-error">
                <p><?php echo $res['message']; ?></p>
            </div>
            <?php
                }
            ?>
            <p class="heading">Register New User</p>
            <div class="input-form-section">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" class="form-input" require>
            </div>
            <div class="input-form-section">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-input" require>
            </div>

            <div class="input-form-section">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-input" require>
            </div>

            <div class="input-form-section">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-input" require>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</body>
</html>