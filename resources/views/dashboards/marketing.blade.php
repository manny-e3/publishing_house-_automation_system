@extends('layouts.admin')

@section('title', 'Marketing & Attribution ROI Dashboard')

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Marketing Analytics & Campaign Operations</h3>
                    <div class="nk-block-des text-soft">
                        <p>Track multi-channel acquisition ROI, UTM source performance, ARC distributions, and store promotions.</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-icon mb-4">
                <em class="icon ni ni-check-circle"></em> {{ session('success') }}
            </div>
        @endif

        <!-- Prompt 10: Traffic ROI Attribution Matrix -->
        <div class="card card-bordered mb-4">
            <div class="card-inner">
                <div class="card-title-group mb-3">
                    <div class="card-title">
                        <h5 class="title"><em class="icon ni ni-growth text-primary me-1"></em> Multi-Channel Acquisition ROI Matrix</h5>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Attribution Channel</th>
                                <th class="text-center">Total Inquiries</th>
                                <th class="text-center">Paid Conversions</th>
                                <th class="text-center">Conversion Rate</th>
                                <th>Revenue (NGN)</th>
                                <th>Revenue (USD)</th>
                                <th>Avg Revenue / Lead</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matrix as $source => $data)
                            @php
                                $badgeColor = match($source) {
                                    'instagram' => 'bg-danger',
                                    'linkedin' => 'bg-info',
                                    'tiktok' => 'bg-dark',
                                    'google' => 'bg-primary',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge {{ $badgeColor }} me-1">{{ ucfirst($source) }}</span>
                                </td>
                                <td class="text-center fw-bold">{{ $data['leads'] }}</td>
                                <td class="text-center fw-bold text-success">{{ $data['paid'] }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $data['conversion_rate'] > 20 ? 'bg-success' : ($data['conversion_rate'] > 0 ? 'bg-info' : 'bg-light text-muted') }}">
                                        {{ $data['conversion_rate'] }}%
                                    </span>
                                </td>
                                <td class="fw-bold">₦{{ number_format($data['revenue_ngn'], 2) }}</td>
                                <td class="fw-bold text-primary">${{ number_format($data['revenue_usd'], 2) }}</td>
                                <td class="text-muted">₦{{ number_format($data['avg_revenue_ngn'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($followupLeads->count() > 0)
        <!-- Pending Follow-Up Pipeline (Bright Red Alert Table) -->
        <div class="card card-bordered border-danger bg-danger-dim mb-4">
            <div class="card-inner">
                <div class="d-flex align-items-center mb-2">
                    <span class="badge bg-danger me-2"><em class="icon ni ni-bell-fill"></em> RECOVERY RADAR</span>
                    <h5 class="title text-danger mb-0">Pending Follow-Up Pipeline (Abandoned Carts)</h5>
                </div>
                <p class="text-danger small mb-3">
                    These authors initiated an Osisi Hybrid package via marketing ads but stalled before payment. 24hr automated warnings sent. Reach out to prevent lead decay.
                </p>

                <div class="table-responsive bg-white rounded border">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Author</th>
                                <th>Book Title</th>
                                <th>Channel</th>
                                <th>Quoted Fee</th>
                                <th>Time Elapsed</th>
                                <th class="text-end">Contact</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($followupLeads as $lead)
                            <tr class="table-danger">
                                <td><strong>{{ $lead->name }}</strong><br><small class="text-muted">{{ $lead->email }}</small></td>
                                <td>{{ $lead->book_title }}</td>
                                <td><span class="badge bg-danger">{{ ucfirst($lead->marketing_source ?: 'direct') }}</span></td>
                                <td>₦{{ number_format($lead->total_calculated_fee, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($lead->created_at)->diffForHumans() }}</td>
                                <td class="text-end">
                                    <a href="mailto:{{ $lead->email }}" class="btn btn-sm btn-danger">
                                        <em class="icon ni ni-mail"></em> Send Custom Pitch
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

        <!-- Active Marketing Campaigns -->
        <div class="card card-bordered">
            <div class="card-inner">
                <div class="card-title-group mb-3">
                    <div class="card-title">
                        <h5 class="title">Active Marketing Campaigns & Book Promotion</h5>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Attribution Source</th>
                                <th>Target Audience</th>
                                <th>ARC Copies</th>
                                <th>Store Blurb</th>
                                <th>Pre-Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaignManuscripts as $book)
                            @php
                                $campaign = $book->metadata_file['campaign_progress'] ?? [];
                            @endphp
                            <tr x-data="{
                                toggleCampaign(key, checked) {
                                    fetch('{{ route('dashboard.marketing.task.toggle', $book->id) }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ task_key: key, completed: checked })
                                    });
                                }
                            }">
                                <td>
                                    <strong>{{ $book->book_title }}</strong><br>
                                    <small class="text-muted">{{ $book->genre }} &bull; ISBN: {{ $book->metadata_file['isbn'] ?? 'TBD' }}</small>
                                </td>
                                <td>{{ $book->name }}</td>
                                <td>
                                    <span class="badge bg-outline-primary">{{ ucfirst($book->marketing_source ?: 'direct') }}</span>
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="ca_aud_{{ $book->id }}"
                                            {{ !empty($campaign['target_audience_analysis']['completed']) ? 'checked' : '' }}
                                            @change="toggleCampaign('target_audience_analysis', $event.target.checked)">
                                        <label class="custom-control-label small" for="ca_aud_{{ $book->id }}">Audience Analysis</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="ca_arc_{{ $book->id }}"
                                            {{ !empty($campaign['arc_distribution']['completed']) ? 'checked' : '' }}
                                            @change="toggleCampaign('arc_distribution', $event.target.checked)">
                                        <label class="custom-control-label small" for="ca_arc_{{ $book->id }}">ARC Dispatched</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="ca_blurb_{{ $book->id }}"
                                            {{ !empty($campaign['store_blurb_signoff']['completed']) ? 'checked' : '' }}
                                            @change="toggleCampaign('store_blurb_signoff', $event.target.checked)">
                                        <label class="custom-control-label small" for="ca_blurb_{{ $book->id }}">Blurb Sign-off</label>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="ca_pre_{{ $book->id }}"
                                            {{ !empty($campaign['preorder_setup']['completed']) ? 'checked' : '' }}
                                            @change="toggleCampaign('preorder_setup', $event.target.checked)">
                                        <label class="custom-control-label small" for="ca_pre_{{ $book->id }}">Pre-Orders Live</label>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No paid manuscripts currently in active marketing phase.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $campaignManuscripts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

