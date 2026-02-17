<?php

use App\Http\Controllers\Admin\ImageMonitoringController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/image-monitoring', [ImageMonitoringController::class, 'dashboard'])
        ->name('admin.image-monitoring');
});
