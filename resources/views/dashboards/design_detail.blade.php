@extends('layouts.admin')

@section('title', 'Design Studio: ' . $prospect->book_title)

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- Page Header with Breadcrumb and Phase Control -->
                <div class="nk-block-head nk-block-head-sm mb-4">
                    <div class="nk-block-between align-items-start">
                        <div class="nk-block-head-content">
                            <nav class="mb-2">
                                <ul class="breadcrumb breadcrumb-arrow">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard.design.index') }}"><em class="icon ni ni-arrow-left"></em> Design Studio</a></li>
                                    <li class="breadcrumb-item active">{{ $prospect->book_title }}</li>
                                </ul>
                            </nav>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <h3 class="nk-block-title page-title mb-0">{{ $prospect->book_title }}</h3>
                                @php
                                    $currentPhase = $prospect->design_phase?->value ?? $prospect->design_phase ?? 'queued';
                                    $phaseBadge = match($currentPhase) {
                                        'approved' => 'bg-success',
                                        'in_progress' => 'bg-info',
                                        'cover_pending_review', 'interior_pending_review' => 'bg-warning',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $phaseBadge }} text-white text-uppercase px-2 py-1">
                                    {{ ucfirst(str_replace('_', ' ', $currentPhase)) }}
                                </span>
                                @if($gate1Cleared)
                                    <span class="badge bg-success-dim text-success border border-success">
                                        <em class="icon ni ni-unlock me-1"></em> Gate 1 Cleared
                                    </span>
                                @else
                                    <span class="badge bg-danger-dim text-danger border border-danger">
                                        <em class="icon ni ni-lock me-1"></em> Gate 1 Locked
                                    </span>
                                @endif
                            </div>
                            <div class="nk-block-des text-soft mt-1">
                                <p class="mb-0">
                                    Author: <strong class="text-dark">{{ $prospect->name }}</strong>
                                    <span class="text-muted mx-1">&bull;</span> Model: <strong class="text-dark">{{ $prospect->isTraditional() ? 'Traditional' : 'Osisi Hybrid' }}</strong>
                                    <span class="text-muted mx-1">&bull;</span> Word Count: <strong class="text-dark">{{ number_format($prospect->number_of_words) }}</strong>
                                    <span class="text-muted mx-1">&bull;</span> ISBN: <strong class="text-dark">{{ $prospect->isbn_code ?? 'Pending Registration' }}</strong>
                                </p>
                            </div>
                        </div>
                        <div class="nk-block-head-content d-flex align-items-center gap-2 mt-2 mt-sm-0">
                            <!-- Phase Update Dropdown -->
                            <form action="{{ route('dashboard.design.phase.update', $prospect->id) }}" method="POST" class="d-flex align-items-center gap-2">
                                @csrf
                                <label class="form-label small text-muted mb-0 text-nowrap">Production Phase:</label>
                                <select name="design_phase" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="queued" {{ $currentPhase == 'queued' ? 'selected' : '' }}>Queued</option>
                                    <option value="in_progress" {{ $currentPhase == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="cover_pending_review" {{ $currentPhase == 'cover_pending_review' ? 'selected' : '' }}>Cover Pending Review</option>
                                    <option value="interior_pending_review" {{ $currentPhase == 'interior_pending_review' ? 'selected' : '' }}>Interior Pending Review</option>
                                    <option value="approved" {{ $currentPhase == 'approved' ? 'selected' : '' }}>Approved (Ready for Print)</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-icon mb-4 border border-success">
                        <em class="icon ni ni-check-circle"></em> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-icon mb-4 border border-danger">
                        <em class="icon ni ni-alert-circle"></em> {{ session('error') }}
                    </div>
                @endif

                <!-- Gate 2: Regression Guard Alert Banner -->
                @if($regressionAlert)
                    <div class="alert alert-danger alert-icon mb-4 border border-danger shadow-sm">
                        <div class="d-flex justify-content-between align-items-center w-100 flex-wrap gap-2">
                            <div>
                                <em class="icon ni ni-alert-fill fs-3 text-danger me-2"></em>
                                <strong>Gate 2 Regression Warning:</strong> An editor modified or reopened Dummy Copy Editing 2 or 3 after design had begun.
                                File status has reverted to <code>in_progress</code> with tag <code>_EDIT_REVISION</code>.
                                <div class="small mt-1 text-muted">Reason: {{ $prospect->metadata_file['regression_reason'] ?? 'Editorial modification' }} at {{ $prospect->metadata_file['regression_timestamp'] ?? '' }}</div>
                            </div>
                            <div>
                                <form action="{{ route('dashboard.design.regression.acknowledge', $prospect->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger bg-white">
                                        <em class="icon ni ni-check"></em> Acknowledge & Clear Alert
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Gate 1: Design Clearance Banner -->
                @if(!$gate1Cleared)
                    <div class="alert alert-warning alert-icon mb-4 border border-warning shadow-sm">
                        <em class="icon ni ni-lock fs-3 text-warning me-2"></em>
                        <div>
                            <strong class="d-block">Gate 1 Access Gate: Production Dropzones Locked</strong>
                            <p class="mb-0 mt-1 small">
                                This manuscript is currently undergoing editorial review. In accordance with strict publishing house protocol, production typesetting and asset compilation cannot begin until the Editorial Team verifies <strong>100% of Dummy Copy Editing 3</strong>.
                            </p>
                        </div>
                    </div>
                @endif

                @php
                    $readyAssetsCount = ($prospect->epub_file_url ? 1 : 0) +
                                        ($prospect->ebook_pdf_url ? 1 : 0) +
                                        ($prospect->print_ready_pdf_url ? 1 : 0) +
                                        ($prospect->amazon_kdp_ready_url ? 1 : 0) +
                                        ($prospect->cover_design_path ? 1 : 0);
                    $readyPercentage = round(($readyAssetsCount / 5) * 100);
                @endphp

                <div class="row g-gs">
                    <!-- Left Column: Production Multi-Format Dropzones -->
                    <div class="col-lg-7">
                        <div class="card card-bordered mb-4">
                            <div class="card-inner">
                                <div class="card-title-group mb-3 pb-2 border-bottom">
                                    <div class="card-title">
                                        <h5 class="title mb-1">Production Multi-Format Dropzones</h5>
                                        <p class="text-muted small mb-0">Typeset and package all 5 publication targets for distribution and print release.</p>
                                    </div>
                                    <div class="card-tools text-end">
                                        <span class="badge {{ $readyAssetsCount === 5 ? 'bg-success' : 'bg-primary' }} text-white px-2 py-1">
                                            {{ $readyAssetsCount }} of 5 Ready ({{ $readyPercentage }}%)
                                        </span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="progress progress-sm mb-4 bg-light">
                                    <div class="progress-bar bg-{{ $readyAssetsCount === 5 ? 'success' : 'primary' }}" role="progressbar" style="width: {{ $readyPercentage }}%" aria-valuenow="{{ $readyPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <!-- 1. ePub Slot -->
                                <div class="p-3 mb-3 border rounded-3 bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <em class="icon ni ni-book text-primary fs-4"></em>
                                            <h6 class="mb-0 text-dark fw-bold">1. ePub E-Reader Format</h6>
                                            <span class="badge bg-primary-dim text-primary">Reflowable</span>
                                        </div>
                                        <div>
                                            @if($prospect->epub_file_url)
                                                <span class="badge bg-success text-white px-2 py-1"><em class="icon ni ni-check-circle me-1"></em> Ready</span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2 py-1"><em class="icon ni ni-clock me-1"></em> Awaiting Upload</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        Target for Apple Books, Google Play, Kobo & Selar. <span class="text-primary fw-medium">(Required for Gate 3 Selar Release)</span>
                                    </p>

                                    @if($prospect->epub_file_url)
                                        <div class="bg-success-dim border border-success-subtle p-2 rounded d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center text-truncate me-2">
                                                <em class="icon ni ni-check-circle-fill text-success fs-4 me-2"></em>
                                                <div class="text-truncate">
                                                    <span class="fw-bold small text-dark d-block text-truncate">{{ basename($prospect->epub_file_url) }}</span>
                                                    <span class="text-success small" style="font-size: 11px;">Verified production artifact ready for release</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 flex-shrink-0">
                                                <a href="{{ asset($prospect->epub_file_url) }}" class="btn btn-xs btn-outline-success bg-white" target="_blank" download>
                                                    <em class="icon ni ni-download me-1"></em> Download
                                                </a>
                                                @if($gate1Cleared)
                                                    <button type="button" class="btn btn-xs btn-outline-secondary bg-white" data-bs-toggle="collapse" data-bs-target="#replace-epub">
                                                        <em class="icon ni ni-reload me-1"></em> Replace
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        @if($gate1Cleared)
                                            <div class="collapse mt-2" id="replace-epub">
                                                <form action="{{ route('dashboard.design.asset.upload', $prospect->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="asset_type" value="epub">
                                                    <div class="row g-2 align-items-center">
                                                        <div class="col-sm col-12">
                                                            <input type="file" name="file" class="form-control form-control-sm" accept=".epub" required>
                                                        </div>
                                                        <div class="col-sm-auto col-12">
                                                            <button type="submit" class="btn btn-sm btn-primary w-100 text-nowrap">
                                                                <em class="icon ni ni-upload-cloud me-1"></em> Upload Replacement
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        @if($gate1Cleared)
                                            <form action="{{ route('dashboard.design.asset.upload', $prospect->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="asset_type" value="epub">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-sm col-12">
                                                        <input type="file" name="file" class="form-control form-control-sm" accept=".epub" required>
                                                    </div>
                                                    <div class="col-sm-auto col-12">
                                                        <button type="submit" class="btn btn-sm btn-primary w-100 text-nowrap">
                                                            <em class="icon ni ni-upload-cloud me-1"></em> Upload ePub
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="text-muted small mt-1" style="font-size: 11px;">
                                                    Accepted format: <code>.epub</code> &bull; Max file size: 100MB
                                                </div>
                                            </form>
                                        @else
                                            <div class="bg-light p-2 rounded text-muted small d-flex align-items-center">
                                                <em class="icon ni ni-lock me-2 text-warning fs-5"></em>
                                                <span>Upload locked until Gate 1 cleared (Editorial Dummy Copy 3).</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <!-- 2. eBook PDF Slot -->
                                <div class="p-3 mb-3 border rounded-3 bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <em class="icon ni ni-file-pdf text-info fs-4"></em>
                                            <h6 class="mb-0 text-dark fw-bold">2. Tablet eBook PDF</h6>
                                            <span class="badge bg-info-dim text-info">Fixed Layout</span>
                                        </div>
                                        <div>
                                            @if($prospect->ebook_pdf_url)
                                                <span class="badge bg-success text-white px-2 py-1"><em class="icon ni ni-check-circle me-1"></em> Ready</span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2 py-1"><em class="icon ni ni-clock me-1"></em> Awaiting Upload</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        High-res vector typography formatted for iPad, tablets & screen reading.
                                    </p>

                                    @if($prospect->ebook_pdf_url)
                                        <div class="bg-success-dim border border-success-subtle p-2 rounded d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center text-truncate me-2">
                                                <em class="icon ni ni-check-circle-fill text-success fs-4 me-2"></em>
                                                <div class="text-truncate">
                                                    <span class="fw-bold small text-dark d-block text-truncate">{{ basename($prospect->ebook_pdf_url) }}</span>
                                                    <span class="text-success small" style="font-size: 11px;">Verified tablet proof ready for distribution</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 flex-shrink-0">
                                                <a href="{{ asset($prospect->ebook_pdf_url) }}" class="btn btn-xs btn-outline-success bg-white" target="_blank" download>
                                                    <em class="icon ni ni-download me-1"></em> Download
                                                </a>
                                                @if($gate1Cleared)
                                                    <button type="button" class="btn btn-xs btn-outline-secondary bg-white" data-bs-toggle="collapse" data-bs-target="#replace-ebook-pdf">
                                                        <em class="icon ni ni-reload me-1"></em> Replace
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        @if($gate1Cleared)
                                            <div class="collapse mt-2" id="replace-ebook-pdf">
                                                <form action="{{ route('dashboard.design.asset.upload', $prospect->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="asset_type" value="ebook_pdf">
                                                    <div class="row g-2 align-items-center">
                                                        <div class="col-sm col-12">
                                                            <input type="file" name="file" class="form-control form-control-sm" accept=".pdf" required>
                                                        </div>
                                                        <div class="col-sm-auto col-12">
                                                            <button type="submit" class="btn btn-sm btn-primary w-100 text-nowrap">
                                                                <em class="icon ni ni-upload-cloud me-1"></em> Upload Replacement
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        @if($gate1Cleared)
                                            <form action="{{ route('dashboard.design.asset.upload', $prospect->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="asset_type" value="ebook_pdf">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-sm col-12">
                                                        <input type="file" name="file" class="form-control form-control-sm" accept=".pdf" required>
                                                    </div>
                                                    <div class="col-sm-auto col-12">
                                                        <button type="submit" class="btn btn-sm btn-primary w-100 text-nowrap">
                                                            <em class="icon ni ni-upload-cloud me-1"></em> Upload PDF
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="text-muted small mt-1" style="font-size: 11px;">
                                                    Accepted format: <code>.pdf</code> &bull; Max file size: 100MB
                                                </div>
                                            </form>
                                        @else
                                            <div class="bg-light p-2 rounded text-muted small d-flex align-items-center">
                                                <em class="icon ni ni-lock me-2 text-warning fs-5"></em>
                                                <span>Upload locked until Gate 1 cleared.</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <!-- 3. Print Ready PDF Slot -->
                                <div class="p-3 mb-3 border rounded-3 bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <em class="icon ni ni-printer text-secondary fs-4"></em>
                                            <h6 class="mb-0 text-dark fw-bold">3. Print Ready PDF</h6>
                                            <span class="badge bg-secondary-dim text-secondary">Press Blueprint</span>
                                        </div>
                                        <div>
                                            @if($prospect->print_ready_pdf_url)
                                                <span class="badge bg-success text-white px-2 py-1"><em class="icon ni ni-check-circle me-1"></em> Ready</span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2 py-1"><em class="icon ni ni-clock me-1"></em> Awaiting Upload</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        Trim-sized with 0.125" bleed, crop marks & CMYK color space for physical offset press.
                                    </p>

                                    @if($prospect->print_ready_pdf_url)
                                        <div class="bg-success-dim border border-success-subtle p-2 rounded d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center text-truncate me-2">
                                                <em class="icon ni ni-check-circle-fill text-success fs-4 me-2"></em>
                                                <div class="text-truncate">
                                                    <span class="fw-bold small text-dark d-block text-truncate">{{ basename($prospect->print_ready_pdf_url) }}</span>
                                                    <span class="text-success small" style="font-size: 11px;">Verified press blueprint ready for physical print</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 flex-shrink-0">
                                                <a href="{{ asset($prospect->print_ready_pdf_url) }}" class="btn btn-xs btn-outline-success bg-white" target="_blank" download>
                                                    <em class="icon ni ni-download me-1"></em> Download
                                                </a>
                                                @if($gate1Cleared)
                                                    <button type="button" class="btn btn-xs btn-outline-secondary bg-white" data-bs-toggle="collapse" data-bs-target="#replace-print-pdf">
                                                        <em class="icon ni ni-reload me-1"></em> Replace
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        @if($gate1Cleared)
                                            <div class="collapse mt-2" id="replace-print-pdf">
                                                <form action="{{ route('dashboard.design.asset.upload', $prospect->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="asset_type" value="print_ready_pdf">
                                                    <div class="row g-2 align-items-center">
                                                        <div class="col-sm col-12">
                                                            <input type="file" name="file" class="form-control form-control-sm" accept=".pdf" required>
                                                        </div>
                                                        <div class="col-sm-auto col-12">
                                                            <button type="submit" class="btn btn-sm btn-primary w-100 text-nowrap">
                                                                <em class="icon ni ni-upload-cloud me-1"></em> Upload Replacement
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        @if($gate1Cleared)
                                            <form action="{{ route('dashboard.design.asset.upload', $prospect->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="asset_type" value="print_ready_pdf">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-sm col-12">
                                                        <input type="file" name="file" class="form-control form-control-sm" accept=".pdf" required>
                                                    </div>
                                                    <div class="col-sm-auto col-12">
                                                        <button type="submit" class="btn btn-sm btn-primary w-100 text-nowrap">
                                                            <em class="icon ni ni-upload-cloud me-1"></em> Upload Press PDF
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="text-muted small mt-1" style="font-size: 11px;">
                                                    Accepted format: <code>.pdf</code> (Trim & Bleed verified) &bull; Max: 100MB
                                                </div>
                                            </form>
                                        @else
                                            <div class="bg-light p-2 rounded text-muted small d-flex align-items-center">
                                                <em class="icon ni ni-lock me-2 text-warning fs-5"></em>
                                                <span>Upload locked until Gate 1 cleared.</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <!-- 4. Amazon Ready KDP Package Slot -->
                                <div class="p-3 mb-3 border rounded-3 bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <em class="icon ni ni-package text-warning fs-4"></em>
                                            <h6 class="mb-0 text-dark fw-bold">4. Amazon Ready KDP Package</h6>
                                            <span class="badge bg-warning-dim text-warning">Kindle Target</span>
                                        </div>
                                        <div>
                                            @if($prospect->amazon_kdp_ready_url)
                                                <span class="badge bg-success text-white px-2 py-1"><em class="icon ni ni-check-circle me-1"></em> Ready</span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2 py-1"><em class="icon ni ni-clock me-1"></em> Awaiting Upload</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        Packaged KDP target format for direct Amazon syndication. <span class="text-warning fw-medium">(Required for Gate 3 Amazon Release)</span>
                                    </p>

                                    @if($prospect->amazon_kdp_ready_url)
                                        <div class="bg-success-dim border border-success-subtle p-2 rounded d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center text-truncate me-2">
                                                <em class="icon ni ni-check-circle-fill text-success fs-4 me-2"></em>
                                                <div class="text-truncate">
                                                    <span class="fw-bold small text-dark d-block text-truncate">{{ basename($prospect->amazon_kdp_ready_url) }}</span>
                                                    <span class="text-success small" style="font-size: 11px;">Verified Amazon syndication bundle</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 flex-shrink-0">
                                                <a href="{{ asset($prospect->amazon_kdp_ready_url) }}" class="btn btn-xs btn-outline-success bg-white" target="_blank" download>
                                                    <em class="icon ni ni-download me-1"></em> Download
                                                </a>
                                                @if($gate1Cleared)
                                                    <button type="button" class="btn btn-xs btn-outline-secondary bg-white" data-bs-toggle="collapse" data-bs-target="#replace-kdp">
                                                        <em class="icon ni ni-reload me-1"></em> Replace
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        @if($gate1Cleared)
                                            <div class="collapse mt-2" id="replace-kdp">
                                                <form action="{{ route('dashboard.design.asset.upload', $prospect->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="asset_type" value="amazon_kdp">
                                                    <div class="row g-2 align-items-center">
                                                        <div class="col-sm col-12">
                                                            <input type="file" name="file" class="form-control form-control-sm" accept=".kpf,.mobi,.zip,.pdf" required>
                                                        </div>
                                                        <div class="col-sm-auto col-12">
                                                            <button type="submit" class="btn btn-sm btn-primary w-100 text-nowrap">
                                                                <em class="icon ni ni-upload-cloud me-1"></em> Upload Replacement
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        @if($gate1Cleared)
                                            <form action="{{ route('dashboard.design.asset.upload', $prospect->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="asset_type" value="amazon_kdp">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-sm col-12">
                                                        <input type="file" name="file" class="form-control form-control-sm" accept=".kpf,.mobi,.zip,.pdf" required>
                                                    </div>
                                                    <div class="col-sm-auto col-12">
                                                        <button type="submit" class="btn btn-sm btn-primary w-100 text-nowrap">
                                                            <em class="icon ni ni-upload-cloud me-1"></em> Upload KDP
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="text-muted small mt-1" style="font-size: 11px;">
                                                    Accepted formats: <code>.kpf, .mobi, .zip, .pdf</code> &bull; Max: 100MB
                                                </div>
                                            </form>
                                        @else
                                            <div class="bg-light p-2 rounded text-muted small d-flex align-items-center">
                                                <em class="icon ni ni-lock me-2 text-warning fs-5"></em>
                                                <span>Upload locked until Gate 1 cleared.</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <!-- 5. High-Res Cover Artwork Slot -->
                                <div class="p-3 border rounded-3 bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <em class="icon ni ni-img text-success fs-4"></em>
                                            <h6 class="mb-0 text-dark fw-bold">5. Full Cover Artwork</h6>
                                            <span class="badge bg-success-dim text-success">300 DPI CMYK</span>
                                        </div>
                                        <div>
                                            @if($prospect->cover_design_path)
                                                <span class="badge bg-success text-white px-2 py-1"><em class="icon ni ni-check-circle me-1"></em> Ready</span>
                                            @else
                                                <span class="badge bg-light text-muted border px-2 py-1"><em class="icon ni ni-clock me-1"></em> Awaiting Upload</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        300 DPI high-resolution CMYK jacket image spanning front cover, spine, and back cover.
                                    </p>

                                    @if($prospect->cover_design_path)
                                        <div class="bg-success-dim border border-success-subtle p-2 rounded d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center text-truncate me-2">
                                                <em class="icon ni ni-check-circle-fill text-success fs-4 me-2"></em>
                                                <div class="text-truncate">
                                                    <span class="fw-bold small text-dark d-block text-truncate">{{ basename($prospect->cover_design_path) }}</span>
                                                    <span class="text-success small" style="font-size: 11px;">Verified 300 DPI jacket print artwork</span>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 flex-shrink-0">
                                                <a href="{{ asset($prospect->cover_design_path) }}" class="btn btn-xs btn-outline-success bg-white" target="_blank">
                                                    <em class="icon ni ni-eye me-1"></em> View Cover
                                                </a>
                                                @if($gate1Cleared)
                                                    <button type="button" class="btn btn-xs btn-outline-secondary bg-white" data-bs-toggle="collapse" data-bs-target="#replace-cover">
                                                        <em class="icon ni ni-reload me-1"></em> Replace
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        @if($gate1Cleared)
                                            <div class="collapse mt-2" id="replace-cover">
                                                <form action="{{ route('dashboard.design.asset.upload', $prospect->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="hidden" name="asset_type" value="cover_image">
                                                    <div class="row g-2 align-items-center">
                                                        <div class="col-sm col-12">
                                                            <input type="file" name="file" class="form-control form-control-sm" accept="image/*,.pdf" required>
                                                        </div>
                                                        <div class="col-sm-auto col-12">
                                                            <button type="submit" class="btn btn-sm btn-primary w-100 text-nowrap">
                                                                <em class="icon ni ni-upload-cloud me-1"></em> Upload Replacement
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        @if($gate1Cleared)
                                            <form action="{{ route('dashboard.design.asset.upload', $prospect->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="asset_type" value="cover_image">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-sm col-12">
                                                        <input type="file" name="file" class="form-control form-control-sm" accept="image/*,.pdf" required>
                                                    </div>
                                                    <div class="col-sm-auto col-12">
                                                        <button type="submit" class="btn btn-sm btn-primary w-100 text-nowrap">
                                                            <em class="icon ni ni-upload-cloud me-1"></em> Upload Cover
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="text-muted small mt-1" style="font-size: 11px;">
                                                    Accepted formats: <code>.jpg, .jpeg, .png, .pdf</code> (300 DPI) &bull; Max: 100MB
                                                </div>
                                            </form>
                                        @else
                                            <div class="bg-light p-2 rounded text-muted small d-flex align-items-center">
                                                <em class="icon ni ni-lock me-2 text-warning fs-5"></em>
                                                <span>Upload locked until Gate 1 cleared.</span>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Proof Annotation Stream & Project Reference -->
                    <div class="col-lg-5">
                        <!-- Proof Annotation Stream -->
                        <div class="card card-bordered mb-4">
                            <div class="card-inner">
                                <div class="card-title-group mb-2">
                                    <div class="card-title">
                                        <h5 class="title mb-1">Live Proof Annotation Stream</h5>
                                    </div>
                                    <div class="card-tools">
                                        <span class="badge bg-light text-primary border">
                                            {{ count($annotations) }} Note{{ count($annotations) === 1 ? '' : 's' }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-muted small mb-3">
                                    Submit and track timestamped feedback pins on interior layouts, typography, and cover proofs.
                                </p>

                                <!-- Add Annotation Form -->
                                <form action="{{ route('dashboard.design.annotate', $prospect->id) }}" method="POST" class="mb-4 p-3 bg-lighter rounded border">
                                    @csrf
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="form-label small text-muted mb-1">Page / Spread #</label>
                                            <input type="number" name="page_number" class="form-control form-control-sm" placeholder="e.g. 42 (Optional)" min="1">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small text-muted mb-1">Target Category</label>
                                            <select name="format" class="form-select form-select-sm">
                                                <option value="interior_proof">Interior Proof</option>
                                                <option value="cover_proof">Cover Artwork</option>
                                                <option value="epub_format">ePub / Mobile</option>
                                                <option value="general">General Layout</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted mb-1">Annotation Feedback</label>
                                        <textarea name="note" class="form-control form-control-sm" rows="3" placeholder="e.g. Increase gutter margin on page 42, fix widow line in Chapter 3..." required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><em class="icon ni ni-user-circle me-1"></em> By {{ Auth::user()->name ?? 'Designer' }}</small>
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <em class="icon ni ni-send me-1"></em> Pin Note
                                        </button>
                                    </div>
                                </form>

                                <!-- Annotation Feed -->
                                <div class="timeline border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
                                    @forelse(array_reverse($annotations) as $ann)
                                        <div class="p-3 mb-2 bg-white rounded border-start border-3 border-primary shadow-sm">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold small text-dark">
                                                    <em class="icon ni ni-user-alt text-primary me-1"></em>{{ $ann['user_name'] ?? 'Team Member' }}
                                                    <span class="badge bg-light text-secondary ms-1">{{ ucfirst($ann['role'] ?? 'staff') }}</span>
                                                </span>
                                                <span class="text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($ann['created_at'])->diffForHumans() }}</span>
                                            </div>
                                            <div class="mb-1">
                                                @if(!empty($ann['page_number']))
                                                    <span class="badge bg-primary-dim text-primary border border-primary-subtle" style="font-size: 11px;">
                                                        Page {{ $ann['page_number'] }}
                                                    </span>
                                                @endif
                                                <span class="badge bg-secondary-dim text-secondary" style="font-size: 11px;">
                                                    {{ ucfirst(str_replace('_', ' ', $ann['format'] ?? 'general')) }}
                                                </span>
                                            </div>
                                            <p class="mb-0 small text-dark" style="white-space: pre-wrap;">{{ $ann['note'] }}</p>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-muted small">
                                            <em class="icon ni ni-notes fs-1 text-soft d-block mb-2"></em>
                                            No proof annotations recorded yet. Pin the first note above!
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Source Manuscript & Metadata Reference -->
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <h6 class="title mb-3">Typesetting Source Reference</h6>
                                <table class="table table-sm table-borderless small mb-3">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted ps-0">Working Title:</td>
                                            <td class="fw-medium text-end">{{ $prospect->book_title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Author:</td>
                                            <td class="fw-medium text-end">{{ $prospect->name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Total Word Count:</td>
                                            <td class="fw-medium text-end">{{ number_format($prospect->number_of_words) }} words</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Publishing Route:</td>
                                            <td class="fw-medium text-end">{{ $prospect->isTraditional() ? 'Traditional' : 'Osisi Hybrid' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted ps-0">Assigned ISBN:</td>
                                            <td class="fw-medium text-end">{{ $prospect->isbn_code ?? 'Not yet logged' }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                @if($prospect->manuscript_path)
                                    <a href="{{ asset($prospect->manuscript_path) }}" class="btn btn-outline-primary btn-sm w-100 mb-2" target="_blank" download>
                                        <em class="icon ni ni-file-text me-1"></em> Download Raw Manuscript
                                    </a>
                                @endif
                                @if($prospect->sample_chapter_path)
                                    <a href="{{ asset($prospect->sample_chapter_path) }}" class="btn btn-outline-secondary btn-sm w-100" target="_blank" download>
                                        <em class="icon ni ni-file-code me-1"></em> Download Sample Chapter
                                    </a>
                                @endif
                                @if(!$prospect->manuscript_path && !$prospect->sample_chapter_path)
                                    <div class="alert alert-light p-2 small text-muted text-center mb-0">
                                        <em class="icon ni ni-info me-1"></em> Manuscript submitted via direct editorial intake.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
