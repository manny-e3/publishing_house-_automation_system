@extends('layouts.admin')

@section('title', 'Design & Typesetting Workspace')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Design & Typesetting Studio</h3>
                    <div class="nk-block-des text-soft">
                        <p>Production layout, interior formatting, cover artistry, and multi-format compilation (ePub, eBook PDF, Print Ready PDF, Amazon KDP).</p>
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
                        <h5 class="title">Active Design Workflows</h5>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Design Phase</th>
                                <th>Gate 1 Status</th>
                                <th>ePub</th>
                                <th>eBook PDF</th>
                                <th>Print Ready</th>
                                <th>Amazon KDP</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $project)
                            @php
                                $dummy3Done = !empty($project->checklist_progress['dummy_copy_editing_3']['completed']);
                                $hasRegression = !empty($project->metadata_file['regression_alert']);
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $project->book_title }}</strong><br>
                                    <small class="text-muted">{{ $project->genre }} &bull; {{ number_format($project->number_of_words) }} words</small>
                                </td>
                                <td>{{ $project->name }}</td>
                                <td>
                                    @if($hasRegression)
                                        <span class="badge bg-danger"><em class="icon ni ni-alert-fill"></em> _EDIT_REVISION</span>
                                    @else
                                        <span class="badge bg-outline-primary">{{ ucfirst(str_replace('_', ' ', $project->design_phase?->value ?? $project->design_phase ?? 'Queued')) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($dummy3Done)
                                        <span class="badge bg-success-dim text-success"><em class="icon ni ni-unlock"></em> Cleared</span>
                                    @else
                                        <span class="badge bg-warning-dim text-warning"><em class="icon ni ni-lock"></em> Locked (Gate 1)</span>
                                    @endif
                                </td>
                                <td>
                                    {!! $project->epub_file_url ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-light text-muted">Missing</span>' !!}
                                </td>
                                <td>
                                    {!! $project->ebook_pdf_url ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-light text-muted">Missing</span>' !!}
                                </td>
                                <td>
                                    {!! $project->print_ready_pdf_url ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-light text-muted">Missing</span>' !!}
                                </td>
                                <td>
                                    {!! $project->amazon_kdp_ready_url ? '<span class="badge bg-success">Uploaded</span>' : '<span class="badge bg-light text-muted">Missing</span>' !!}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('dashboard.design.show', $project->id) }}" class="btn btn-sm {{ $dummy3Done ? 'btn-primary' : 'btn-outline-secondary' }}">
                                        <em class="icon ni {{ $dummy3Done ? 'ni-color-palette' : 'ni-lock' }}"></em> Open Studio
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">No projects currently waiting for design layout.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

