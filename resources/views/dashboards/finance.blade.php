@extends('layouts.admin')

@section('title', 'Finance Ledger & Settlements')

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Finance & Revenue Operations</h3>
                    <div class="nk-block-des text-soft">
                        <p>Multi-currency cash inflows (USD Stripe / NGN Paystack), bulk print cost reserves, and invoice reconciliation.</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-icon mb-4">
                <em class="icon ni ni-check-circle"></em> {{ session('success') }}
            </div>
        @endif

        <!-- Financial KPI Cards -->
        <div class="row g-gs mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-2">
                            <div class="card-title">
                                <h6 class="title">Paystack Collections (NGN)</h6>
                            </div>
                            <div class="card-tools">
                                <em class="card-hint icon ni ni-money text-success"></em>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            <div class="nk-sale-data">
                                <span class="amount text-success">₦{{ number_format($totalRevenueNGN, 2) }}</span>
                                <span class="sub-title">Domestic author fees</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-2">
                            <div class="card-title">
                                <h6 class="title">Stripe Collections (USD)</h6>
                            </div>
                            <div class="card-tools">
                                <em class="card-hint icon ni ni-globe text-primary"></em>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            <div class="nk-sale-data">
                                <span class="amount text-primary">${{ number_format($totalRevenueUSD, 2) }}</span>
                                <span class="sub-title">Diaspora & global packages</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-2">
                            <div class="card-title">
                                <h6 class="title">Bulk Print Estimates (Reserve)</h6>
                            </div>
                            <div class="card-tools">
                                <em class="card-hint icon ni ni-printer text-info"></em>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            <div class="nk-sale-data">
                                <span class="amount text-info">₦{{ number_format($bulkPrintEstimates, 2) }}</span>
                                <span class="sub-title">Allocated for press production</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-2">
                            <div class="card-title">
                                <h6 class="title">Invoices Settled / Unpaid</h6>
                            </div>
                            <div class="card-tools">
                                <em class="card-hint icon ni ni-file-docs text-secondary"></em>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            <div class="nk-sale-data">
                                <span class="amount">{{ $paidInvoicesCount }} <small class="text-muted">/ {{ $pendingInvoicesCount }} unpaid</small></span>
                                <span class="sub-title">Reconciled billing records</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="card card-bordered">
            <div class="card-inner">
                <div class="card-title-group mb-3">
                    <div class="card-title">
                        <h5 class="title">Publishing Invoices Ledger</h5>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Author / Project</th>
                                <th>Total Fee</th>
                                <th>Total Paid</th>
                                <th>Status</th>
                                <th>Payment Ref</th>
                                <th>Settled Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $inv)
                            <tr>
                                <td>
                                    <strong>{{ $inv->invoice_number }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $inv->prospect->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $inv->prospect->book_title ?? 'Manuscript' }}</small>
                                </td>
                                <td>₦{{ number_format($inv->amount, 2) }}</td>
                                <td>₦{{ number_format($inv->total_paid, 2) }}</td>
                                <td>
                                    <span class="badge {{ $inv->status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($inv->status) }}
                                    </span>
                                </td>
                                <td>
                                    <code class="small">{{ $inv->payment_reference ?? 'Unsettled' }}</code>
                                </td>
                                <td>
                                    {{ $inv->paid_at ? \Carbon\Carbon::parse($inv->paid_at)->format('M d, Y') : '&mdash;' }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.invoices.show', $inv->id) }}" class="btn btn-sm btn-outline-primary">
                                        <em class="icon ni ni-eye"></em> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No invoices currently recorded.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

