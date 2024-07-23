<?php
session_start();
?>

<!DOCTYPE hmtl>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simple OAuth2 Implementation</title>
    </head>
    <body>
        <h1>Welcome to the Simple OAuth2 Implementation</h1>
        <?php if (isset($_SESSION["username"])): ?>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="signup.php">Signup</a>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </body>
</html>