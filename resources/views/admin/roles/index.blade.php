@extends('layouts.admin')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Role Management</h3>
                            <div class="nk-block-des text-soft">
                                <p>Define access levels and associate permissions.</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRole">
                                <em class="icon ni ni-plus"></em><span>Add New Role</span>
                            </button>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-icon">
                        <em class="icon ni ni-check-circle"></em> {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-icon">
                        <em class="icon ni ni-cross-circle"></em> {{ session('error') }}
                    </div>
                @endif

                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner">
                            <table class="datatable-init nk-tb-list nk-tb-ulist table" data-auto-responsive="false">
                                <thead>
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col"><span class="sub-text">Role Name</span></th>
                                        <th class="nk-tb-col"><span class="sub-text">Users Count</span></th>
                                        <th class="nk-tb-col nk-tb-col-tools text-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                    <tr class="nk-tb-item">
                                        <td class="nk-tb-col">
                                            <span class="tb-lead text-uppercase">{{ $role->name }}</span>
                                        </td>
                                        <td class="nk-tb-col">
                                            <span class="badge badge-dim bg-light text-dark">{{ $role->users_count }} Users</span>
                                        </td>
                                        <td class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">
                                                <li>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li><a href="{{ route('admin.roles.edit', $role->id) }}"><em class="icon ni ni-setting"></em><span>Manage Permissions</span></a></li>
                                                                @if(!in_array($role->name, ['admin', 'author']))
                                                                <li>
                                                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" id="delete-role-{{ $role->id }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" onclick="event.preventDefault(); if(confirm('Are you sure?')) document.getElementById('delete-role-{{ $role->id }}').submit();" class="text-danger">
                                                                            <em class="icon ni ni-trash"></em><span>Remove</span>
                                                                        </a>
                                                                    </form>
                                                                </li>
                                                                @endif
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

<!-- Modal -->
<div class="modal fade" id="addRole">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Role</h5>
                <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <form action="{{ route('admin.roles.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Role Name (Internal ID)</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. human_resources" required>
                        <p class="text-soft smaller mt-1">Lower case, no spaces. Use underscores.</p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary">Save Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
