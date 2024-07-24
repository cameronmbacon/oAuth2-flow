<?php
session_start();
require '../src/includes/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please fill in both fields.';
    } else {
        $sql = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $sql->execute([$username]);
        if ($sql->rowCount() > 0) {
            $error = 'Username is already taken.';
        } else {
            $hash_pass = password_hash($password, PASSWORD_DEFAULT);
            $sql = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            if ($sql->execute([$username, $hash_pass])) {
                $success = 'User registration successful. You can now <a href="login.php">login</a>.';
            } else {
                $error = 'User registration failed. Please try again.';
            }
            
        }
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Signup</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <h1>Signup</h1>
        <?php if ($error): ?>
            <p style="color: red"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p style="color: green"><?php echo $success; ?></p>
        <?php endif; ?>
        <form action="signup.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Signup</button>
        </form>
    </body>
</html>