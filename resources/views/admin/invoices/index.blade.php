@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Invoices</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{ $invoices->count() }} invoices.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools justify-between g-3">
                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-upload-cloud"></em><span>Import</span></a></li>
                                        <!-- We removed 'Add New' since it triggers organically via Prospect Accepted status -->
                                    </ul>
                                </div>
                            </div><!-- .toggle-wrap -->
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title">
                                        <h5 class="title">All Invoices</h5>
                                    </div>
                                    <div class="card-tools me-n1">
                                        <ul class="btn-toolbar">
                                            <li>
                                                <a href="#" class="btn btn-icon search-toggle toggle-search" data-target="search"><em class="icon ni ni-search"></em></a>
                                            </li><!-- li -->
                                            <li class="btn-toolbar-sep"></li><!-- li -->
                                            <li>
                                                <div class="dropdown">
                                                    <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                                                        <em class="icon ni ni-setting"></em>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-xs">
                                                        <ul class="link-check">
                                                            <li><span>Show</span></li>
                                                            <li class="active"><a href="#">10</a></li>
                                                            <li><a href="#">20</a></li>
                                                            <li><a href="#">50</a></li>
                                                        </ul>
                                                    </div>
                                                </div><!-- .dropdown -->
                                            </li><!-- li -->
                                        </ul><!-- .btn-toolbar -->
                                    </div><!-- card-tools -->
                                    <div class="card-search search-wrap" data-search="search">
                                        <div class="search-content">
                                            <a href="#" class="search-back btn btn-icon toggle-search" data-target="search"><em class="icon ni ni-arrow-left"></em></a>
                                            <input type="text" class="form-control form-control-sm border-transparent form-focus-none" placeholder="Quick search by invoice id">
                                            <button class="search-submit btn btn-icon"><em class="icon ni ni-search"></em></button>
                                        </div>
                                    </div><!-- card-search -->
                                </div><!-- .card-title-group -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-0">
                                <table class="table table-orders">
                                    <thead class="tb-odr-head">
                                        <tr class="tb-odr-item">
                                            <th class="tb-odr-info">
                                                <span class="tb-odr-id">Invoice ID</span>
                                                <span class="tb-odr-date d-none d-md-inline-block">Date</span>
                                            </th>
                                            <th class="tb-odr-amount">
                                                <span class="tb-odr-total">Amount</span>
                                                <span class="tb-odr-status d-none d-md-inline-block">Status</span>
                                            </th>
                                            <th class="tb-odr-action">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tb-odr-body">
                                        @forelse($invoices as $invoice)
                                        <tr class="tb-odr-item">
                                            <td class="tb-odr-info">
                                                <span class="tb-odr-id"><a href="{{ route('admin.invoices.show', $invoice->id) }}">{{ $invoice->invoice_number }}</a></span>
                                                <span class="tb-odr-date">{{ $invoice->created_at->format('d M Y, h:ia') }}</span>
                                                <span class="d-block text-soft small mt-1">Billed to: {{ $invoice->prospect->name ?? 'Unknown' }}</span>
                                            </td>
                                            <td class="tb-odr-amount">
                                                <span class="tb-odr-total">
                                                    <span class="amount">₦{{ number_format($invoice->amount, 2) }}</span>
                                                </span>
                                                <span class="tb-odr-status">
                                                    @if($invoice->status == 'paid')
                                                        @php 
                                                            $isPartial = $invoice->total_paid > 0 && $invoice->total_paid < $invoice->amount;
                                                        @endphp
                                                        @if($isPartial)
                                                            <span class="badge badge-dot bg-info">Partially Paid</span>
                                                            <div class="text-soft smaller">Paid: ₦{{ number_format($invoice->total_paid, 2) }} / ₦{{ number_format($invoice->amount, 2) }}</div>
                                                        @else
                                                            <span class="badge badge-dot bg-success">Paid</span>
                                                        @endif
                                                    @elseif($invoice->status == 'unpaid')
                                                        <span class="badge badge-dot bg-warning">Unpaid</span>
                                                    @else
                                                        <span class="badge badge-dot bg-danger">Cancelled</span>
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="tb-odr-action">
                                                <div class="tb-odr-btns d-none d-sm-inline">
                                                    @if($invoice->status == 'unpaid')
                                                    <form method="POST" action="{{ route('admin.invoices.confirm', $invoice->id) }}" onsubmit="return confirm('Confirm payment received? This will instantly activate the book as a Live Project!');" class="d-inline me-1">
                                                        @csrf
                                                        <button type="submit" class="btn btn-icon btn-white btn-dim btn-sm btn-success" title="Confirm Manual Payment"><em class="icon ni ni-check-circle-fill"></em></button>
                                                    </form>
                                                    @endif
                                                    <a href="#" class="btn btn-icon btn-white btn-dim btn-sm btn-primary" title="Print Invoice"><em class="icon ni ni-printer-fill"></em></a>
                                                </div>
                                                <a href="#" class="btn btn-pd-auto d-sm-none"><em class="icon ni ni-chevron-right"></em></a>
                                            </td>
                                        </tr><!-- .tb-odr-item -->
                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                No invoices generated yet.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div><!-- .card-inner -->
                            @if($invoices->hasPages() ?? false)
                            <div class="card-inner">
                                {{ $invoices->links() }}
                            </div><!-- .card-inner -->
                            @endif
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection
