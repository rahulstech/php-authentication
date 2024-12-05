<?php 
require_once('auth.php');

logout();

$user = isLoggedIn();
$loggedin = !is_null($user);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css" >

    <?php if ($loggedin) {  ?>
        
    <title>Profile | <?php echo $user['name']; ?></title>

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
    <?php
        }
        else {
    ?>
    <a class="btn-link" href="./?logout">Log Out</a>
    <?php
        }
    ?>

    <h2><?php if ($loggedin) { echo "Hello ".$user['name']; } else { echo "Hello Guest"; } ?></h2>
    </div>
</body>
</html>