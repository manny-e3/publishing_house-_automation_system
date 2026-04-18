<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SettingsController;


Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    return "Cache cleared successfully!";
});


use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/verify-otp', [AuthController::class, 'showOTP'])->name('otp.show');
Route::post('/verify-otp', [AuthController::class, 'verifyOTP'])->name('otp.verify');
Route::post('/resend-otp', [AuthController::class, 'resendOTP'])->name('otp.resend');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/enquiry', [EnquiryController::class, 'enquiry'])->name('enquiry.index');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::post('/', [EnquiryController::class, 'store'])->name('enquiry.store');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/prospects', [AdminController::class, 'prospects'])->name('prospects.index');
    Route::get('/prospects/{prospect}', [AdminController::class, 'show'])->name('prospects.show');
    Route::patch('/prospects/{prospect}/status', [AdminController::class, 'updateStatus'])->name('prospects.status');

    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/receipts', [InvoiceController::class, 'receiptsIndex'])->name('receipts.index');
    Route::get('/transactions', [InvoiceController::class, 'transactionsIndex'])->name('transactions.index');
    Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPDF'])->name('invoices.pdf');
    Route::get('/invoices/{invoice}/receipt', [InvoiceController::class, 'showReceipt'])->name('invoices.receipt');
    Route::get('/invoices/{invoice}/receipt/pdf', [InvoiceController::class, 'downloadReceipt'])->name('invoices.receipt_pdf');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::post('/invoices/{invoice}/confirm', [InvoiceController::class, 'confirmPayment'])->name('invoices.confirm');

    Route::get('/settings/gateways', [InvoiceController::class, 'gatewaySettings'])->name('settings.gateways');
    Route::post('/settings/gateways/{gateway}', [InvoiceController::class, 'updateGateway'])->name('settings.gateways.update');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::patch('/projects/{project}/stage', [ProjectController::class, 'updateStage'])->name('projects.update_stage');

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/pricing', [SettingsController::class, 'pricingIndex'])->name('pricing');
        // InvoiceController already has gatewaySettings, I'll keep it there for now or migrate later.
        Route::post('/pricing', [SettingsController::class, 'pricingUpdate'])->name('pricing.update');
        
        Route::get('/criteria', [SettingsController::class, 'criteriaIndex'])->name('criteria');
        Route::post('/criteria', [SettingsController::class, 'criteriaStore'])->name('criteria.store');
        Route::delete('/criteria/{criterion}', [SettingsController::class, 'criteriaDelete'])->name('criteria.delete');

        Route::get('/global', [SettingsController::class, 'globalIndex'])->name('global');
        Route::post('/global', [SettingsController::class, 'globalUpdate'])->name('global.update');

        Route::get('/templates', [SettingsController::class, 'templatesIndex'])->name('templates');
        Route::post('/templates/{template}', [SettingsController::class, 'templatesUpdate'])->name('templates.update');
    });
});

// Payment Flow (Publicly accessible for authors)
Route::get('/payments/{invoice}/pay/{gateway}', [PaymentController::class, 'initiate'])->name('payments.initiate');
Route::get('/payments/callback/{gateway}', [PaymentController::class, 'callback'])->name('payments.callback');

