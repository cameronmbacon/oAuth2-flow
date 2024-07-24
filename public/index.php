<?php
session_start();
?>

<!DOCTYPE hmtl>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Simple OAuth2 Implementation</title>
    </head>
    <body>
        <nav class="navbar" style="background-color: teal;">
            <a class="btn btn-outline-light" href="index.php">Home</a>
            <?php if (isset($_SESSION["username"])): ?>
                    <a class="btn btn-outline-light float-right" href="logout.php">Logout</a>
                <?php endif; ?>
        </nav>
        <div class="px-4 py-5 my-5 text-center">
            <h1 class="display-5 m-5 fw-bold text-body-emphasis">Simple OAuth2 Implementation</h1>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <?php if (isset($_SESSION["username"])): ?>
                    <h4>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h4>
                <?php else: ?>
                    <a class="btn btn-lg btn-primary px-4" href="signup.php">Signup</a>
                    <a class="btn btn-lg btn-outline-secondary px-4" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>