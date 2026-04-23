@extends('layouts.admin')

@section('title', 'Generate Invoice')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Generate Publishing Invoice</h3>
                            <div class="nk-block-des text-soft">
                                <p>You accepted the manuscript. Now lock in the final cost and generate the invoice.</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('admin.prospects.index') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back to Tracker</span></a>
                        </div>
                    </div>
                </div>
                
                <div class="nk-block">
                    <div class="card card-bordered card-stretch" style="max-width: 600px;">
                        <div class="card-inner">
                            <form action="{{ route('admin.invoices.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="prospect_id" value="{{ $prospect->id }}">
                                
                                <div class="form-group">
                                    <label class="form-label">Author</label>
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-left">
                                            <em class="icon ni ni-user"></em>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $prospect->name }}" disabled>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label class="form-label">Manuscript Title</label>
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-left">
                                            <em class="icon ni ni-book-read"></em>
                                        </div>
                                        <input type="text" class="form-control" value="{{ $prospect->book_title }}" disabled>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <label class="form-label" for="amount">Final Invoice Amount (₦)</label>
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-left">
                                            <em class="icon ni ni-sign-naira"></em>
                                        </div>
                                        <input type="number" step="0.01" id="amount" name="amount" class="form-control form-control-lg" value="{{ $prospect->estimated_cost }}" required>
                                    </div>
                                    <div class="form-note text-primary mt-1">You can adjust the system-calculated estimate here before locking the invoice.</div>
                                </div>

                                <div class="form-group mt-4">
                                    <label class="form-label">Offered Payment Methods</label>
                                    <div class="row g-3">
                                        @foreach($gateways as $gateway)
                                        <div class="col-sm-6">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="allowed_gateways[]" value="{{ $gateway->slug }}" id="gw-{{ $gateway->slug }}" checked>
                                                <label class="custom-control-label" for="gw-{{ $gateway->slug }}">{{ $gateway->name }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('allowed_gateways')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                    <div class="form-note mt-1">Select at least one payment method to offer the author.</div>
                                </div>

                                <div class="form-group mt-4 pt-4 border-top">
                                    <div class="row g-3 items-center">
                                        <div class="col-md-6">
                                            <label class="form-label mb-0">Minimum Deposit Percentage (%)</label>
                                            <p class="text-soft small mb-0">The lowest amount (in %) the author can pay to activate their project.</p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint">
                                                    <span class="overline-title">%</span>
                                                </div>
                                                <input type="number" name="min_deposit_percentage" class="form-control form-control-lg" value="{{ $minDeposit }}" min="0" max="100" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4 pt-2 border-top">
                                    <button type="submit" class="btn btn-lg btn-primary fw-bold w-100"><em class="icon ni ni-file-docs"></em><span>Lock & Generate Invoice</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
