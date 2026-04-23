@extends('layouts.admin')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">General Settings</h3>
                            <div class="nk-block-des text-soft">
                                <p>Configure global platform identity and contact information.</p>
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
            <form action="{{ route('admin.settings.global.update') }}" method="POST">
                @csrf
                <div class="card card-bordered">
                    <div class="card-inner">
                        @foreach($settings as $group => $items)
                        <div class="nk-block-head nk-block-head-line mt-4 first:mt-0" style="margin-top: {{ $loop->first ? '0' : '2rem' }};">
                            <h6 class="title overline-title text-base">{{ $group }} Settings</h6>
                        </div>
                        <div class="row g-3">
                            @foreach($items as $setting)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ ucfirst(str_replace('_', ' ', $setting->key)) }}</label>
                                    @if($setting->key === 'enable_otp')
                                        <select name="{{ $setting->key }}" class="form-select">
                                            <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Enabled</option>
                                            <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Disabled</option>
                                        </select>
                                    @else
                                        <input type="text" name="{{ $setting->key }}" class="form-control" value="{{ $setting->value }}">
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary">Save Global Settings</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection
