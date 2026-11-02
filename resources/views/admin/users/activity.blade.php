@extends('layouts.admin')

@section('title', 'User Activity Timeline: ' . $user->name)

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- Header with Navigation -->
                <div class="nk-block-head nk-block-head-sm mb-4">
                    <div class="nk-block-between align-items-center flex-wrap gap-2">
                        <div class="nk-block-head-content">
                            <div class="nk-block-des text-soft mb-1">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-link p-0 text-muted">
                                    <em class="icon ni ni-arrow-left me-1"></em> Back to User List
                                </a>
                            </div>
                            <h3 class="nk-block-title page-title">User Activity & Audit Trail</h3>
                        </div>
                        <div class="nk-block-head-content d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                <em class="icon ni ni-edit me-1"></em> Edit Profile
                            </a>
                            <a href="{{ route('admin.audit_logs.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-primary">
                                <em class="icon ni ni-filter me-1"></em> View in Master Audit Log
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile & Metric Overview -->
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-5">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar lg bg-primary text-white fs-4">
                                        <span>{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 text-dark">{{ $user->name }}</h4>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                        <div class="mt-2 d-flex flex-wrap gap-1">
                                            @forelse($user->roles as $role)
                                                <span class="badge bg-primary-dim text-primary text-uppercase">{{ $role->name }}</span>
                                            @empty
                                                <span class="badge bg-light text-muted">Author / Standard</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="row g-3 text-center text-md-start">
                                    <div class="col-4">
                                        <span class="small text-muted text-uppercase fw-bold d-block">Total Actions</span>
                                        <span class="fs-4 fw-bold text-dark">{{ number_format($activities->total()) }}</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="small text-muted text-uppercase fw-bold d-block">Logins</span>
                                        <span class="fs-4 fw-bold text-info">{{ number_format($loginCount) }}</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="small text-muted text-uppercase fw-bold d-block">Last Active</span>
                                        <span class="fs-6 fw-bold text-dark">
                                            {{ $activities->first() ? $activities->first()->created_at->diffForHumans() : 'Never' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Activity Timeline -->
                <div class="card card-bordered">
                    <div class="card-inner-group">
                        <div class="card-inner py-3 border-bottom">
                            <div class="card-title-group">
                                <div class="card-title">
                                    <h6 class="title mb-0"><em class="icon ni ni-history text-primary me-1"></em> Chronological Activity Timeline</h6>
                                </div>
                                <div class="card-tools text-muted small">
                                    Showing {{ $activities->firstItem() ?? 0 }} - {{ $activities->lastItem() ?? 0 }} of {{ $activities->total() }} events
                                </div>
                            </div>
                        </div>
                        <div class="card-inner p-4">
                            <ul class="nk-activity">
                                @forelse($activities as $act)
                                <li class="nk-activity-item pb-3 mb-3 border-bottom">
                                    <div class="nk-activity-media user-avatar sm {{ $act->category === 'security' ? 'bg-danger-dim text-danger' : ($act->category === 'publishing' ? 'bg-success-dim text-success' : 'bg-primary-dim text-primary') }}">
                                        <em class="icon ni {{ $act->icon }}"></em>
                                    </div>
                                    <div class="nk-activity-data">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div>
                                                <span class="fw-bold text-dark fs-6">{{ $act->human_action }}</span>
                                                <span class="badge {{ $act->badge_class }} ms-2" style="font-size: 10px;">{{ strtoupper($act->category) }}</span>
                                            </div>
                                            <div class="text-muted small">
                                                <em class="icon ni ni-clock me-1"></em> {{ $act->created_at->format('M d, Y H:i:s') }} ({{ $act->created_at->diffForHumans() }})
                                            </div>
                                        </div>
                                        
                                        <div class="text-muted small mt-1">
                                            Target: <strong class="text-dark">{{ $act->target_title }}</strong> &bull; IP: <code class="text-dark">{{ $act->ip_address ?: '127.0.0.1' }}</code>
                                        </div>

                                        @if($act->changes && is_array($act->changes) && count($act->changes) > 0)
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-xs btn-outline-light text-primary" data-bs-toggle="collapse" data-bs-target="#userDiff-{{ $act->id }}">
                                                    <em class="icon ni ni-code me-1"></em> Toggle Change Payload
                                                </button>
                                                <div class="collapse mt-2" id="userDiff-{{ $act->id }}">
                                                    <div class="bg-dark text-light p-3 rounded font-monospace small">
                                                        <pre class="text-light mb-0" style="white-space: pre-wrap;">{{ json_encode($act->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                                @empty
                                <li class="text-center py-5 text-muted">
                                    <em class="icon ni ni-inbox fs-1 mb-2 d-block"></em>
                                    No activity recorded for this user yet.
                                </li>
                                @endforelse
                            </ul>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $activities->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

