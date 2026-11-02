@extends('layouts.admin')

@section('title', 'IT & Infrastructure Operations')

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">IT Infrastructure & Security Registry</h3>
                    <div class="nk-block-des text-soft">
                        <p>System runtime metrics, feature flags toggles, ghost file purging, and security audit logs.</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-icon mb-4">
                <em class="icon ni ni-check-circle"></em> {{ session('success') }}
            </div>
        @endif

        <div class="row g-gs mb-4">
            <!-- Left Column: Feature Flags & Maintenance -->
            <div class="col-lg-6">
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <div class="card-title-group mb-3">
                            <div class="card-title">
                                <h5 class="title">Dynamic System Feature Flags</h5>
                            </div>
                        </div>

                        <div class="list-group list-group-flush border rounded">
                            @foreach($featureFlags as $flag)
                            <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <div class="fw-bold">{{ ucwords(str_replace('_', ' ', $flag->name)) }}</div>
                                    <small class="text-muted">{{ $flag->description }}</small>
                                </div>
                                <form action="{{ route('dashboard.it.flags.toggle', $flag->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $flag->is_enabled ? 'btn-success' : 'btn-outline-danger' }}">
                                        {{ $flag->is_enabled ? 'Enabled' : 'Disabled' }}
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Ghost File Batch Cleaner Card -->
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="title text-danger mb-0"><em class="icon ni ni-trash text-danger"></em> Unpaid Ghost File Cleaner</h5>
                            <span class="badge bg-danger">{{ $systemStats['ghost_files_count'] }} detected</span>
                        </div>
                        <p class="text-muted small mb-3">
                            Purges raw uploaded manuscript Word/PDF documents attached to unpaid prospect accounts older than 60 days to reclaim server storage while preserving lead metadata and audit records.
                        </p>
                        <form action="{{ route('dashboard.it.ghost.flush') }}" method="POST" onsubmit="return confirm('Are you sure you want to flush raw files for all unpaid leads older than 60 days?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" {{ $systemStats['ghost_files_count'] == 0 ? 'disabled' : '' }}>
                                <em class="icon ni ni-shield-alert"></em> Flush Unpaid Ghost Files (>60 Days)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: System Diagnostics & Audits -->
            <div class="col-lg-6">
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <h5 class="title mb-3">Environment Runtime Diagnostics</h5>
                        <table class="table table-sm table-bordered">
                            <tr>
                                <td class="text-muted">PHP Version:</td>
                                <td class="fw-bold">{{ $systemStats['php_version'] }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Framework:</td>
                                <td class="fw-bold">Laravel {{ $systemStats['laravel_version'] }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Primary Database:</td>
                                <td class="fw-bold">{{ ucfirst($systemStats['database_connection']) }} (MySQL 8.x)</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Total Audit Events:</td>
                                <td class="fw-bold">{{ number_format($systemStats['total_audit_logs']) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card card-bordered">
                    <div class="card-inner">
                        <h5 class="title mb-3"><em class="icon ni ni-shield-check text-success"></em> System Security Audit Log Stream</h5>
                        <div class="timeline border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
                            @foreach($auditLogs as $log)
                            <div class="p-2 mb-2 bg-white rounded border-start border-3 {{ str_contains($log->action, 'warning') ? 'border-danger' : 'border-primary' }} shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge {{ str_contains($log->action, 'warning') ? 'bg-danger' : 'bg-outline-primary' }}" style="font-size: 11px;">
                                        {{ $log->action }}
                                    </span>
                                    <span class="text-muted" style="font-size: 11px;">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="small text-muted mb-1">
                                    User ID: {{ $log->user_id }} &bull; IP: <code>{{ $log->ip_address }}</code>
                                </div>
                                @if(!empty($log->changes))
                                    <pre class="bg-light p-1 rounded small mb-0" style="font-size: 10px; max-height: 80px; overflow: hidden;">{{ json_encode($log->changes, JSON_PRETTY_PRINT) }}</pre>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

