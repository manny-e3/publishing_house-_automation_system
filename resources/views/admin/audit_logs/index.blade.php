@extends('layouts.admin')

@section('title', 'Audit Logs & User Activities')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- Page Header with Export -->
                <div class="nk-block-head nk-block-head-sm mb-4">
                    <div class="nk-block-between align-items-center flex-wrap gap-2">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title mb-1">Audit Logs & User Activities</h3>
                            <div class="nk-block-des text-soft">
                                <p class="mb-0">Comprehensive, tamper-evident audit trail capturing user logins, publishing gate transitions, security blocks, and financial events.</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('admin.audit_logs.export', request()->query()) }}" class="btn btn-outline-primary shadow-sm">
                                <em class="icon ni ni-download-cloud me-1"></em> Export Audit Trail (CSV)
                            </a>
                        </div>
                    </div>
                </div>

                <!-- KPI Metric Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-bordered">
                            <div class="card-inner py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fs-12px text-muted text-uppercase fw-bold">Total Recorded Logs</span>
                                        <div class="fs-3 fw-bold text-dark mt-1">{{ number_format($totalLogsCount) }}</div>
                                    </div>
                                    <div class="user-avatar bg-primary-dim text-primary">
                                        <em class="icon ni ni-activity-round fs-4"></em>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-bordered">
                            <div class="card-inner py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fs-12px text-muted text-uppercase fw-bold">Today's Activities</span>
                                        <div class="fs-3 fw-bold text-info mt-1">{{ number_format($todayLogsCount) }}</div>
                                    </div>
                                    <div class="user-avatar bg-info-dim text-info">
                                        <em class="icon ni ni-clock fs-4"></em>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-bordered">
                            <div class="card-inner py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fs-12px text-muted text-uppercase fw-bold">Active Users Today</span>
                                        <div class="fs-3 fw-bold text-success mt-1">{{ number_format($activeUsersTodayCount) }}</div>
                                    </div>
                                    <div class="user-avatar bg-success-dim text-success">
                                        <em class="icon ni ni-users fs-4"></em>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card card-bordered">
                            <div class="card-inner py-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="fs-12px text-muted text-uppercase fw-bold">Security & Alerts</span>
                                        <div class="fs-3 fw-bold text-danger mt-1">{{ number_format($securityAlertsCount) }}</div>
                                    </div>
                                    <div class="user-avatar bg-danger-dim text-danger">
                                        <em class="icon ni ni-shield-alert fs-4"></em>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter & Search Bar -->
                <div class="card card-bordered mb-4">
                    <div class="card-inner py-3">
                        <form action="{{ route('admin.audit_logs.index') }}" method="GET" class="row g-2 align-items-center">
                            <div class="col-lg-3 col-md-6">
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-search"></em>
                                    </div>
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search action, IP, user, details..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <select name="category" class="form-select form-select-sm">
                                    <option value="">All Categories</option>
                                    <option value="security" {{ request('category') === 'security' ? 'selected' : '' }}>Security & Alerts</option>
                                    <option value="auth" {{ request('category') === 'auth' ? 'selected' : '' }}>Auth & Logins</option>
                                    <option value="publishing" {{ request('category') === 'publishing' ? 'selected' : '' }}>Publishing & Gates</option>
                                    <option value="financial" {{ request('category') === 'financial' ? 'selected' : '' }}>Financial & Billing</option>
                                    <option value="user_management" {{ request('category') === 'user_management' ? 'selected' : '' }}>User Management</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <select name="user_id" class="form-select form-select-sm">
                                    <option value="">All Users</option>
                                    @foreach($allUsers as $u)
                                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <select name="period" class="form-select form-select-sm">
                                    <option value="all" {{ request('period') === 'all' ? 'selected' : '' }}>All Time</option>
                                    <option value="today" {{ request('period') === 'today' ? 'selected' : '' }}>Today</option>
                                    <option value="yesterday" {{ request('period') === 'yesterday' ? 'selected' : '' }}>Yesterday</option>
                                    <option value="7days" {{ request('period') === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                                    <option value="30days" {{ request('period') === '30days' ? 'selected' : '' }}>Last 30 Days</option>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-6 d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-primary flex-fill">
                                    <em class="icon ni ni-filter me-1"></em> Filter
                                </button>
                                @if(request()->hasAny(['search', 'category', 'user_id', 'period']))
                                    <a href="{{ route('admin.audit_logs.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Audit Logs Table -->
                <div class="card card-bordered">
                    <div class="card-inner p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 22%;">User / Actor</th>
                                        <th style="width: 28%;">Action & Event</th>
                                        <th style="width: 20%;">Target Resource</th>
                                        <th style="width: 15%;">IP & Client</th>
                                        <th style="width: 15%;" class="text-end">Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                    <tr>
                                        <td>
                                            @if($log->user)
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="user-avatar xs bg-primary-dim text-primary">
                                                        <span>{{ strtoupper(substr($log->user->name, 0, 2)) }}</span>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('admin.users.activity', $log->user->id) }}" class="fw-bold text-dark text-decoration-none">
                                                            {{ $log->user->name }}
                                                        </a>
                                                        <div class="small text-muted" style="font-size: 11px;">{{ $log->user->email }}</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center gap-2 text-muted">
                                                    <div class="user-avatar xs bg-light text-muted">
                                                        <em class="icon ni ni-server"></em>
                                                    </div>
                                                    <div>
                                                        <span class="fw-bold text-secondary">System / Guest</span>
                                                        <div class="small text-muted" style="font-size: 11px;">Automated Service</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge {{ $log->badge_class }} py-1 px-2 d-inline-flex align-items-center gap-1">
                                                    <em class="icon ni {{ $log->icon }}"></em>
                                                    <span>{{ $log->human_action }}</span>
                                                </span>
                                            </div>
                                            <div class="small text-muted mt-1 font-monospace" style="font-size: 11px;">
                                                {{ $log->action }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark text-truncate" style="max-width: 220px;" title="{{ $log->target_title }}">
                                                {{ $log->target_title }}
                                            </div>
                                            @if($log->changes && is_array($log->changes) && count($log->changes) > 0)
                                                <button type="button" class="btn btn-xs btn-link p-0 text-primary" data-bs-toggle="modal" data-bs-target="#logModal-{{ $log->id }}">
                                                    <em class="icon ni ni-eye me-1"></em> View Details
                                                </button>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="font-monospace text-dark small">{{ $log->ip_address ?: '127.0.0.1' }}</div>
                                            @if($log->user_agent)
                                                <span class="small text-muted text-truncate d-block" style="max-width: 140px; font-size: 10px;" title="{{ $log->user_agent }}">
                                                    {{ Str::limit($log->user_agent, 20) }}
                                                </span>
                                            @else
                                                <span class="small text-muted" style="font-size: 10px;">Web Service</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="fw-medium text-dark small">{{ $log->created_at->diffForHumans() }}</div>
                                            <div class="text-muted" style="font-size: 11px;">{{ $log->created_at->format('M d, Y H:i:s') }}</div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <em class="icon ni ni-inbox fs-1 mb-2 d-block"></em>
                                            No audit activities matched the selected filters.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="p-3 border-top">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>

                <!-- Modals for JSON Payload & Diff Inspection -->
                @foreach($logs as $log)
                    @if($log->changes && is_array($log->changes) && count($log->changes) > 0)
                    <div class="modal fade" id="logModal-{{ $log->id }}" tabindex="-1" aria-labelledby="logModalLabel-{{ $log->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content shadow">
                                <div class="modal-header bg-lighter border-bottom">
                                    <div>
                                        <h5 class="modal-title text-dark" id="logModalLabel-{{ $log->id }}">
                                            <em class="icon ni {{ $log->icon }} text-primary me-1"></em>
                                            {{ $log->human_action }}
                                        </h5>
                                        <span class="small text-muted">Log Entry #{{ $log->id }} &bull; {{ $log->created_at->format('M d, Y H:i:s') }}</span>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-6">
                                            <span class="small text-muted d-block">User / Actor</span>
                                            <strong>{{ $log->user ? $log->user->name . ' (' . $log->user->email . ')' : 'System Automation / Guest' }}</strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="small text-muted d-block">IP Address & Location</span>
                                            <span class="font-monospace">{{ $log->ip_address ?: '127.0.0.1' }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="small text-muted d-block">Target Model</span>
                                            <span>{{ $log->target_title }} (<code>{{ class_basename($log->model_type) ?: 'N/A' }} #{{ $log->model_id ?: 'N/A' }}</code>)</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="small text-muted d-block">User Agent / Client</span>
                                            <span class="small text-muted text-break">{{ $log->user_agent ?: 'Internal System Client' }}</span>
                                        </div>
                                    </div>

                                    <h6 class="overline-title text-muted mb-2">Payload & Changes (JSON Diff)</h6>
                                    <div class="bg-dark text-light p-3 rounded font-monospace small" style="max-height: 350px; overflow-y: auto;">
                                        <pre class="text-light mb-0" style="white-space: pre-wrap;">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                    </div>
                                </div>
                                <div class="modal-footer bg-light border-top">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

