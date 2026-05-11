<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ==========================================
// ROUTES D'AUTHENTIFICATION ADMIN
// ==========================================

// Page de login admin
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

// Traitement du login
Route::post('/admin/login', function (Request $request) {

    $validPassword = 'benabdellah00';
    
    if ($request->password == $validPassword) {
        session(['admin_logged_in' => true]);
        return redirect()->route('admin.dashboard');
    }
    
    return back()->with('error', 'Mot de passe incorrect');
})->name('admin.login.submit');

// Logout admin
Route::get('/admin/logout', function () {
    session()->forget('admin_logged_in');
    return redirect('/admin/login')->with('success', 'Déconnecté avec succès');
})->name('admin.logout');

// ==========================================
// PAGE D'ACCUEIL (HOME) AVEC SLIDER
// ==========================================
Route::get('/', function () {
    return view('home');
});

Route::get('/home', function () {
    return view('home');
});

// ==========================================
// PAGE MENU CLIENT (après clic sur "Voir le menu")
// ==========================================
Route::get('/menu-client', function () {
    $categories = DB::table('categories')->get();
    $dishes = DB::table('dishes')->get();
    
    return view('menu', compact('categories', 'dishes'));
})->name('menu.client');

// ==========================================
// PAGE MENU (route alternative - gardée pour compatibilité)
// ==========================================
Route::get('/menu', function () {
    $categories = DB::table('categories')->get();
    $dishes = DB::table('dishes')->get();
    
    return view('menu', compact('categories', 'dishes'));
});

// ==========================================
// ROUTES DU PANIER
// ==========================================
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

// ==========================================
// ROUTES DE COMMANDE
// ==========================================
Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');

// ==========================================
// ROUTES ADMINISTRATEUR (PROTÉGÉES)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    
    // Gestion des commandes
    Route::get('/orders', [App\Http\Controllers\AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [App\Http\Controllers\AdminController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{id}/status', [App\Http\Controllers\AdminController::class, 'updateOrderStatus'])->name('orders.status');
    
    // Gestion des plats
    Route::get('/dishes', [App\Http\Controllers\AdminController::class, 'dishes'])->name('dishes');
    Route::post('/dishes/add', [App\Http\Controllers\AdminController::class, 'addDish'])->name('dishes.add');
    Route::put('/dishes/{id}/edit', [App\Http\Controllers\AdminController::class, 'editDish'])->name('dishes.edit');
    Route::delete('/dishes/{id}/delete', [App\Http\Controllers\AdminController::class, 'deleteDish'])->name('dishes.delete');
});

// ==========================================
// ROUTES CUISINE (PROTÉGÉES)
// ==========================================
Route::prefix('kitchen')->name('kitchen.')->middleware('admin.auth')->group(function () {
    Route::get('/', [App\Http\Controllers\KitchenController::class, 'index'])->name('dashboard');
    Route::get('/order/{id}', [App\Http\Controllers\KitchenController::class, 'showOrder'])->name('order.show');
    Route::put('/order/{id}/status', [App\Http\Controllers\KitchenController::class, 'updateStatus'])->name('order.status');
    Route::get('/api/orders', [App\Http\Controllers\KitchenController::class, 'getOrdersApi'])->name('api.orders');
    Route::get('/api/order/{id}/details', [App\Http\Controllers\KitchenController::class, 'getOrderDetailsApi'])->name('api.order.details');
});
// Routes pour les avis
Route::get('/plat/{id}/avis', [App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
Route::post('/plat/{id}/avis', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
Route::get('/admin/reviews', [App\Http\Controllers\AdminController::class, 'reviews'])->name('admin.reviews');
Route::put('/admin/reviews/{id}/approve', [App\Http\Controllers\AdminController::class, 'approveReview'])->name('admin.reviews.approve');
Route::delete('/admin/reviews/{id}/delete', [App\Http\Controllers\AdminController::class, 'deleteReview'])->name('admin.reviews.delete');