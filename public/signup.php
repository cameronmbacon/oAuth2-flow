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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar" style="background-color: teal;">
            <a class="btn btn-outline-light" href="index.php">Home</a>
        </nav>
        <div class="col-md-10 mx-auto mt-5 col-lg-5">
            <h1 class="h3 fw-normal text-center">Signup</h1>
            <?php if ($error): ?>
                <p style="color: red"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p style="color: green"><?php echo $success; ?></p>
            <?php endif; ?>
            <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" action="signup.php" method="post">
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="username" id="floatingInput" required>
                    <label class="form-label" for="floatingInput">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="password" name="password" id="floatingPassword" required>
                    <label class="form-label" for="floatingPassword">Password</label>
                </div>
                <button class="btn btn-lg btn-primary px-4 mt-3 w-100" type="submit">Signup</button>
                <hr class="my-4">
                <p class="text-body-secondary">Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </div>
    </body>
</html>