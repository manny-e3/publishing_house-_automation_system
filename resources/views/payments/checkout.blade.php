@extends('layouts.app')

@section('content')
@php
    $currency = $invoice->currency;
    $currencySymbol = $invoice->currency_symbol;
    $preferredGateway = ($currency === 'USD') ? 'stripe' : 'paystack';

    // If currency is USD, display stripe alone
    if ($currency === 'USD') {
        $availableGateways = $gateways->where('slug', 'stripe');
        if ($availableGateways->isEmpty()) {
            $availableGateways = $gateways->where('slug', $preferredGateway);
        }
    } else {
        $availableGateways = $gateways->filter(function($gw) {
            return $gw->slug !== 'stripe';
        });
        if ($availableGateways->isEmpty()) {
            $availableGateways = $gateways;
        }
    }
@endphp
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0 overflow-hidden" style="border-radius: 1.5rem;">
                <!-- Header -->
                <div class="bg-dark p-4 text-center">
                    <div class="mb-2">
                        <img src="{{ asset('assets/landing/logo-white.svg') }}" alt="Logo" style="height: 40px;">
                    </div>
                    <h4 class="text-white mb-0">Invoice #{{ $invoice->invoice_number }}</h4>
                    <p class="text-secondary small mb-0">{{ $invoice->prospect->name }}</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <div class="mb-4 text-center">
                        <div class="text-soft overline-title mb-1">{{ $invoice->total_paid > 0 ? 'Remaining Balance' : 'Total Outstanding' }} ({{ $currency }})</div>
                        <h2 class="display-6 fw-bold">{{ $currencySymbol }}{{ number_format($invoice->amount - $invoice->total_paid, 2) }}</h2>
                        @if($invoice->total_paid > 0)
                            <div class="badge bg-navy-dim text-navy-950 mt-2">Paid to date: {{ $currencySymbol }}{{ number_format($invoice->total_paid, 2) }}</div>
                        @endif
                    </div>

                    <form action="{{ route('payments.initiate', ['invoice' => $invoice->id]) }}" method="GET" id="paymentForm">
                        <input type="hidden" name="amount" value="{{ $invoice->amount - $invoice->total_paid }}">

                        {{-- "Choose Amount to Pay" partial deposit block temporarily hidden as requested --}}
                        {{--
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold small text-uppercase text-soft">Choose Amount to Pay ({{ $currency }})</label>
                            
                            <div x-data="{ 
                                total: {{ $invoice->amount - $invoice->total_paid }}, 
                                minPercent: {{ $invoice->total_paid > 0 ? 0 : $invoice->min_deposit_percentage }},
                                amount: {{ $invoice->amount - $invoice->total_paid }},
                                get minAmount() { 
                                    if (this.minPercent == 0) return 1.00;
                                    return ({{ $invoice->amount }} * (this.minPercent / 100)).toFixed(2); 
                                }
                            }" class="space-y-3">
                                
                                <div class="form-control-wrap">
                                    <div class="form-text-hint">
                                        <span class="overline-title text-soft">{{ $currencySymbol }}</span>
                                    </div>
                                    <input type="number" name="amount" x-model="amount" 
                                           class="form-control form-control-xl" 
                                           :class="(amount < parseFloat(minAmount) || amount > total) ? 'border-danger text-danger' : ''"
                                           :min="minAmount" :max="total" step="0.01" required>
                                </div>
                                
                                <div x-show="amount < parseFloat(minAmount)" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     class="card card-bordered border-danger bg-danger-dim p-2 mb-3">
                                    <div class="d-flex align-items-center text-danger small fw-bold">
                                        <em class="icon ni ni-alert-circle me-1" style="font-size: 1.1rem;"></em>
                                        <span>Min. payment is {{ $currencySymbol }}<span x-text="parseFloat(minAmount).toLocaleString(undefined, {minimumFractionDigits: 2})"></span></span>
                                    </div>
                                </div>

                                <div x-show="amount > total" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     class="card card-bordered border-warning bg-warning-dim p-2 mb-3">
                                    <div class="d-flex align-items-center text-warning-dark small fw-bold">
                                        <em class="icon ni ni-alert-circle me-1" style="font-size: 1.1rem;"></em>
                                        <span>Amount exceeds total balance ({{ $currencySymbol }}<span x-text="parseFloat(total).toLocaleString(undefined, {minimumFractionDigits: 2})"></span>)</span>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between text-soft small px-1">
                                    <span x-show="minPercent > 0">Min: {{ $currencySymbol }}{{ number_format($invoice->amount * ($invoice->min_deposit_percentage / 100), 2) }} ({{ $invoice->min_deposit_percentage }}%)</span>
                                    <span x-show="minPercent == 0">Min: {{ $currencySymbol }}1.00</span>
                                </div>
                            </div>
                        </div>
                        --}}

                        <div class="form-group mb-4">
                            <label class="form-label fw-bold small text-uppercase text-soft mb-3">
                                {{ $availableGateways->count() === 1 ? 'Payment Gateway' : 'Select Payment Gateway' }}
                            </label>
                            <div class="row g-2">
                                @foreach($availableGateways as $gateway)
                                @php
                                    $isDefault = ($loop->first || $gateway->slug === $preferredGateway);
                                @endphp
                                <div class="{{ $availableGateways->count() === 1 ? 'col-12' : 'col-6' }}">
                                    <input type="radio" class="btn-check" name="gateway" id="gw_{{ $gateway->slug }}" value="{{ $gateway->slug }}" required {{ $isDefault ? 'checked' : '' }}>
                                    <label class="btn btn-outline-light w-100 py-3 text-dark border-2 text-center" for="gw_{{ $gateway->slug }}">
                                        <div class="fw-bold">{{ $gateway->name }}</div>
                                        @if($gateway->slug === 'stripe')
                                            <div class="text-soft small mt-1">Debit / Credit Card (USD)</div>
                                        @elseif($gateway->slug === 'paystack')
                                            <div class="text-soft small mt-1">Card / Bank / Transfer (NGN)</div>
                                        @endif
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-5">
                            <button type="submit" 
                                    class="btn btn-dark btn-lg w-100 py-3 fw-bold transition-all shadow-sm" 
                                    style="border-radius: 1rem;">
                                <span>Pay {{ $currencySymbol }}{{ number_format($invoice->amount - $invoice->total_paid, 2) }} Now</span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="card-footer bg-light p-4 text-center border-0">
                    <p class="text-soft small mb-0">Secure Encrypted Payment by {{ config('app.name') }}</p>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-soft small">← Back to homepage</a>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8fafc; }
    .form-control-xl { font-size: 1.5rem; font-weight: 700; text-align: center; height: auto; padding: 1rem; border-radius: 1rem; border-color: #e2e8f0; }
    .form-control-xl:focus { border-color: #1a202c; box-shadow: none; }
    .btn-check:checked + .btn-outline-light { background-color: #1a202c !important; color: white !important; border-color: #1a202c !important; }
    .overline-title { letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.75rem; }
    .text-soft { color: #64748b; }
</style>
@endsection
