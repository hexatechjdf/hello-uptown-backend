
<?php

use Illuminate\Support\Facades\Route;

Route::get('/businesses', [PublicBusinessController::class, 'index']);
Route::get('/businesses/{slug}', [PublicBusinessController::class, 'show']);

?>