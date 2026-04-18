@extends('layouts.admin')

@section('title', 'Project Details: ' . $project->prospect->book_title)

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Project / <strong class="text-primary small">{{ $project->prospect->book_title }}</strong></h3>
                            <div class="nk-block-des text-soft">
                                <ul class="list-inline">
                                    <li>Project ID: <span class="text-base">#PRJ-{{ str_pad($project->id, 5, '0', STR_PAD_LEFT) }}</span></li>
                                    <li>Activated: <span class="text-base">{{ $project->created_at->format('d M, Y') }}</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('admin.projects.index') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back to List</span></a>
                            <a href="{{ route('admin.projects.index') }}" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->
                
                <div class="nk-block">
                    <div class="row g-gs">
                        <div class="col-lg-4 col-xl-4">
                            <div class="card card-bordered">
                                <div class="card-inner-group">
                                    <div class="card-inner">
                                        <div class="user-card user-card-s2">
                                            <div class="user-avatar lg bg-primary">
                                                <span>{{ strtoupper(substr($project->prospect->name, 0, 2)) }}</span>
                                            </div>
                                            <div class="user-info">
                                                <div class="badge badge-outline-light rounded-pill pill">Author</div>
                                                <h5>{{ $project->prospect->name }}</h5>
                                                <span class="sub-text">{{ $project->prospect->email }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-inner">
                                        <div class="overline-title-alt mb-3">Contact Information</div>
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <span class="sub-text">Phone:</span>
                                                <span>{{ $project->prospect->phone_number }}</span>
                                            </div>
                                            <div class="col-6">
                                                <span class="sub-text">Location:</span>
                                                <span>{{ $project->prospect->location ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-inner">
                                        <div class="overline-title-alt mb-3">Billing Status</div>
                                        <ul class="list-plain g-2">
                                            @if($project->invoice)
                                            <li>
                                                <span class="sub-text">Invoice ID:</span>
                                                <span><a href="{{ route('admin.invoices.show', $project->invoice->id) }}">#{{ $project->invoice->invoice_number }}</a></span>
                                            </li>
                                            <li>
                                                <span class="sub-text">Amount:</span>
                                                <span>₦{{ number_format($project->invoice->amount, 2) }}</span>
                                            </li>
                                            <li>
                                                <span class="sub-text">Payment:</span>
                                                <span class="badge badge-dim bg-success">Paid</span>
                                            </li>
                                            @else
                                            <li class="text-soft italic">No invoice record found.</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .col -->
                        
                        <div class="col-lg-8 col-xl-8">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <div class="nk-block-head nk-block-head-sm">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h6 class="title">Manuscript & Progress</h6>
                                            </div>
                                            <div class="nk-block-head-content">
                                                <span class="badge badge-dim bg-primary text-primary">{{ strtoupper($project->stage_label) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @php
                                        $stageProgress = [
                                            'project_recording' => 10,
                                            'manuscript_review' => 20,
                                            'book_cover_isbn' => 30,
                                            'editing' => 40,
                                            'formatting' => 50,
                                            'dummy_review' => 60,
                                            'printing' => 75,
                                            'distribution' => 85,
                                            'sales_promotion' => 95,
                                            'reviews' => 100
                                        ];
                                        $currentStage = $project->current_stage ?? 'project_recording';
                                        $progress = $stageProgress[$currentStage] ?? 0;
                                    @endphp
                                    
                                    <div class="project-progress mb-4">
                                        <div class="project-progress-details d-flex justify-content-between mb-1">
                                            <div class="project-progress-task text-soft small">Pipeline Progress</div>
                                            <div class="project-progress-percent fw-bold text-primary">{{ $progress }}%</div>
                                        </div>
                                        <div class="progress progress-lg">
                                            <div class="progress-bar bg-primary" data-progress="{{ $progress }}" style="width: {{ $progress }}%;"></div>
                                        </div>
                                    </div>

                                    <div class="nk-block">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="card card-bordered bg-light">
                                                    <div class="card-inner p-3">
                                                        <span class="overline-title text-soft">Genre</span>
                                                        <p class="mb-0 fw-bold">{{ $project->prospect->genre }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card card-bordered bg-light">
                                                    <div class="card-inner p-3">
                                                        <span class="overline-title text-soft">Word Count</span>
                                                        <p class="mb-0 fw-bold">{{ number_format($project->prospect->number_of_words) }} words</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4">
                                            <h6 class="overline-title text-soft">Manuscript Excerpt / Synopsis</h6>
                                            <div class="card card-bordered bg-lighter mt-2 text-dark" style="min-height: 150px;">
                                                <div class="card-inner">
                                                    {{ $project->prospect->manuscript_excerpt ?? 'No excerpt available.' }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <h6 class="overline-title text-soft">Deadline</h6>
                                            <p class="fw-bold {{ $project->estimated_completion_date && \Carbon\Carbon::parse($project->estimated_completion_date)->isPast() ? 'text-danger' : 'text-dark' }}">
                                                {{ $project->estimated_completion_date ? \Carbon\Carbon::parse($project->estimated_completion_date)->format('d F, Y') : 'Not Set' }}
                                            </p>
                                        </div>

                                        <div class="mt-4">
                                            <h6 class="overline-title text-soft">Files & Assets</h6>
                                            <div class="row g-3 mt-1">
                                                @if($project->prospect->manuscript_file_path)
                                                <div class="col-sm-6">
                                                    <div class="card card-bordered">
                                                        <div class="card-inner p-3">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="user-avatar bg-dim-primary text-primary me-3">
                                                                        <em class="icon ni ni-file-text"></em>
                                                                    </div>
                                                                    <div>
                                                                        <span class="lead-text">Manuscript</span>
                                                                        <span class="sub-text">Original Upload</span>
                                                                    </div>
                                                                </div>
                                                                <a href="{{ asset('storage/' . $project->prospect->manuscript_file_path) }}" target="_blank" class="btn btn-trigger btn-icon"><em class="icon ni ni-download-cloud"></em></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($project->prospect->cover_design_path)
                                                <div class="col-sm-6">
                                                    <div class="card card-bordered">
                                                        <div class="card-inner p-3">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="user-avatar bg-dim-info text-info me-3">
                                                                        <em class="icon ni ni-img"></em>
                                                                    </div>
                                                                    <div>
                                                                        <span class="lead-text">Cover Design</span>
                                                                        <span class="sub-text">Reference/Draft</span>
                                                                    </div>
                                                                </div>
                                                                <a href="{{ asset('storage/' . $project->prospect->cover_design_path) }}" target="_blank" class="btn btn-trigger btn-icon"><em class="icon ni ni-download-cloud"></em></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @if(!$project->prospect->manuscript_file_path && !$project->prospect->cover_design_path)
                                            <p class="text-soft italic mt-2">No files were uploaded with this submission.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .col -->
                    </div><!-- .row -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection
