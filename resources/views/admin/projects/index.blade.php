@extends('layouts.admin')

@section('title', 'Active Projects Dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Active Projects</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have total {{ $projects->total() }} active projects.</p>
                            </div>
                        </div>
                    </div>
                </div><!-- .nk-block-head -->

                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner p-0">
                                <table class="table table-tranx">
                                    <thead>
                                        <tr class="tb-tnx-head">
                                            <th class="tb-tnx-id"><span class="overline-title">Project Name</span></th>
                                            <th class="tb-tnx-info"><span class="overline-title">Project Lead</span></th>
                                            <!-- <th class="tb-tnx-info d-none d-sm-table-cell"><span class="overline-title">Team</span></th> -->
                                            <th class="tb-tnx-amount"><span class="overline-title">Progress</span></th>
                                            <th class="tb-tnx-status"><span class="overline-title">Deadline / Status</span></th>
                                            <th class="tb-tnx-action text-end"><span>&nbsp;</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                            $stageColors = [
                                                'project_recording' => 'light',
                                                'manuscript_review' => 'info',
                                                'book_cover_isbn' => 'primary',
                                                'editing' => 'warning',
                                                'formatting' => 'info',
                                                'dummy_review' => 'secondary',
                                                'printing' => 'dark',
                                                'distribution' => 'success',
                                                'sales_promotion' => 'info',
                                                'reviews' => 'success'
                                            ];
                                        @endphp
                                        @forelse($projects as $project)
                                        @php
                                            $currentStage = $project->current_stage ?? 'project_recording';
                                            $progress = $stageProgress[$currentStage] ?? 0;
                                            $color = $stageColors[$currentStage] ?? 'primary';
                                            $initials = collect(explode(' ', $project->prospect->book_title))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->join('');
                                        @endphp
                                        <tr class="tb-tnx-item" id="project-row-{{ $project->id }}">
                                            <td class="tb-tnx-id">
                                                <div class="project-title d-flex align-items-center">
                                                    <div class="user-avatar sm bg-{{ $color }} me-2">
                                                        <span>{{ $initials }}</span>
                                                    </div>
                                                    <span class="user-name fw-bold"><a href="{{ route('admin.projects.show', $project->id) }}">{{ $project->prospect->book_title }}</a></span>
                                                </div>
                                            </td>
                                            <td class="tb-tnx-info">
                                                <span class="text-soft small">{{ $project->prospect->name }}</span>
                                            </td>
                                            <td class="tb-tnx-amount">
                                                <div class="project-progress d-flex align-items-center">
                                                    <div class="progress progress-sm w-100 me-2" style="height: 6px;">
                                                        <div class="progress-bar bg-{{ $color }}" style="width: {{ $progress }}%;"></div>
                                                    </div>
                                                    <span class="small text-soft">{{ $progress }}%</span>
                                                </div>
                                            </td>
                                            <td class="tb-tnx-status">
                                                <span class="badge badge-dot bg-{{ $color }}">{{ $project->stage_label }}</span>
                                                @if($project->estimated_completion_date)
                                                    @php
                                                        $deadline = \Carbon\Carbon::parse($project->estimated_completion_date);
                                                        $diff = now()->diffInDays($deadline, false);
                                                    @endphp
                                                    <div class="text-soft fs-11px">
                                                        @if($diff < 0)
                                                            <span class="text-danger">Overdue by {{ abs($diff) }}d</span>
                                                        @else
                                                            <span>{{ $diff }} Days Left</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="tb-tnx-action text-end">
                                                <div class="dropdown">
                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-xs">
                                                        <ul class="link-list-plain">
                                                            <li><a href="{{ route('admin.projects.show', $project->id) }}">View Details</a></li>
                                                            <li><a href="javascript:void(0)" class="update-status-trigger" 
                                                                    data-id="{{ $project->id }}" 
                                                                    data-title="{{ $project->prospect->book_title }}" 
                                                                    data-status="{{ $currentStage }}">Change Stage</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                No active projects found.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($projects->hasPages())
                            <div class="card-inner">
                                {{ $projects->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>

<!-- Modal for Status Update -->
<div class="modal fade" tabindex="-1" id="updateStatusModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Project Status</h5>
                <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <form id="updateStatusForm">
                    <input type="hidden" id="modal-project-id">
                    <div class="form-group">
                        <label class="form-label">Project</label>
                        <div class="form-control-wrap">
                            <input type="text" class="form-control" id="modal-project-title" readonly>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label" for="modal-project-status">Current Stage</label>
                        <div class="form-control-wrap">
                            <select class="form-select js-select2" id="modal-project-status">
                                @foreach($stages as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-4 text-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="save-status-btn">Save Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    
    // Trigger Modal
    $('.update-status-trigger').on('click', function() {
        const id = $(this).data('id');
        const title = $(this).data('title');
        const status = $(this).data('status');
        
        $('#modal-project-id').val(id);
        $('#modal-project-title').val(title);
        $('#modal-project-status').val(status).trigger('change');
        
        modal.show();
    });

    // Handle Form Submit
    $('#updateStatusForm').on('submit', function(e) {
        e.preventDefault();
        
        const projectId = $('#modal-project-id').val();
        const stageKey = $('#modal-project-status').val();
        const saveBtn = $('#save-status-btn');
        
        saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span> Updating...</span>');

        fetch(`/admin/projects/${projectId}/stage`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ stage: stageKey })
        })
        .then(response => response.json())
        .then(data => {
            saveBtn.prop('disabled', false).text('Save Status');
            if (data.success) {
                modal.hide();
                
                Swal.fire({
                    icon: 'success',
                    title: 'Updated!',
                    text: 'Stage changed and author notified.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload(); // Refresh to update progress bars and badges correctly
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update stage.'
                });
            }
        })
        .catch(error => {
            saveBtn.prop('disabled', false).text('Save Status');
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred.'
            });
        });
    });
});
</script>
<style>
    .project-title .user-avatar { border-radius: 4px; }
    .tb-tnx-id { width: 40%; }
    .table-tranx thead th { background: #f5f6fa; border-top: none; }
</style>
@endpush
@endsection
