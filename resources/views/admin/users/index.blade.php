@extends('layouts.admin')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">User Management</h3>
                            <div class="nk-block-des text-soft">
                                <p>Manage platform users and their assigned roles.</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                                <em class="icon ni ni-user-add"></em><span>Add New User</span>
                            </a>
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
                                        <th class="nk-tb-col"><span class="sub-text">User</span></th>
                                        <th class="nk-tb-col"><span class="sub-text">Role</span></th>
                                        <th class="nk-tb-col"><span class="sub-text">Joined At</span></th>
                                        <th class="nk-tb-col nk-tb-col-tools text-end"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr class="nk-tb-item">
                                        <td class="nk-tb-col">
                                            <div class="user-card">
                                                <div class="user-avatar bg-primary">
                                                    <span>{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                                </div>
                                                <div class="user-info">
                                                    <span class="tb-lead">{{ $user->name }}</span>
                                                    <span>{{ $user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="nk-tb-col">
                                            @foreach($user->roles as $role)
                                                <span class="badge badge-dim bg-outline-primary text-uppercase">{{ $role->name }}</span>
                                            @endforeach
                                        </td>
                                        <td class="nk-tb-col">
                                            <span>{{ $user->created_at->format('d M Y') }}</span>
                                        </td>
                                        <td class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">
                                                <li>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li><a href="{{ route('admin.users.edit', $user->id) }}"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                                                @if($user->id !== auth()->id())
                                                                <li>
                                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" id="delete-user-{{ $user->id }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" onclick="event.preventDefault(); if(confirm('Are you sure?')) document.getElementById('delete-user-{{ $user->id }}').submit();" class="text-danger">
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
@endsection
