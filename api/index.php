<?php
/**
 * Vercel compatible entry point for Laravel
 */

// Set the current working directory to the project root
chdir(dirname(__DIR__));

// Require Composer autoloader
require_once __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// Create a request from the server variables
$request = Illuminate\Http\Request::capture();

// Send the request to the Laravel application
$response = $app->handle($request);

// Send the response back to the client
$response->send();

// Finish the Laravel application lifecycle
$app->terminate($request, $response);