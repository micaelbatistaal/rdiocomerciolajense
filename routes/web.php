<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/', function () {
    return view('radio', [
        'stream' => env('RADIO_STREAM_URL', 'https://stm10.conectastreaming.com:6890/stream'),
        'logo'   => asset('images/logo.png'), // coloque sua logo em public/images/logo.png
    ]);
});
