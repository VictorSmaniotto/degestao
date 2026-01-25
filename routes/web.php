<?php

use App\Http\Controllers\PersonProfileController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/people/{id}/print', [PersonProfileController::class, 'print'])->name('people.print');
});
