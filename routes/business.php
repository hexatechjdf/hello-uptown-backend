<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:business_admin'])->group(function () {
    Route::get('/profile', [BusinessProfileController::class, 'show']);
    Route::put('/profile', [BusinessProfileController::class, 'update']);
});

?>