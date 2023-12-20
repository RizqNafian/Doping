<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[
    'uses' => 'DopingController@index',
]);

Route::post('/cari',[
    'uses' => 'DopingController@cari',
]);

Route::post('/detail',[
    'uses' => 'DopingController@detail',
]);

Route::get('/carix/{kategoris}',[
    'uses' => 'DopingController@carix',
]);

// Route::get('detail/{id}', function () {
//     return view('index');
// });

