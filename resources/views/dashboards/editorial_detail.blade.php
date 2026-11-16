@extends('layouts.admin')

@section('title', 'Editorial Review: ' . $prospect->book_title)

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <a href="{{ route('dashboard.editorial.index') }}" class="text-muted small">&larr; Back to Editorial Pipeline</a>
                    <h3 class="nk-block-title page-title mt-1">{{ $prospect->book_title }}</h3>
                    <div class="nk-block-des text-soft">
                        <p>Author: <strong>{{ $prospect->name }}</strong> &bull; Model: <strong>{{ $prospect->isTraditional() ? 'Traditional' : 'Osisi Hybrid' }}</strong> &bull; Word Count: <strong>{{ number_format($prospect->number_of_words) }}</strong></p>
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

        @if($regressionAlert)
            <div class="alert alert-warning alert-icon mb-4">
                <em class="icon ni ni-alert-fill"></em>
                <strong>Gate 2 Regression Notice:</strong> A dummy copy edit was modified after design had started. The design phase has been reset to <code>in_progress</code> and tagged with <code>_EDIT_REVISION</code> until reconciled.
            </div>
        @endif

        @if(!empty($prospect->metadata_file['recommended_for_traditional']))
            <div class="alert alert-success alert-icon mb-4 border-success">
                <em class="icon ni ni-award"></em>
                <strong>Executive Board Recommendation:</strong> This manuscript scored <strong>EXCELLENT</strong> under Osisi Hybrid. An alert has been forwarded to the Executive Board to evaluate offering a 100% house-funded Traditional Contract.
            </div>
        @endif

        <div class="row g-gs">
            <!-- Left Column: 7-Stage Editorial Checklist -->
            <div class="col-lg-7">
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <div class="card-title-group mb-3">
                            <div class="card-title">
                                <h5 class="title">7-Stage Editorial Revision Pipeline</h5>
                            </div>
                            <div class="card-tools">
                                <span class="badge bg-primary">{{ $progress['completed'] }} of {{ $progress['total'] }} Stages ({{ $progress['percentage'] }}%)</span>
                            </div>
                        </div>

                        <div class="progress progress-sm mb-4">
                            <div class="progress-bar bg-primary" data-progress="{{ $progress['percentage'] }}" style="width: {{ $progress['percentage'] }}%;"></div>
                        </div>

                        <!-- Gate 1 Clearance Status Alert -->
                        <div class="alert {{ $gate1Cleared ? 'alert-success' : 'alert-light' }} p-3 mb-4 rounded border">
                            <div class="d-flex align-items-center">
                                <em class="icon ni {{ $gate1Cleared ? 'ni-unlock' : 'ni-lock' }} fs-3 me-2 {{ $gate1Cleared ? 'text-success' : 'text-muted' }}"></em>
                                <div>
                                    <strong>Gate 1 (Design Clearance):</strong>
                                    @if($gate1Cleared)
                                        <span class="text-success fw-bold">CLEARED.</span> Dummy Copy Editing 3 complete &mdash; Design Workspace is UNLOCKED.
                                    @else
                                        <span class="text-muted">LOCKED. Design workspace dropzones unlock automatically once Dummy Copy Editing 3 is 100% verified.</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="list-group list-group-flush border rounded" x-data="{
                            toggleTask(key, checked) {
                                fetch('{{ route('dashboard.editorial.task.toggle', $prospect->id) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ task_key: key, completed: checked })
                                }).then(r => r.json()).then(data => {
                                    window.location.reload();
                                });
                            }
                        }">
                            @foreach($editorialTasks as $key => $title)
                                @php 
                                    $isDone = !empty($prospect->checklist_progress[$key]['completed']); 
                                    $isGateKey = $key === 'dummy_copy_editing_3';
                                @endphp
                                <div class="list-group-item d-flex justify-content-between align-items-center py-3 {{ $isGateKey ? 'bg-light' : '' }}">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="task_{{ $key }}" 
                                            {{ $isDone ? 'checked' : '' }}
                                            @change="toggleTask('{{ $key }}', $event.target.checked)">
                                        <label class="custom-control-label fw-bold {{ $isDone ? 'text-decoration-line-through text-muted' : '' }}" for="task_{{ $key }}">
                                            {{ $title }}
                                            @if($isGateKey)
                                                <span class="badge bg-warning text-dark ms-2">&larr; Gate 1 Trigger</span>
                                            @endif
                                        </label>
                                    </div>
                                    @if($isDone)
                                        <span class="badge bg-success-dim text-success">
                                            <em class="icon ni ni-check"></em> Completed
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted">Pending</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Quality Score Rating & Reviewer Notes -->
            <div class="col-lg-5">
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <h5 class="title mb-3">Literary Quality Evaluation Matrix</h5>
                        <p class="text-muted small mb-3">
                            Assess the manuscript's literary merit and market readiness. Rating an Osisi Hybrid project as <strong>Excellent</strong> automatically triggers an Executive Board escalation.
                        </p>

                        <form action="{{ route('dashboard.editorial.rate', $prospect->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Manuscript Rating</label>
                                <select name="rating" class="form-select" required>
                                    @php $currentRating = $prospect->manuscript_rating?->value ?? $prospect->manuscript_rating; @endphp
                                    <option value="excellent" {{ $currentRating === 'excellent' ? 'selected' : '' }}>⭐ Excellent (Board Escalation Trigger)</option>
                                    <option value="promising" {{ $currentRating === 'promising' ? 'selected' : '' }}>✨ Promising (Strong Commercial Potential)</option>
                                    <option value="some_work_required" {{ $currentRating === 'some_work_required' ? 'selected' : '' }}>🛠 Some Work Required (Developmental Focus)</option>
                                    <option value="poor" {{ $currentRating === 'poor' ? 'selected' : '' }}>❌ Poor (Sub-Standard Quality)</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold">Editorial Notes & Feedback</label>
                                <textarea name="notes" class="form-control" rows="4" placeholder="Enter structural critique, dialogue assessment, pacing observations...">{{ $prospect->metadata_file['editorial_review_notes'] ?? '' }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <em class="icon ni ni-save"></em> Save Quality Assessment
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Manuscript Excerpt & Assets Access Card -->
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <h6 class="title mb-2">Manuscript Excerpt & Submission Assets</h6>
                        @if($prospect->manuscript_file_path || $prospect->excerpt_file_path)
                            <a href="{{ route('dashboard.editorial.download_manuscript', $prospect->id) }}" class="btn btn-primary w-100 mb-2">
                                <em class="icon ni ni-download-cloud"></em> Download 3,000-Word Excerpt
                            </a>
                        @else
                            <div class="alert alert-light text-center small text-muted mb-2">
                                No excerpt document uploaded.
                            </div>
                        @endif

                        @if($prospect->cover_design_path)
                            <a href="{{ route('dashboard.editorial.download_cover', $prospect->id) }}" class="btn btn-outline-secondary w-100">
                                <em class="icon ni ni-img"></em> Download Cover Concept
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Synopsis & Pitch Reading Deck (Point 19) -->
                @if($prospect->synopsis)
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="title mb-0">Book Synopsis</h6>
                            <span class="badge bg-light text-dark">{{ $prospect->genre }}</span>
                        </div>
                        <div class="bg-light p-3 rounded text-dark small" style="max-height: 250px; overflow-y: auto; white-space: pre-wrap;">
                            {{ $prospect->synopsis }}
                        </div>
                        @if($prospect->target_audience)
                            <div class="mt-2 text-muted small"><strong>Target Audience:</strong> {{ $prospect->target_audience }}</div>
                        @endif
                    </div>
                </div>
                @endif

                @if($prospect->author_bio)
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <h6 class="title mb-2">About the Author</h6>
                        <p class="small text-muted mb-1">{{ $prospect->author_bio }}</p>
                        @if($prospect->author_location)
                            <span class="badge bg-outline-secondary small"><em class="icon ni ni-map-pin"></em> {{ $prospect->author_location }}</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

