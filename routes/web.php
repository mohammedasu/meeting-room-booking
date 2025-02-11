<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // Ensure you have an `app.blade.php` file that loads Vue
})->where('any', '.*');
