<?php
session_start();
require '../src/includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = 'Please fill in both fields.';
    } else {
        $sql = $pdo->prepare('SELECT * FROM users WHERE username = ?;');
        $sql->execute([$username]);
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <a href="index.php">Home</a>
        <a href="signup.php">Signup</a>
        <h1>Login</h1>
        <?php if ($error): ?>
            <p style="color: red"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Login</button>
        </form>
        <p>OR</p>
        <a href="oauth_callback.php?provider=github">Login with GitHub</a>
        <a href="oauth_callback.php?provider=google">Login with Google</a>
    </body>
</html>