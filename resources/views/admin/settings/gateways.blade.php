@extends('layouts.admin')

@section('title', 'Payment Gateway Settings')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Payment Settings</h3>
                            <div class="nk-block-des text-soft">
                                <p>Manage your payment providers, API keys, and bank transfer details.</p>
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
                                            <th class="tb-tnx-id"><span class="">Provider</span></th>
                                            <th class="tb-tnx-info">
                                                <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                    <span>Connection Type</span>
                                                </span>
                                            </th>
                                            <th class="tb-tnx-status"><span class="d-none d-md-inline-block">Status</span></th>
                                            <th class="tb-tnx-action"><span>&nbsp;</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gateways as $gateway)
                                        <tr class="tb-tnx-item">
                                            <td class="tb-tnx-id">
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar sm bg-dim-primary text-primary me-2">
                                                        <em class="icon ni ni-{{ $gateway->slug == 'bank_transfer' ? 'building' : 'cc-alt' }}"></em>
                                                    </div>
                                                    <span class="fw-bold">{{ $gateway->name }}</span>
                                                </div>
                                            </td>
                                            <td class="tb-tnx-info">
                                                <span class="tb-tnx-desc">
                                                    <span class="badge badge-dim bg-outline-{{ $gateway->slug == 'bank_transfer' ? 'light' : 'primary' }}">
                                                        {{ $gateway->slug == 'bank_transfer' ? 'Offline / Manual' : 'API / Real-time' }}
                                                    </span>
                                                </span>
                                            </td>
                                            <td class="tb-tnx-status">
                                                @if($gateway->is_active)
                                                    <span class="badge badge-dot bg-success">Active</span>
                                                @else
                                                    <span class="badge badge-dot bg-danger">Disabled</span>
                                                @endif
                                            </td>
                                            <td class="tb-tnx-action">
                                                <button class="btn btn-trigger btn-icon" data-bs-toggle="modal" data-bs-target="#modal-{{ $gateway->slug }}">
                                                    <em class="icon ni ni-setting-alt"></em>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

{{-- Dynamic Modals for each Gateway --}}
@foreach($gateways as $gateway)
<div class="modal fade" id="modal-{{ $gateway->slug }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Configure {{ $gateway->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.settings.gateways.update', $gateway->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-4 pb-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="title">Enable Provider</h6>
                                <p class="text-soft small">Toggle this provider on or off globally.</p>
                            </div>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" name="is_active" id="sw-{{ $gateway->id }}" {{ $gateway->is_active ? 'checked' : '' }}>
                                <label class="custom-control-label" for="sw-{{ $gateway->id }}"></label>
                            </div>
                        </div>
                    </div>

                    @if($gateway->slug == 'bank_transfer')
                        <div class="form-group mb-3">
                            <label class="form-label">Bank Name</label>
                            <input type="text" name="config[bank_name]" class="form-control" value="{{ $gateway->config['bank_name'] ?? '' }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Account Number</label>
                            <input type="text" name="config[account_number]" class="form-control" value="{{ $gateway->config['account_number'] ?? '' }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Account Name</label>
                            <input type="text" name="config[account_name]" class="form-control" value="{{ $gateway->config['account_name'] ?? '' }}" required>
                        </div>
                    @else
                        <div class="form-group mb-3">
                            <label class="form-label">Public Key</label>
                            <div class="form-control-wrap">
                                <input type="password" name="config[public_key]" class="form-control" value="{{ $gateway->config['public_key'] ?? '' }}" placeholder="Paste your public key here">
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Secret Key</label>
                            <div class="form-control-wrap">
                                <input type="password" name="config[secret_key]" class="form-control" value="{{ $gateway->config['secret_key'] ?? '' }}" placeholder="Paste your secret key here">
                            </div>
                        </div>
                        @if($gateway->slug == 'flutterwave')
                        <div class="form-group mb-3">
                            <label class="form-label">Encryption Key</label>
                            <div class="form-control-wrap">
                                <input type="password" name="config[encryption_key]" class="form-control" value="{{ $gateway->config['encryption_key'] ?? '' }}">
                            </div>
                        </div>
                        @endif
                        <div class="alert alert-warning small py-2 mt-3">
                            <em class="icon ni ni-alert-circle"></em>
                            <span>Ensure your webhook URL is set to <code>{{ url('api/paystack/webhook') }}</code> in your provider dashboard.</span>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@endsection
