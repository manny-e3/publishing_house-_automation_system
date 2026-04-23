@extends('layouts.admin')

@section('title', 'Transaction Audit Log')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Transaction History</h3>
                            <div class="nk-block-des text-soft">
                                <p>A unified ledger of all payment attempts across all gateways.</p>
                            </div>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->

                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner p-0">
                                <table class="table table-tranx">
                                    <thead>
                                        <tr class="tb-tnx-head">
                                            <th class="tb-tnx-id"><span class="">ID</span></th>
                                            <th class="tb-tnx-info">
                                                <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                    <span>Details</span>
                                                </span>
                                                <span class="tb-tnx-date d-md-inline-block d-none">
                                                    <span class="d-md-none">Date</span>
                                                    <span class="d-none d-md-block">
                                                        <span>Date</span>
                                                        <span>Gateway</span>
                                                    </span>
                                                </span>
                                            </th>
                                            <th class="tb-tnx-amount">
                                                <span class="tb-tnx-total">Amount</span>
                                                <span class="tb-tnx-status d-none d-md-inline-block">Status</span>
                                            </th>
                                            <th class="tb-tnx-action">
                                                <span>&nbsp;</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $tx)
                                        <tr class="tb-tnx-item">
                                            <td class="tb-tnx-id">
                                                <a href="#"><span>{{ substr($tx->transaction_reference, 0, 10) }}...</span></a>
                                            </td>
                                            <td class="tb-tnx-info">
                                                <div class="tb-tnx-desc">
                                                    <span class="title">Invoice #{{ $tx->invoice->invoice_number ?? 'N/A' }}</span>
                                                    <span class="text-soft small">{{ $tx->invoice->prospect->name ?? 'Author N/A' }}</span>
                                                </div>
                                                <div class="tb-tnx-date">
                                                    <span class="date">{{ $tx->created_at->format('d/m/Y') }}</span>
                                                    <span class="badge badge-dim bg-outline-light text-soft small text-uppercase">{{ $tx->gateway_slug }}</span>
                                                </div>
                                            </td>
                                            <td class="tb-tnx-amount">
                                                <div class="tb-tnx-total">
                                                    <span class="amount">₦{{ number_format($tx->amount, 2) }}</span>
                                                </div>
                                                <div class="tb-tnx-status">
                                                    @if($tx->status == 'successful')
                                                        <span class="badge badge-dot bg-success">Success</span>
                                                    @elseif($tx->status == 'pending')
                                                        <span class="badge badge-dot bg-warning">Pending</span>
                                                    @else
                                                        <span class="badge badge-dot bg-danger">Failed</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="tb-tnx-action">
                                                <div class="dropdown">
                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-xs">
                                                        <ul class="link-list-plain">
                                                            @if($tx->invoice_id)
                                                                <li><a href="{{ route('admin.invoices.show', $tx->invoice_id) }}">View Invoice</a></li>
                                                            @endif
                                                            @if($tx->external_reference)
                                                                <li><span class="dropdown-header">Ext Ref: {{ $tx->external_reference }}</span></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center p-5 text-soft">No transactions found yet.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($transactions->hasPages())
                            <div class="card-inner">
                                {{ $transactions->links() }}
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
