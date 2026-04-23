@extends('layouts.admin')

@section('title', 'Payment Receipts')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Payment Receipts</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{ $invoices->total() }} receipts.</p>
                            </div>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->

                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner p-0">
                                <table class="table table-orders">
                                    <thead class="tb-odr-head">
                                        <tr class="tb-odr-item">
                                            <th class="tb-odr-info">
                                                <span class="tb-odr-id">Receipt Reference</span>
                                                <span class="tb-odr-date d-none d-md-inline-block">Payment Date</span>
                                            </th>
                                            <th class="tb-odr-amount">
                                                <span class="tb-odr-total">Amount Paid</span>
                                            </th>
                                            <th class="tb-odr-status">
                                                <span class="tb-odr-status">Billed To</span>
                                            </th>
                                            <th class="tb-odr-action">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tb-odr-body">
                                        @forelse($invoices as $invoice)
                                        <tr class="tb-odr-item">
                                            <td class="tb-odr-info">
                                                <span class="tb-odr-id text-success fw-bold">#{{ $invoice->payment_reference }}</span>
                                                <span class="tb-odr-date">{{ $invoice->paid_at ? \Carbon\Carbon::parse($invoice->paid_at)->format('d M Y') : $invoice->updated_at->format('d M Y') }}</span>
                                            </td>
                                            <td class="tb-odr-amount">
                                                <span class="tb-odr-total">
                                                    <span class="amount">₦{{ number_format($invoice->is_installment ? $invoice->total_paid : $invoice->amount, 2) }}</span>
                                                    @if($invoice->is_installment)
                                                        <div class="smaller text-soft">of ₦{{ number_format($invoice->amount, 2) }}</div>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="tb-odr-status">
                                                @if($invoice->is_installment)
                                                    <span class="badge badge-dot bg-info">Installment</span>
                                                @else
                                                    <span class="badge badge-dot bg-success">Full Payment</span>
                                                @endif
                                                <div class="small text-soft">{{ $invoice->prospect->name }}</div>
                                            </td>
                                            <td class="tb-odr-action">
                                                <div class="dropdown">
                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-xs">
                                                        <ul class="link-list-plain">
                                                            <li><a href="{{ route('admin.invoices.receipt', $invoice->id) }}">View Receipt</a></li>
                                                            <li><a href="{{ route('admin.invoices.receipt_pdf', $invoice->id) }}">Download PDF</a></li>
                                                            <li><a href="{{ route('admin.invoices.show', $invoice->id) }}">Original Invoice</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                No receipts found.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($invoices->hasPages())
                            <div class="card-inner">
                                {{ $invoices->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection
