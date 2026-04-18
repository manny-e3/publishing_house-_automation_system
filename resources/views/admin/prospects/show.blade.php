@extends('layouts.admin')

@section('title', 'Review Manuscript')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Manuscript Review / <strong class="text-primary small">{{ $prospect->book_title }}</strong></h3>
                            <div class="nk-block-des text-soft">
                                <ul class="list-inline">
                                    <li>Author: <span class="text-base">{{ $prospect->name }}</span></li>
                                    <li>Submitted: <span class="text-base">{{ $prospect->created_at->format('d M, Y h:i A') }}</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('admin.prospects.index') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="row g-gs">
                        <div class="col-lg-8">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <div class="card-head">
                                        <h5 class="title">Manuscript Details</h5>
                                    </div>
                                    <div class="profile-ud-list">
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Book Title</span>
                                                <span class="profile-ud-value">{{ $prospect->book_title }}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Genre</span>
                                                <span class="profile-ud-value">{{ Str::title($prospect->genre) }}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Stage</span>
                                                <span class="profile-ud-value">{{ Str::title(str_replace('_', ' ', $prospect->stage_of_manuscript)) }}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Word Count</span>
                                                <span class="profile-ud-value">{{ number_format($prospect->number_of_words) }} Words</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Est. Investment</span>
                                                <span class="profile-ud-value fw-bold text-success">₦{{ number_format($prospect->estimated_cost, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item w-100">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Services Required</span>
                                                <span class="profile-ud-value">
                                                    @if($prospect->quote_for_services)
                                                        @foreach($prospect->quote_for_services as $service)
                                                            <span class="badge badge-dim bg-outline-primary text-uppercase">{{ $service }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-soft">Default (Editing only)</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-inner border-top">
                                    <div class="card-head">
                                        <h5 class="title">Submitted Assets</h5>
                                    </div>
                                    <ul class="d-flex flex-wrap" style="gap: 1rem;">
                                        @if($prospect->manuscript_file_path)
                                        <li>
                                            <a href="{{ asset('storage/' . $prospect->manuscript_file_path) }}" target="_blank" class="btn btn-outline-primary"><em class="icon ni ni-file-docs"></em><span>Download Excerpt</span></a>
                                        </li>
                                        @endif
                                        @if($prospect->cover_design_path)
                                        <li>
                                            <a href="{{ asset('storage/' . $prospect->cover_design_path) }}" target="_blank" class="btn btn-outline-secondary"><em class="icon ni ni-img"></em><span>View Cover Idea</span></a>
                                        </li>
                                        @endif
                                        @if(!$prospect->manuscript_file_path && !$prospect->cover_design_path)
                                        <li><span class="text-soft">No files were uploaded.</span></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="card card-bordered mt-4">
                                <div class="card-inner">
                                    <div class="card-head">
                                        <h5 class="title">Author Information</h5>
                                    </div>
                                    <div class="profile-ud-list">
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Full Name</span>
                                                <span class="profile-ud-value">{{ $prospect->name }}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Email Address</span>
                                                <span class="profile-ud-value">{{ $prospect->email }}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Phone Number</span>
                                                <span class="profile-ud-value">{{ $prospect->phone_number }}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">Agreement Signed By</span>
                                                <span class="profile-ud-value">{{ $prospect->agreement_name }}</span>
                                            </div>
                                        </div>
                                        <div class="profile-ud-item">
                                            <div class="profile-ud wider">
                                                <span class="profile-ud-label">IP Address</span>
                                                <span class="profile-ud-value text-soft">{{ $prospect->ip_address }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card card-bordered h-100">
                                <div class="card-inner">
                                    <div class="card-head">
                                        <h5 class="title">Acquisitions Review</h5>
                                    </div>
                                    <p class="text-soft mb-4">Evaluate the manuscript excerpt against our baseline criteria.</p>
                                    
                                    @if($prospect->status == 'prospect')
                                    <form action="{{ route('admin.prospects.status', $prospect->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <ul class="custom-control-group g-3 mb-4">
                                            @foreach($criteria as $item)
                                            <li>
                                                <div class="custom-control custom-checkbox custom-control-pro">
                                                    <input type="checkbox" name="evaluation[{{ $item->id }}][passed]" class="custom-control-input" id="chk-{{ $item->id }}">
                                                    <label class="custom-control-label" for="chk-{{ $item->id }}">
                                                        <span class="text-dark">{{ $item->label }}</span> 
                                                        <br><small class="text-soft">{{ $item->description }}</small>
                                                    </label>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>

                                        @if($criteria->isEmpty())
                                            <p class="text-soft italic fs-13px mb-4">No specific criteria configured. You may proceed with the decision below.</p>
                                        @endif

                                        <div class="form-group mb-4">
                                            <label class="form-label" for="reviewer_notes">Internal Notes (Optional)</label>
                                            <div class="form-control-wrap">
                                                <textarea class="form-control no-resize" name="reviewer_notes" id="reviewer_notes" placeholder="Internal remarks..." rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="d-flex" style="gap: 0.5rem;">
                                            <button type="submit" name="status" value="accepted" class="btn btn-success fw-bold flex-grow-1"><em class="icon ni ni-check-circle"></em><span>Accept Manuscript</span></button>
                                            <button type="button" class="btn btn-outline-danger btn-icon" data-bs-toggle="modal" data-bs-target="#rejectModal"><em class="icon ni ni-cross-circle"></em></button>
                                        </div>
                                    </form>
                                    @else
                                    <div class="alert alert-{{ $prospect->status == 'accepted' ? 'success' : 'danger' }} alert-icon">
                                        <em class="icon ni ni-{{ $prospect->status == 'accepted' ? 'check' : 'cross' }}-circle"></em> 
                                        This manuscript was <strong>{{ strtoupper($prospect->status) }}</strong>.
                                    </div>
                                    @endif
                                    
                                    <!-- Modal Alert Reject -->
                                    <div class="modal fade" tabindex="-1" id="rejectModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body modal-body-lg text-center">
                                                    <div class="nk-modal">
                                                        <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                                                        <h4 class="nk-modal-title">Reject Manuscript?</h4>
                                                        <div class="nk-modal-text">
                                                            <p class="lead">Are you sure you want to officially reject this manuscript from {{ $prospect->name }}? This action will mark it closed.</p>
                                                        </div>
                                                        <form action="{{ route('admin.prospects.status', $prospect->id) }}" method="POST" class="mt-4">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="nk-modal-action d-flex justify-content-center" style="gap:0.5rem;">
                                                                <button type="submit" name="status" value="rejected" class="btn btn-lg btn-mw btn-danger">Yes, Reject</button>
                                                                <a href="#" class="btn btn-lg btn-mw btn-light" data-bs-dismiss="modal">Cancel</a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
