<?php
session_start();
require '../src/includes/db.php';
require '../vendor/autoload.php';

$config = require '../config.php';

use League\OAuth2\Client\Provider\GenericProvider;

// $logFile = '../private/logs/oauth_debug.log';
// function debug_log($message) {
//     global $logFile;
//     file_put_contents($logFile, $message . "\n", FILE_APPEND);
// }

$provider = new GenericProvider([
    'clientID'                => $config['github_client_id'],
    'clientSecret'            => $config['github_client_secret'],
    'redirectUri'             => $config['github_redirect_uri'],
    'urlAuthorize'            => 'https://github.com/login/oauth/authorize',
    'urlAccessToken'          => 'https://github.com/login/oauth/access_token',
    'urlResourceOwnerDetails' => 'https://api.github.com/user',
]);

if (!isset($_GET['code'])) {
    $authorizationUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();

    // Debug output
    error_log("Authorization URL: " . $authorizationUrl);
    error_log("OAuth2 State: " . $_SESSION['oauth2state']);
    error_log('Redirecting to GitHub...');

    header("Location: {$authorizationUrl}");
    exit;
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    error_log('Invalid state');
    exit('Invalid state');
} else {
    // Debug output
    error_log("Authorization code: " . htmlspecialchars($_GET['code']));
    error_log("OAuth2 State: " . htmlspecialchars($_SESSION['oauth2state']));

    try {
        $accessToken = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);

        // Debug output
        error_log("Access Token: " . $accessToken->getToken());

        $resourceOwner = $provider->getResourceOwner($accessToken);
        $user = $resourceOwner->toArray();

        // Debug output
        error_log("Resource Owner: " . print_r($user, true));

        $sql = $pdo->prepare('SELECT * FROM users WHERE username = ?;');
        $sql->execute([$user['login']]);
        if ($sql->rowCount() == 0) {
            $sql = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?);');
            $sql->execute([$user['login'], '']);
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['login'];

        header('Location: index.php');
        exit;
    } catch (Exception $e) {
        error_log('Failed to get access token: ' . $e->getMessage());
        exit('Failed to get access token: ' . $e->getMessage());
    }
}