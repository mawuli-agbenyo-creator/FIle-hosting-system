<?php

use App\Http\Controllers\API\link;
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

Route::get('/', [link::class, 'index']);

Route::get('/download/{code}', [link::class, 'download_display']);


Route::post('/download', [link::class, 'download'])->name('download');
