
<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:superadmin'])->group(function () {
    Route::get('/businesses', [BusinessController::class, 'index']);
    Route::post('/businesses', [BusinessController::class, 'store']);
    Route::post('/business-admin', [BusinessAdminController::class, 'store']);
});
?>