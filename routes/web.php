<?php

use App\Http\Controllers\ReportController;

Route::get('/laporan', [ReportController::class, 'index'])->name('laporan');
