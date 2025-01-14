<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/product', [ProductController::class, 'viewpage']);
Route::post('/addproducts', [ProductController::class, 'store']);
Route::get('/product', [ProductController::class, 'viewpage']);
Route::get('/getproductsdetails', [ProductController::class, 'getProductDetails']);
Route::get('/products/edit/{id}', [ProductController::class, 'productEdit']);
Route::post('/products/update', [ProductController::class, 'productUpdate']);
Route::delete('/products/delete/{id}', [ProductController::class, 'destroy']);
});









require __DIR__.'/auth.php';
