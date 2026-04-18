@extends('layouts.admin')

@section('content')
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
                    <div class="card-inner p-0">
                        <table class="table table-tranx">
                            <thead>
                                <tr class="tb-tnx-head">
                                    <th class="tb-tnx-id"><span class="">#</span></th>
                                    <th class="tb-tnx-info">
                                        <span class="tb-tnx-desc d-none d-sm-inline-block">
                                            <span>Label</span>
                                        </span>
                                    </th>
                                    <th class="tb-tnx-info">
                                        <span class="tb-tnx-desc">Description</span>
                                    </th>
                                    <th class="tb-tnx-status">Status</th>
                                    <th class="tb-tnx-action"><span>&nbsp;</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($criteria as $item)
                                <tr class="tb-tnx-item">
                                    <td class="tb-tnx-id">
                                        <span>{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="tb-tnx-info">
                                        <div class="tb-tnx-desc">
                                            <span class="title">{{ $item->label }}</span>
                                        </div>
                                    </td>
                                    <td class="tb-tnx-info">
                                        <span class="text-soft">{{ $item->description }}</span>
                                    </td>
                                    <td class="tb-tnx-status">
                                        <span class="badge badge-dot {{ $item->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="tb-tnx-action">
                                        <div class="dropdown">
                                            <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-xs">
                                                <ul class="link-list-plain">
                                                    <li>
                                                        <form action="{{ route('admin.settings.criteria.delete', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-danger border-0 bg-transparent py-2 px-3 w-100 text-left">Remove</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
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
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary">Save Criterion</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
