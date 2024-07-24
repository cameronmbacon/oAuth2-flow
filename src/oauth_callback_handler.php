<?php

session_start();
require '../src/includes/db.php';
require '../vendor/autoload.php';
require '../src/oauth_providers.php';

$config = require '../config.php';
$provider = null;

if (isset($_GET['provider']) && $_GET['provider'] === 'google') {
    $provider = $googleProvider;
} else {
    $provider = $githubProvider;
}

if (!isset($_GET['code'])) {
    $authorizationUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authorizationUrl);
    exit;
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit;
} else {
    try {
        $accessToken = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
        $resourceOwner = $provider->getResourceOwner($accessToken);
        $user = $resourceOwner->toArray();

        $sql = $pdo->prepare('SELECT * FROM users WHERE username = ?;');
        $sql->execute([$user['login'] ?? $user['email']]);
        if ($sql->rowCount() == 0) {
            $sql = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?);');
            $sql->execute([$user['login'] ?? $user['email'], '']);
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['login'] ?? $user['email'];
        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        exit('Failed to get access token: ' . $e->getMessage());
    }
}