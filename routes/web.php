<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorDashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Dashboard\ItDashboardController;
use App\Http\Controllers\Dashboard\FinanceDashboardController;
use App\Http\Controllers\Dashboard\AcquisitionDashboardController;
use App\Http\Controllers\Dashboard\EditorialDashboardController;
use App\Http\Controllers\Dashboard\DesignDashboardController;
use App\Http\Controllers\Dashboard\MarketingDashboardController;
use App\Http\Controllers\Dashboard\DistributionDashboardController;
use App\Http\Controllers\Dashboard\AuthorPortalController;

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
Route::post('/enquiry', [EnquiryController::class, 'store'])->name('enquiry.store');
Route::get('/enquiry/confirmed/{prospect}', [EnquiryController::class, 'confirmed'])->name('enquiry.confirmed');
Route::post('/enquiry/calculate', [EnquiryController::class, 'calculateQuote'])->name('enquiry.calculate');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::post('/', [EnquiryController::class, 'store']);

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/prospects', [AdminController::class, 'prospects'])->name('prospects.index');
    Route::get('/prospects/{prospect}', [AdminController::class, 'show'])->name('prospects.show');
    Route::patch('/prospects/{prospect}/status', [AdminController::class, 'updateStatus'])->name('prospects.status');
    Route::post('/prospects/{prospect}/estimate', [AdminController::class, 'updateEstimate'])->name('prospects.update_estimate');

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

    Route::get('/contracts', [\App\Http\Controllers\Admin\ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/{contract}/download', [\App\Http\Controllers\Admin\ContractController::class, 'download'])->name('contracts.download');

    // Audit & Activity Logs
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit_logs.index');
    Route::get('/audit-logs/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'export'])->name('audit_logs.export');
    Route::get('/audit-logs/{auditLog}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show'])->name('audit_logs.show');

    // Access Control & User Activity
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class)->except(['show', 'create']);

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/pricing', [SettingsController::class, 'pricingIndex'])->name('pricing');
        // InvoiceController already has gatewaySettings, I'll keep it there for now or migrate later.
        Route::post('/pricing', [SettingsController::class, 'pricingUpdate'])->name('pricing.update');
        
        Route::get('/criteria', [SettingsController::class, 'criteriaIndex'])->name('criteria');
        Route::post('/criteria', [SettingsController::class, 'criteriaStore'])->name('criteria.store');
        Route::patch('/criteria/{criterion}', [SettingsController::class, 'criteriaUpdate'])->name('criteria.update');
        Route::delete('/criteria/{criterion}', [SettingsController::class, 'criteriaDelete'])->name('criteria.delete');

        Route::get('/global', [SettingsController::class, 'globalIndex'])->name('global');
        Route::post('/global', [SettingsController::class, 'globalUpdate'])->name('global.update');

        Route::get('/templates', [SettingsController::class, 'templatesIndex'])->name('templates');
        Route::post('/templates/{template}', [SettingsController::class, 'templatesUpdate'])->name('templates.update');
    });
});

// Payment Flow (Publicly accessible for authors)
Route::get('/payments/{invoice}/checkout', [PaymentController::class, 'showCheckout'])->name('payments.checkout');
Route::get('/payments/{invoice}/initiate', [PaymentController::class, 'initiate'])->name('payments.initiate');
Route::get('/payments/success', function () {
    return view('payments.success');
})->name('payments.success');
// Payments (Callback/Webhook)
Route::get('/payments/callback/{gateway}', [PaymentController::class, 'callback'])->name('payments.callback');

