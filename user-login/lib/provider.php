<?php

$productionEnv = require __DIR__ . '/../../env.php';

require __DIR__ . '/../../vendor/autoload.php';

use League\OAuth2\Client\Provider\Google;

// Replace these with your token settings
// Create a project at https://console.developers.google.com/
$clientId = '126828542038-93st8bcdheq25gp4ajj7afsjjr0cbc28.apps.googleusercontent.com';
$clientSecret = 'dVkf9HNY6ZYYk2n6h4apYwp1';

$serverName = $_GET[ 'HTTP_HOST' ];
$projectName = explode( '/', $_GET[ 'REQUEST_URI' ] )[ 1 ];
$redirectUri = 'https://' . $serverName;
if ( $productionEnv )
	$redirectUri .= '/' . $projectName;

$redirectUri .= '/login/callback/google';

// Start the session
session_start();

// Initialize the provider
$provider = new Google( [
	'clientId' => $clientId,
	'clientSecret' => $clientSecret,
	'redirectUri' => $redirectUri
] );

// No HTML for demo, prevents any attempt at XSS
header( 'Content-Type', 'text/plain' );

return $provider;
