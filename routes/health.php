<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Health check route to test basic functionality
Route::get('/health', function () {
    try {
        // Test if we can access the basic configuration
        $config = [
            'database' => !empty(env('DB_HOST')),
            'app_key' => !empty(env('APP_KEY')),
        ];
        
        // Try a basic database connection check (without actually querying)
        try {
            DB::connection()->getPdo();
            $db_connected = true;
        } catch (\Exception $e) {
            $db_connected = false;
        }
        
        return response()->json([
            'status' => 'ok',
            'config' => $config,
            'database_connected' => $db_connected,
            'timestamp' => now()->toISOString()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'timestamp' => now()->toISOString()
        ], 500);
    }
});

// Simplified route to test if registration views load (without database interaction)
Route::get('/test-register', function () {
    try {
        // Return a simplified registration form without database dependencies
        return view('test-register');
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
});