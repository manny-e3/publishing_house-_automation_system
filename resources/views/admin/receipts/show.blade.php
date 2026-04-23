@extends('layouts.admin')

@section('title', 'Receipt - ' . $invoice->payment_reference)

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Payment Receipt</h3>
                            <div class="nk-block-des text-soft">
                                <p>Reference: <strong class="text-primary">{{ $invoice->payment_reference }}</strong></p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <ul class="nk-block-tools g-3">
                                <li><a href="{{ route('admin.invoices.receipt_pdf', $invoice->id) }}" class="btn btn-primary"><em class="icon ni ni-download-cloud"></em><span>Download PDF</span></a></li>
                                <li><a href="{{ route('admin.invoices.show', $invoice->id) }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-file-text"></em><span>View Invoice</span></a></li>
                            </ul>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->

                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <div class="nk-receipt">
                                <div class="nk-receipt-head mb-5 text-center">
                                    @php 
                                        $isPartial = $invoice->total_paid > 0 && $invoice->total_paid < $invoice->amount;
                                    @endphp
                                    <h4 class="title {{ $isPartial ? 'text-info' : 'text-success' }}">
                                        {{ $isPartial ? 'Installment Received' : 'Payment Received' }}
                                    </h4>
                                    <p class="text-soft">Thank you for your payment. Your project has been activated.</p>
                                </div>
                                <div class="row g-gs">
                                    <div class="col-md-6">
                                        <div class="card card-bordered bg-lighter">
                                            <div class="card-inner">
                                                <h6 class="overline-title text-soft mb-2">Payer Details</h6>
                                                <h5 class="title">{{ $invoice->prospect->name }}</h5>
                                                <p class="text-soft small">{{ $invoice->prospect->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card card-bordered bg-lighter">
                                            <div class="card-inner">
                                                <h6 class="overline-title text-soft mb-2">Transaction Info</h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="sub-text">Date:</span>
                                                    <span class="fw-bold">{{ $invoice->paid_at ? \Carbon\Carbon::parse($invoice->paid_at)->format('d M, Y') : 'N/A' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span class="sub-text">Ref:</span>
                                                    <span class="fw-bold text-primary">{{ $invoice->payment_reference }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="nk-receipt-details mt-5">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Description</th>
                                                <th class="text-end">Amount Paid</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <span class="fw-bold">Publishing Service Fee</span><br>
                                                    <span class="text-soft small">Book Title: {{ $invoice->prospect->book_title }}</span>
                                                    @if($invoice->is_installment)
                                                        <div class="badge badge-dim bg-info mt-2">Installment Payment</div>
                                                    @endif
                                                </td>
                                                <td class="text-end fw-bold text-dark">
                                                    ₦{{ number_format($invoice->is_installment ? $invoice->total_paid : $invoice->amount, 2) }}
                                                </td>
                                            </tr>
                                            @if($invoice->is_installment)
                                            <tr class="bg-lighter">
                                                <td class="text-end text-soft small">Total Invoice Amount</td>
                                                <td class="text-end text-soft small">₦{{ number_format($invoice->amount, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="text-end fw-bold">Balance Outstanding</td>
                                                <td class="text-end fw-bold text-danger">₦{{ number_format($invoice->amount - $invoice->total_paid, 2) }}</td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <div class="nk-receipt-footer mt-5 pt-3 border-top text-center text-soft small">
                                    <p>The Curated Archive &copy; {{ date('Y') }}. This is a computer generated receipt.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection
