@extends('layouts.admin')

@section('title', 'Acquisitions Dashboard')

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Acquisitions & Vetting Department</h3>
                    <div class="nk-block-des text-soft">
                        <p>Intake assessment, manuscript classification (Osisi Hybrid vs Traditional), and ISBN management.</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-icon mb-4">
                <em class="icon ni ni-check-circle"></em> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-icon mb-4">
                <em class="icon ni ni-alert-circle"></em> {{ session('error') }}
            </div>
        @endif

        <!-- Quick Stats Cards -->
        <div class="row g-gs mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-2">
                            <div class="card-title">
                                <h6 class="title">Awaiting Vetting</h6>
                            </div>
                            <div class="card-tools">
                                <em class="card-hint icon ni ni-inbox text-primary"></em>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            <div class="nk-sale-data">
                                <span class="amount">{{ $stats['pending_review'] }}</span>
                                <span class="sub-title">Active manuscripts in queue</span>
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
                                <h6 class="title">Traditional Candidates</h6>
                            </div>
                            <div class="card-tools">
                                <em class="card-hint icon ni ni-award text-success"></em>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            <div class="nk-sale-data">
                                <span class="amount">{{ $stats['traditional_candidates'] }}</span>
                                <span class="sub-title">100% house-funded</span>
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
                                <h6 class="title">Osisi Hybrid Leads</h6>
                            </div>
                            <div class="card-tools">
                                <em class="card-hint icon ni ni-layers text-info"></em>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            <div class="nk-sale-data">
                                <span class="amount">{{ $stats['osisi_hybrid'] }}</span>
                                <span class="sub-title">Author co-published</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-bordered border-danger">
                    <div class="card-inner">
                        <div class="card-title-group align-start mb-2">
                            <div class="card-title">
                                <h6 class="title text-danger">Pending Follow-Up Alert</h6>
                            </div>
                            <div class="card-tools">
                                <em class="card-hint icon ni ni-bell-fill text-danger"></em>
                            </div>
                        </div>
                        <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                            <div class="nk-sale-data">
                                <span class="amount text-danger">{{ $stats['critical_followups'] }}</span>
                                <span class="sub-title text-danger">24hr abandoned reservations</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($abandonedFollowups->count() > 0)
        <!-- Prompt 6: Pending Follow-Up Pipeline (Bright Red Alert Banner & Table) -->
        <div class="card card-bordered border-danger bg-danger-dim mb-4">
            <div class="card-inner">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge bg-danger me-2"><em class="icon ni ni-alert-fill"></em> CRITICAL ALERT</span>
                    <h5 class="title text-danger mb-0">Pending Follow-Up Pipeline (24hr Editorial Slot Expiry)</h5>
                </div>
                <p class="text-danger small mb-3">
                    The following Osisi Hybrid leads abandoned checkout over 24 hours ago. Final warning emails have been dispatched. Direct intervention is recommended before slots expire.
                </p>
                <div class="table-responsive bg-white rounded border">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Author</th>
                                <th>Book Title</th>
                                <th>Attribution Source</th>
                                <th>Age</th>
                                <th>Word Count</th>
                                <th>Calculated Fee</th>
                                <th>Abandoned Since</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($abandonedFollowups as $lead)
                            <tr class="table-danger">
                                <td>
                                    <strong>{{ $lead->name }}</strong><br>
                                    <small class="text-muted">{{ $lead->email }}</small>
                                </td>
                                <td>{{ $lead->book_title }}</td>
                                <td>
                                    <span class="badge bg-outline-danger">{{ ucfirst($lead->marketing_source ?: 'direct') }}</span>
                                </td>
                                <td>{{ $lead->author_age ?? 'N/A' }}</td>
                                <td>{{ number_format($lead->number_of_words) }}</td>
                                <td>₦{{ number_format($lead->total_calculated_fee, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($lead->created_at)->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="mailto:{{ $lead->email }}" class="btn btn-sm btn-danger">
                                        <em class="icon ni ni-mail"></em> Reach Out
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Primary Acquisition Vetting Queue -->
        <div class="card card-bordered">
            <div class="card-inner">
                <div class="card-title-group mb-3">
                    <div class="card-title">
                        <h5 class="title">Active Acquisition Intake Queue</h5>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Priority / Title</th>
                                <th>Author</th>
                                <th>Genre / Model</th>
                                <th>Rating</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Assigned ISBN</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vettingQueue as $item)
                            <tr>
                                <td>
                                    @if(!empty($item->metadata_file['escalated_acquisition']))
                                        <span class="badge bg-warning text-dark me-1"><em class="icon ni ni-zap"></em> Escalated</span>
                                    @endif
                                    <strong>{{ $item->book_title }}</strong><br>
                                    <small class="text-muted">{{ number_format($item->number_of_words) }} words</small>
                                </td>
                                <td>
                                    {{ $item->name }}<br>
                                    <small class="text-muted">{{ $item->email }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $item->isTraditional() ? 'bg-success' : 'bg-primary' }}">
                                        {{ $item->isTraditional() ? 'Traditional' : 'Osisi Hybrid' }}
                                    </span><br>
                                    <small class="text-muted">{{ $item->genre }}</small>
                                </td>
                                <td>
                                    @if($item->manuscript_rating)
                                        <span class="badge bg-outline-info">{{ ucfirst($item->manuscript_rating->value ?? $item->manuscript_rating) }}</span>
                                    @else
                                        <span class="badge bg-outline-light text-muted">Unrated</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $item->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($item->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $item->metadata_file['isbn'] ?? 'Not Assigned' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-outline-secondary">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('dashboard.acquisition.show', $item->id) }}" class="btn btn-sm btn-primary">
                                        <em class="icon ni ni-eye"></em> Review Vetting
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No manuscripts currently waiting in the acquisition queue.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $vettingQueue->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

