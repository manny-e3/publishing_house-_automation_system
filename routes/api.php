<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\LeadRecoveryController;
use Illuminate\Support\Facades\Route;

// Payment Webhook Handlers (Prompt 5)
Route::post('/webhooks/payment', [PaymentController::class, 'handleUnifiedWebhook'])->name('api.webhooks.payment');
Route::post('/webhooks/{gateway}', [PaymentController::class, 'webhook'])->name('payments.webhook');

// Abandoned Cart Recovery Scheduler (Prompt 6)
Route::post('/cron/recovery-scheduler', [LeadRecoveryController::class, 'runScheduler'])->name('api.cron.recovery');

// Dynamic Triage Quote Calculation
Route::post('/triage/calculate', [EnquiryController::class, 'calculateQuote'])->name('api.triage.calculate');
