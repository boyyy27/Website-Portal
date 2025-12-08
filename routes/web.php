<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TmsController;

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

// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::get('/verification', [App\Http\Controllers\VerificationController::class, 'show'])->name('verification.show');
Route::post('/verification', [App\Http\Controllers\VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/verification/resend', [App\Http\Controllers\VerificationController::class, 'resend'])->name('verification.resend');

// Protected Routes (require authentication)
Route::middleware('auth')->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');
    
    // Transaction Routes
    Route::get('/admin/transactions', [DashboardController::class, 'allTransactions'])->name('admin.transactions');
    Route::get('/transaction/{id}', [DashboardController::class, 'showTransaction'])->name('admin.transaction.detail');
    Route::get('/invoice/{id}', [DashboardController::class, 'downloadInvoice'])->name('user.invoice.download');
    Route::get('/admin/transaction/{id}/invoice', [DashboardController::class, 'downloadInvoice'])->name('admin.transaction.invoice');
    
    // Package Management Routes (Admin Only)
    Route::prefix('admin/packages')->name('admin.packages.')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('index');
        Route::get('/create', [PackageController::class, 'create'])->name('create');
        Route::post('/', [PackageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PackageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PackageController::class, 'update'])->name('update');
        Route::delete('/{id}', [PackageController::class, 'destroy'])->name('destroy');
    });
    
    // Payment Routes
    Route::get('/checkout/{packageId}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/create/{packageId}', [PaymentController::class, 'createTransaction'])->name('payment.create');
    Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
    Route::get('/payment/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
    Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
    
    // TMS Access Routes
    Route::get('/tms/access', [TmsController::class, 'redirectToTms'])->name('tms.access');
});

// Midtrans Notification (no auth required, but should be secured)
Route::post('/payment/notification', [PaymentController::class, 'handleNotification'])->name('payment.notification');

// Manual status check (for testing/debugging)
Route::middleware('auth')->group(function () {
    Route::get('/payment/check-status/{orderId}', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
});
