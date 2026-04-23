@extends('layouts.admin')

@section('title', 'Invoice #' . $invoice->invoice_number)

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Invoice <strong class="text-primary small">#{{ $invoice->invoice_number }}</strong></h3>
                            <div class="nk-block-des text-soft">
                                <ul class="list-inline">
                                    <li>Created At: <span class="text-base">{{ $invoice->created_at->format('d M, Y h:i A') }}</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                            <a href="{{ route('admin.invoices.index') }}" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="invoice">
                        <div class="invoice-action">
                            <!-- Print functionality can be mapped to window.print() if needed -> target="_blank" -->
                        </div><!-- .invoice-actions -->
                        <div class="invoice-wrap">
                            <div class="invoice-brand text-center">
                                <h2 class="text-primary fw-bolder" style="font-family: serif">Curated Archive</h2>
                            </div>
                            <div class="invoice-head">
                                <div class="invoice-contact">
                                    <span class="overline-title">Invoice To</span>
                                    <div class="invoice-contact-info">
                                        <h4 class="title">{{ $invoice->prospect->name ?? 'Unknown Author' }}</h4>
                                        <ul class="list-plain">
                                            <li><em class="icon ni ni-call-fill"></em><span>{{ $invoice->prospect->phone_number ?? 'N/A' }}</span></li>
                                            <li><em class="icon ni ni-mail-fill"></em><span>{{ $invoice->prospect->email ?? 'N/A' }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="invoice-desc">
                                    <h3 class="title">
                                        @if($invoice->status == 'paid')
                                            <span class="text-success">PAID INVOICE</span>
                                        @elseif($invoice->status == 'unpaid')
                                            <span class="text-warning">UNPAID INVOICE</span>
                                        @else
                                            <span class="text-danger">CANCELLED</span>
                                        @endif
                                    </h3>
                                    <ul class="list-plain">
                                        <li class="invoice-id"><span>Invoice ID</span>:<span>{{ $invoice->invoice_number }}</span></li>
                                        <li class="invoice-date"><span>Date</span>:<span>{{ $invoice->created_at->format('d F Y') }}</span></li>
                                        @if($invoice->status == 'paid')
                                        <li class="invoice-date"><span>Paid On</span>:<span>{{ $invoice->paid_at ? $invoice->paid_at->format('d F Y') : 'N/A' }}</span></li>
                                        <li class="invoice-date"><span>Status</span>:<span>{!! $invoice->is_installment ? '<span class="text-info fw-bold">INSTALLMENT</span>' : '<span class="text-success fw-bold">FULL PAYMENT</span>' !!}</span></li>
                                        @endif
                                    </ul>
                                </div>
                            </div><!-- .invoice-head -->
                            <div class="nk-block-head-content">
                            <ul class="nk-block-tools g-3">
                                <li><a href="{{ route('admin.invoices.pdf', $invoice->id) }}" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Download PDF</span></a></li>
                                @if($invoice->status == 'paid')
                                <li><a href="{{ route('admin.invoices.receipt', $invoice->id) }}" class="btn btn-success"><em class="icon ni ni-file-text"></em><span>View Receipt</span></a></li>
                                @endif
                                <li class="nk-block-tools-opt">
                                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-primary"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                                </li>
                            </ul>
                        </div>
                            <div class="invoice-bills">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="w-60">Description</th>
                                                <th>Price</th>
                                                <th>Qty</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Publishing Setup & Manuscript Processing
                                                    <span class="d-block text-soft small">Project: '{{ $invoice->prospect->book_title ?? 'Untitled' }}'</span>
                                                </td>
                                                <td>₦{{ number_format($invoice->amount, 2) }}</td>
                                                <td>1</td>
                                                <td>₦{{ number_format($invoice->amount, 2) }}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td>Subtotal</td>
                                                <td>₦{{ number_format($invoice->amount, 2) }}</td>
                                            </tr>
                                            @if($invoice->is_installment)
                                            <tr>
                                                <td colspan="2"></td>
                                                <td class="text-info">Total Paid (Installments)</td>
                                                <td class="text-info">₦{{ number_format($invoice->total_paid, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td class="text-danger">Balance Due</td>
                                                <td class="text-danger fw-bold">₦{{ number_format($invoice->amount - $invoice->total_paid, 2) }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td colspan="2"></td>
                                                <td>Grand Total</td>
                                                <td><strong>₦{{ number_format($invoice->amount, 2) }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="nk-notes ff-italic fs-12px text-soft">
                                        Invoice was created dynamically via The Curated Archive Dashboard and is valid without signature.
                                    </div>

                                    @if($invoice->status == 'unpaid')
                                    <div class="mt-5 pt-3 border-top">
                                        <h6 class="overline-title text-soft mb-3">Pay Securely Via:</h6>
                                        <div class="d-flex flex-wrap g-3">
                                            @php 
                                                $selectedGateways = $invoice->allowed_gateways ?? ['bank_transfer']; 
                                            @endphp
                                            
                                            @if(in_array('paystack', $selectedGateways))
                                            <a href="{{ route('payments.initiate', ['invoice' => $invoice->id, 'gateway' => 'paystack']) }}" class="btn btn-primary"><em class="icon ni ni-cc-alt"></em><span>Pay with Paystack</span></a>
                                            @endif
                                            
                                            @if(in_array('flutterwave', $selectedGateways))
                                            <a href="{{ route('payments.initiate', ['invoice' => $invoice->id, 'gateway' => 'flutterwave']) }}" class="btn btn-info"><em class="icon ni ni-cc-new"></em><span>Pay with Flutterwave</span></a>
                                        @endif
                                            
                                            @if(in_array('bank_transfer', $selectedGateways))
                                            <button type="button" class="btn btn-outline-light d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#bankTransferModal">
                                                <em class="icon ni ni-building"></em><span>Bank Transfer</span>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div><!-- .invoice-bills -->
                        </div><!-- .invoice-wrap -->
                    </div><!-- .invoice -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

{{-- Bank Transfer Modal --}}
@php
    $bankGateway = \App\Models\PaymentGateway::where('slug', 'bank_transfer')->first();
    $bankConfig = $bankGateway ? $bankGateway->config : [];
@endphp
<div class="modal fade" id="bankTransferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bank Transfer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Please make a transfer of <strong>₦{{ number_format($invoice->amount, 2) }}</strong> to the account below:</p>
                <div class="card card-bordered bg-lighter">
                    <div class="card-inner">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-soft">Bank Name:</span>
                            <span class="fw-bold">{{ $bankConfig['bank_name'] ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-soft">Account Number:</span>
                            <span class="fw-bold fs-16px text-primary">{{ $bankConfig['account_number'] ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-soft">Account Name:</span>
                            <span class="fw-bold">{{ $bankConfig['account_name'] ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-soft">Reference:</span>
                            <span class="fw-bold">{{ $invoice->invoice_number }}</span>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3">
                    <em class="icon ni ni-info-fill"></em>
                    <span>Please use the Invoice ID ({{ $invoice->invoice_number }}) as your transfer description to help us confirm your payment faster.</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I've Made the Transfer</button>
            </div>
        </div>
    </div>
</div>
@endsection
