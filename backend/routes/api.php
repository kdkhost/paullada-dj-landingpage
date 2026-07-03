<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

Route::get('/shows', [ApiController::class, 'shows']);
Route::get('/gallery', [ApiController::class, 'gallery']);
Route::get('/social-links', [ApiController::class, 'socialLinks']);
Route::get('/settings', [ApiController::class, 'settings']);
Route::post('/track-event', [ApiController::class, 'trackEvent']);
