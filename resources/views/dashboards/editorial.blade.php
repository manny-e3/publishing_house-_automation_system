@extends('layouts.admin')

@section('title', 'Editorial Workspace')

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Editorial Review & Quality Board</h3>
                    <div class="nk-block-des text-soft">
                        <p>Manage the 7-stage editorial revision cycle, assess literary quality scores, and enforce Gate 1 clearance.</p>
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

        <div class="card card-bordered">
            <div class="card-inner">
                <div class="card-title-group mb-3">
                    <div class="card-title">
                        <h5 class="title">Active Editorial Pipeline</h5>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Model</th>
                                <th>Current Status</th>
                                <th>Quality Score</th>
                                <th>Gate 1 (Dummy 3)</th>
                                <th>Regression Alert</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($manuscripts as $manuscript)
                            @php
                                $dummy3Done = !empty($manuscript->checklist_progress['dummy_copy_editing_3']['completed']);
                                $hasRegression = !empty($manuscript->metadata_file['regression_alert']);
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $manuscript->book_title }}</strong><br>
                                    <small class="text-muted">{{ number_format($manuscript->number_of_words) }} words &bull; {{ $manuscript->genre }}</small>
                                </td>
                                <td>{{ $manuscript->name }}</td>
                                <td>
                                    <span class="badge {{ $manuscript->isTraditional() ? 'bg-success' : 'bg-primary' }}">
                                        {{ $manuscript->isTraditional() ? 'Traditional' : 'Osisi Hybrid' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-outline-secondary">{{ ucfirst(str_replace('_', ' ', $manuscript->status)) }}</span>
                                </td>
                                <td>
                                    @if($manuscript->manuscript_rating)
                                        @php
                                            $ratingVal = $manuscript->manuscript_rating->value ?? $manuscript->manuscript_rating;
                                            $badgeClass = match($ratingVal) {
                                                'excellent' => 'bg-success',
                                                'promising' => 'bg-info',
                                                'some_work_required' => 'bg-warning',
                                                'poor' => 'bg-danger',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $ratingVal)) }}</span>
                                        @if($ratingVal === 'excellent' && ($manuscript->isOsisiHybrid()))
                                            <span class="badge bg-warning text-dark ms-1" title="Recommended for Traditional switch"><em class="icon ni ni-award"></em> Traditional Switch</span>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-muted">Unscored</span>
                                    @endif
                                </td>
                                <td>
                                    @if($dummy3Done)
                                        <span class="badge bg-success"><em class="icon ni ni-unlock"></em> Gate 1 Cleared</span>
                                    @else
                                        <span class="badge bg-light text-muted"><em class="icon ni ni-lock"></em> Incomplete</span>
                                    @endif
                                </td>
                                <td>
                                    @if($hasRegression)
                                        <span class="badge bg-danger"><em class="icon ni ni-alert-fill"></em> Regression Active</span>
                                    @else
                                        <span class="text-muted small">&mdash;</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('dashboard.editorial.show', $manuscript->id) }}" class="btn btn-sm btn-primary">
                                        <em class="icon ni ni-edit"></em> Editorial Workspace
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No manuscripts currently in the editorial phase.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $manuscripts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

