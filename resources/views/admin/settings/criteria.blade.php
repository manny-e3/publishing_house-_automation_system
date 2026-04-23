@extends('layouts.admin')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Review Criteria Manager</h3>
                            <div class="nk-block-des text-soft">
                                <p>Customize the checklist used by editorial staff during manuscript assessment.</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCriterion">
                                <em class="icon ni ni-plus"></em><span>Add Criterion</span>
                            </button>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-icon">
                        <em class="icon ni ni-check-circle"></em> {{ session('success') }}
                    </div>
                @endif

                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner">
                                <table class="datatable-init nk-tb-list nk-tb-ulist table" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col"><span class="sub-text">#</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Label</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Description</span></th>
                                            <th class="nk-tb-col"><span class="sub-text">Status</span></th>
                                            <th class="nk-tb-col nk-tb-col-tools text-end"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($criteria as $item)
                                        <tr class="nk-tb-item">
                                            <td class="nk-tb-col">
                                                <span>{{ $loop->iteration }}</span>
                                            </td>
                                            <td class="nk-tb-col">
                                                <div class="user-card">
                                                    <div class="user-info">
                                                        <span class="tb-lead">{{ $item->label }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col">
                                                <span class="text-soft">{{ $item->description }}</span>
                                            </td>
                                            <td class="nk-tb-col">
                                                <span class="badge badge-dot {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#editCriterion{{ $item->id }}"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                                                    <li>
                                                                        <form action="{{ route('admin.settings.criteria.delete', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <a href="#" onclick="event.preventDefault(); if(confirm('Are you sure?')) document.getElementById('delete-form-{{ $item->id }}').submit();" class="text-danger"><em class="icon ni ni-trash"></em><span>Remove</span></a>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addCriterion">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Review Criterion</h5>
                <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <form action="{{ route('admin.settings.criteria.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Criterion Label</label>
                        <input type="text" name="label" class="form-control" required placeholder="e.g. Grammatical Accuracy">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control no-resize"></textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="is_active" value="1" id="add_is_active" checked>
                            <label class="custom-control-label" for="add_is_active">Active Status</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary">Save Criterion</button>
                </div>
            </form>
        </div>
    </div>
</div>
@foreach($criteria as $item)
<!-- Edit Modal -->
<div class="modal fade" id="editCriterion{{ $item->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Review Criterion</h5>
                <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <form action="{{ route('admin.settings.criteria.update', $item->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Criterion Label</label>
                        <input type="text" name="label" class="form-control" value="{{ $item->label }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control no-resize">{{ $item->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="is_active" value="1" id="edit_is_active{{ $item->id }}" {{ $item->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="edit_is_active{{ $item->id }}">Active Status</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary">Update Criterion</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
