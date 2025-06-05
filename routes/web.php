<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\FacebookController;
use Illuminate\Support\Facades\Cache;
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


Route::get('/reverse-geocode', function () {
    $lat = request('lat');
    $lon = request('lon');

    if (!$lat || !$lon) {
        return response()->json(['error' => 'Thiếu tham số'], 400);
    }

    $cacheKey = "reverse_geocode_{$lat}_{$lon}";
    return Cache::remember($cacheKey, now()->addHours(6), function () use ($lat, $lon) {
        return Http::withHeaders([
            'User-Agent' => 'MyLaravelApp/1.0 (nguyenhoangviet251103@gmail.com)',
        ])->get('https://nominatim.openstreetmap.org/reverse', [
                    'format' => 'json',
                    'lat' => $lat,
                    'lon' => $lon,
                    'zoom' => 18,
                    'addressdetails' => 1,
                ])->json();
    });
});

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('auth/facebook', [FacebookController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [FacebookController::class, 'handleFacebookCallback']);


// Public Routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/item', [ItemController::class, 'index'])->name('item.index');
Route::get('/item/{id}', [ItemController::class, 'detail'])->where('id', '[0-9]+')->name('item.detail');
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::get('/user/{id}', [UserController::class, 'profile'])->name('user.profile');

Route::view('/terms', 'support.terms')->name('terms');
Route::view('/faq', 'support.faq')->name('faq');
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
Route::view('/about', 'support.about')->name('about');
Route::view('/privacy-policy', 'support.privacy-policy')->name('privacy-policy');
Route::view('/help', 'support.help')->name('help');
// Mark messages as read
Route::post('/messages/mark-as-read/{senderId}', [MessagesController::class, 'markAsRead'])
    ->middleware('auth')
    ->name('messages.markAsRead');

// Get unread message count
Route::get('/messages/unread-count', [MessagesController::class, 'getUnreadCount'])
    ->middleware('auth')
    ->name('messages.unreadCount');
Auth::routes();

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // Item Management
    Route::get('/item/create', [ItemController::class, 'createForm'])->name('item.create');
    Route::post('/item/create', [ItemController::class, 'store'])->name('item.store');
    Route::get('/item/edit/{id}', [ItemController::class, 'edit'])->name('item.edit');
    Route::put('/item/{id}', [ItemController::class, 'update'])->name('item.update');
    Route::delete('/item/{id}', [ItemController::class, 'destroy'])->name('item.destroy');

    // Messages
    Route::get('/messages', [MessagesController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [MessagesController::class, 'show'])->name('messages.show');
    Route::post('/messages/send', [MessagesController::class, 'send'])->name('messages.send');

    // User Account Management
    Route::get('/account-setting', [UserController::class, 'update'])->name('user.setting');
    Route::put('/account/profile', [UserController::class, 'updateProfile'])->name('user.update.profile');
    Route::put('/account/password', [UserController::class, 'updatePassword'])->name('user.update.password');
    Route::delete('/account', [UserController::class, 'delete'])->name('user.delete');
    Route::delete('/account/profile-image', [UserController::class, 'removeProfileImage'])->name('user.remove.profile.image');
    Route::post('/user/update-activity', [UserController::class, 'updateActivity'])->name('user.update-activity');

    // Transactions
    Route::get('/transactions/export/excel', [TransactionController::class, 'exportExcel'])->name('transactions.export.excel');
    Route::get('/transactions/export/csv', [TransactionController::class, 'exportCsv'])->name('transactions.export.csv');
    Route::get('/transactions/export/pdf', [TransactionController::class, 'exportPdf'])->name('transactions.export.pdf');
    Route::post('/item/request/{id}', [TransactionController::class, 'store'])->name('item.request');
    Route::delete('/item/request/{id}', [TransactionController::class, 'destroy'])->name('item.cancel');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::get('/statistics', [TransactionController::class, 'statistics'])->name('statistics');
    Route::get('/transactions/unread-count', [TransactionController::class, 'getUnreadCount']);
    Route::post('/transactions/mark-as-read', [TransactionController::class, 'markAsRead']);
});

