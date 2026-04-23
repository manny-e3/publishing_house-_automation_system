@extends('layouts.admin')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Manage Permissions: {{ strtoupper($role->name) }}</h3>
                            <div class="nk-block-des text-soft">
                                <p>Control what actions this role can perform on the platform.</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex">
                                <em class="icon ni ni-arrow-left"></em><span>Back to Roles</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <div class="form-group mb-4">
                                    <label class="form-label">Role Display Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $role->name }}" required>
                                </div>
                                <div class="nk-block-head nk-block-head-line mt-4">
                                    <h6 class="title overline-title text-base">Assign Permissions</h6>
                                </div>
                                <div class="row g-4">
                                    @foreach($permissions as $permission)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="custom-control-input" id="perm_{{ $permission->id }}" {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="custom-control-label text-capitalize" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="mt-5 text-end">
                                    <button type="submit" class="btn btn-lg btn-primary">Update Role Permissions</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