// Author Dashboard
Route::group(['prefix' => 'author', 'as' => 'author.', 'middleware' => ['auth']], function () {
    Route::get('/dashboard', [AuthorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/enquiries', [AuthorDashboardController::class, 'enquiries'])->name('enquiries.index');
    Route::get('/enquiries/create', [AuthorDashboardController::class, 'createEnquiry'])->name('enquiries.create');
    Route::get('/enquiries/{prospect}', [AuthorDashboardController::class, 'showEnquiry'])->name('enquiries.show');
    
    Route::get('/invoices', [AuthorDashboardController::class, 'invoices'])->name('invoices');
    Route::get('/transactions', [AuthorDashboardController::class, 'transactions'])->name('transactions');

    // Contracts
    Route::get('/contracts/{contract}', [\App\Http\Controllers\ContractController::class, 'show'])->name('contracts.show');
    Route::post('/contracts/{contract}/sign', [\App\Http\Controllers\ContractController::class, 'sign'])->name('contracts.sign');
});

// =========================================================================
// Phase 4: Back-Office Team Dashboards & Strict Business Gates (Prompts 3, 7, 8, 9)
// Protected by Auth & RoleDashboardGuard Middleware
// =========================================================================
Route::middleware(['auth', \App\Http\Middleware\RoleDashboardGuard::class])->group(function () {
    // IT Dashboard
    Route::prefix('dashboard/it')->name('dashboard.it.')->group(function () {
        Route::get('/', [ItDashboardController::class, 'index'])->name('index');
        Route::post('/flags/{flag}/toggle', [ItDashboardController::class, 'toggleFlag'])->name('flags.toggle');
        Route::post('/ghost-files/flush', [ItDashboardController::class, 'flushGhostFiles'])->name('ghost.flush');
    });

    // Finance Dashboard
    Route::prefix('dashboard/finance')->name('dashboard.finance.')->group(function () {
        Route::get('/', [FinanceDashboardController::class, 'index'])->name('index');
    });

    // Acquisition Dashboard
    Route::prefix('dashboard/acquisition')->name('dashboard.acquisition.')->group(function () {
        Route::get('/', [AcquisitionDashboardController::class, 'index'])->name('index');
        Route::get('/{prospect}', [AcquisitionDashboardController::class, 'show'])->name('show');
        Route::post('/{prospect}/toggle-task', [AcquisitionDashboardController::class, 'toggleTask'])->name('task.toggle');
        Route::post('/{prospect}/assign-isbn', [AcquisitionDashboardController::class, 'assignIsbn'])->name('isbn.assign');
    });

    // Editorial Dashboard
    Route::prefix('dashboard/editorial')->name('dashboard.editorial.')->group(function () {
        Route::get('/', [EditorialDashboardController::class, 'index'])->name('index');
        Route::get('/{prospect}', [EditorialDashboardController::class, 'show'])->name('show');
        Route::post('/{prospect}/toggle-task', [EditorialDashboardController::class, 'toggleTask'])->name('task.toggle');
        Route::post('/{prospect}/rate', [EditorialDashboardController::class, 'updateRating'])->name('rate');
    });

    // Design Dashboard
    Route::prefix('dashboard/design')->name('dashboard.design.')->group(function () {
        Route::get('/', [DesignDashboardController::class, 'index'])->name('index');
        Route::get('/{prospect}', [DesignDashboardController::class, 'show'])->name('show');
        Route::post('/{prospect}/upload-asset', [DesignDashboardController::class, 'uploadAsset'])->name('asset.upload');
        Route::post('/{prospect}/phase', [DesignDashboardController::class, 'updatePhase'])->name('phase.update');
        Route::post('/{prospect}/annotate', [DesignDashboardController::class, 'addAnnotation'])->name('annotate');
        Route::post('/{prospect}/acknowledge-regression', [DesignDashboardController::class, 'acknowledgeRegression'])->name('regression.acknowledge');
    });

    // Marketing Dashboard
    Route::prefix('dashboard/marketing')->name('dashboard.marketing.')->group(function () {
        Route::get('/', [MarketingDashboardController::class, 'index'])->name('index');
        Route::post('/{prospect}/toggle-task', [MarketingDashboardController::class, 'toggleCampaignTask'])->name('task.toggle');
    });

    // Print & Distribution Dashboard
    Route::prefix('dashboard/distribution')->name('dashboard.distribution.')->group(function () {
        Route::get('/', [DistributionDashboardController::class, 'index'])->name('index');
        Route::post('/{prospect}/channel', [DistributionDashboardController::class, 'updateChannel'])->name('channel.update');
        Route::post('/{prospect}/inventory', [DistributionDashboardController::class, 'addInventory'])->name('inventory.add');
        Route::post('/{prospect}/validate-release', [DistributionDashboardController::class, 'releaseValidation'])->name('release.validate');
    });

    // Author Portal
    Route::prefix('portal/author')->name('portal.author.')->group(function () {
        Route::get('/', [AuthorPortalController::class, 'index'])->name('index');
        Route::get('/{prospect}', [AuthorPortalController::class, 'show'])->name('show');
        Route::post('/{prospect}/feedback', [AuthorPortalController::class, 'postFeedback'])->name('feedback');
    });
});


