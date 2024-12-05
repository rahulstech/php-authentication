<?php 
$authenticator = include_once('auth.php');

if ($authenticator->logout()) {
    header('Location: ./');
}

$loggedin = $authenticator->isLoggedIn();
$user = $authenticator->getLoggedUser();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css" >

    <?php if ($loggedin) {  ?>
        
    <title>Profile | <?php echo $user->name; ?></title>

    <?php } else {?>

    <title>Home</title>

    <?php }?>
    
</head>
<body>
    <div class="content">
    <?php
        if (!$loggedin) {
    ?>
    <a class="btn-link" href="./login.php">LogIn</a>
    <a class="btn-link" href="./registration.php">Register</a>
    <?php
        }
        else {
    ?>
    <a class="btn-link" href="./?logout">Log Out</a>
    <?php
        }
    ?>

    <h2><?php if ($loggedin) { echo "Hello ".$user->name; } else { echo "Hello Guest"; } ?></h2>
    </div>
</body>
</html>