#!/usr/bin/env php
<?php
/**
 * Script to extract environment variables from .env file for Vercel deployment
 */

$envFile = __DIR__ . '/.env';
if (!file_exists($envFile)) {
    echo "Error: .env file not found in the project root.\n";
    exit(1);
}

$envContent = file_get_contents($envFile);
$lines = explode("\n", $envContent);

echo "Environment variables for Vercel deployment:\n\n";

$importantVars = [
    'APP_KEY',
    'APP_URL',
    'DB_CONNECTION',
    'DB_HOST',
    'DB_PORT',
    'DB_DATABASE',
    'DB_USERNAME',
    'DB_PASSWORD',
    'SESSION_DRIVER',
    'CACHE_DRIVER',
    'QUEUE_CONNECTION'
];

$sessionDriver = 'database'; // default
$cacheDriver = 'file'; // default

$outputtedVars = [];

foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line) || strpos($line, '#') === 0) {
        continue;
    }
    
    foreach ($importantVars as $var) {
        if (preg_match('/^' . preg_quote($var) . '=(.*)/', $line, $matches)) {
            // Remove quotes if present
            $value = trim($matches[1], '"\'');
            
            // Only output each variable once
            if (!in_array($var, $outputtedVars)) {
                echo "$var=$value\n";
                $outputtedVars[] = $var;
            }

            // Store special values for recommendations (always taking the last value in the file)
            if ($var === 'SESSION_DRIVER') {
                $sessionDriver = $value;
            } elseif ($var === 'CACHE_DRIVER') {
                $cacheDriver = $value;
            }

            break;
        }
    }
}

echo "\nRecommended variables for Vercel (consider these changes):\n";
echo "SESSION_DRIVER=cookie    # Better for serverless (was: $sessionDriver)\n";
echo "CACHE_DRIVER=array       # Better for serverless (was: $cacheDriver)\n";

echo "\nNote: Add these to your Vercel project settings under Environment Variables.\n";
echo "Set APP_URL to your Vercel deployment URL (e.g., https://your-app.vercel.app)\n";