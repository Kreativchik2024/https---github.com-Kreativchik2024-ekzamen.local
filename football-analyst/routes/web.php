<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return 'Laravel работает!';
});

Route::get('/test-sportmonks', function () {
    $apiToken = trim(env('SPORTMONKS_API_TOKEN'));

    $response = Http::withoutVerifying()
        ->get('https://api.sportmonks.com/v3/football/leagues/501', [
            'api_token' => $apiToken
        ]);

    if ($response->successful()) {
        return $response->json();
    }

    return response()->json([
        'status' => $response->status(),
        'body' => $response->body()
    ]);
});