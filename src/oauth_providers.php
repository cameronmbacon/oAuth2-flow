<?php

$config = require '../config.php';

use League\OAuth2\Client\Provider\Github;
use League\OAuth2\Client\Provider\Google;

$githubProvider = new Github([
    'clientId' => $config['github_client_id'],
    'clientSecret' => $config['github_client_secret'],
    'redirectUri' => $config['github_redirect_uri'],
]);

$googleProvider = new Google([
    'clientId' => $config['google_client_id'],
    'clientSecret' => $config['google_client_secret'],
    'redirectUri' => $config['google_redirect_uri'],
]);