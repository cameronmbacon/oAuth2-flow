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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar" style="background-color: teal;">
            <a class="btn btn-outline-light" href="index.php">Home</a>
        </nav>
        <div class="col-md-10 mx-auto mt-5 col-lg-5">
            <h1 class="h3 fw-normal text-center">Login</h1>
            <?php if ($error): ?>
                <p style="color: red"><?php echo $error; ?></p>
            <?php endif; ?>
            <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" action="login.php" method="post">
                <div class="form-floating mb-3">
                    <input class="form-control" type="text" name="username" id="floatingInput" required>
                    <label class="form-label" for="floatingInput">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control" type="password" name="password" id="floatingPassword" required>
                    <label class="form-label" for="floatingPassword">Password</label>
                </div>
                <button class="btn btn-lg btn-primary px-4 mt-3 w-100" type="submit">Login</button>
                <hr class="my-4">
                <p class="text-body-secondary">Need an account? <a href="signup.php">Register here</a></p>
            </form>
            <p class="mt-2">Or choose an account to log in:</p>
            <div class="w-100 col-md-10 mx-auto col-lg-5">
                <a class="btn text-light px-2 mx-auto mb-1 w-100" style="background-color: rgba(0, 0, 0, 0.8);" href="oauth_callback.php?provider=github">Login with GitHub</a>
                <a class="btn text-light px-2 mx-auto mb-1 w-100" style="background-color: rgba(255, 0, 0, 0.8);" href="oauth_callback.php?provider=google">Login with Google</a>
            </div>
        </div>
    </body>
</html>