<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource('pets', PetController::class)->except([
    'show'
]);
