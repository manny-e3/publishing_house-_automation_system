@extends('layouts.admin')

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Pricing Matrix</h3>
                    <div class="nk-block-des text-soft">
                        <p>Manage standard rates for printing, editing, and publishing setup.</p>
                    </div>
                </div>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-icon">
                <em class="icon ni ni-check-circle"></em> {{ session('success') }}
            </div>
        @endif

        <div class="nk-block">
            <form action="{{ route('admin.settings.pricing.update') }}" method="POST">
                @csrf
                <div class="row g-gs">
                    @foreach($rates as $category => $items)
                    <div class="col-md-6">
                        <div class="card card-bordered h-100">
                            <div class="card-inner">
                                <div class="card-title-group mb-4">
                                    <div class="card-title">
                                        <h6 class="title text-uppercase text-primary">{{ $category }} Fees</h6>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    @foreach($items as $rate)
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ $rate->label }}</label>
                                            <div class="form-control-wrap">
                                                <div class="form-text-hint">
                                                    <span class="overline-title">NGN</span>
                                                </div>
                                                <input type="number" step="0.01" name="rates[{{ $rate->id }}]" class="form-control" value="{{ $rate->value }}">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-lg btn-primary">Save Pricing Matrix</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
