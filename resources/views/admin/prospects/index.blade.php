@extends('layouts.admin')

@section('title', 'Prospect Tracker')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Prospect Tracker</h3>
                            <div class="nk-block-des text-soft">
                                <p>You have {{ $prospects->count() }} incoming manuscripts awaiting review.</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li><a href="#" class="btn btn-white btn-outline-light"><em class="icon ni ni-download-cloud"></em><span>Export</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="nk-block">
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner p-0">
                                <table class="table table-tranx">
                                    <thead>
                                        <tr class="tb-tnx-head bg-light">
                                            <th class="tb-tnx-id"><span class="">ID</span></th>
                                            <th class="tb-tnx-info">
                                                <span class="tb-tnx-desc d-none d-sm-inline-block">
                                                    <span>Author Information</span>
                                                </span>
                                                <span class="tb-tnx-date d-md-inline-block d-none">
                                                    <span class="d-md-none">Date</span>
                                                    <span class="d-none d-md-block"><span>Submitted On</span></span>
                                                </span>
                                            </th>
                                            <th class="tb-tnx-info">
                                                <span>Manuscript Pulse</span>
                                            </th>
                                            <th class="tb-tnx-amount">
                                                <span class="tb-tnx-total">Est. Revenue</span>
                                                <span class="tb-tnx-status">Action</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($prospects as $prospect)
                                        <tr class="tb-tnx-item">
                                            <td class="tb-tnx-id">
                                                <a href="#"><span>#{{ str_pad($prospect->id, 4, '0', STR_PAD_LEFT) }}</span></a>
                                            </td>
                                            <td class="tb-tnx-info">
                                                <div class="tb-tnx-desc">
                                                    <span class="title fw-bold text-dark">{{ $prospect->name }}</span>
                                                    <span class="sub text-soft d-block">{{ $prospect->email }}</span>
                                                    <span class="sub text-soft d-block">{{ $prospect->phone_number }}</span>
                                                </div>
                                                <div class="tb-tnx-date">
                                                    <span class="date">{{ $prospect->created_at->format('d M, Y') }}</span>
                                                    <span class="date text-soft">{{ $prospect->created_at->format('h:i A') }}</span>
                                                </div>
                                            </td>
                                            <td class="tb-tnx-info">
                                                <div class="tb-tnx-desc">
                                                    <span class="title d-block mb-1"><i>{{ $prospect->book_title }}</i></span>
                                                    <span class="badge badge-dim bg-outline-primary mb-1">{{ $prospect->genre }}</span>
                                                    <span class="badge badge-dim bg-outline-secondary">{{ number_format($prospect->number_of_words) }} Words</span>
                                                </div>
                                            </td>
                                            <td class="tb-tnx-amount">
                                                <div class="tb-tnx-total">
                                                    <span class="amount text-success fw-bold">₦{{ number_format($prospect->estimated_cost, 0) }}</span>
                                                </div>
                                                <div class="tb-tnx-status mt-2">
                                                    @if($prospect->status == 'prospect')
                                                    <a href="{{ route('admin.prospects.show', $prospect->id) }}" class="btn btn-sm btn-primary">Review</a>
                                                    @else
                                                    <span class="badge bg-{{ $prospect->status == 'accepted' ? 'success' : 'danger' }}">{{ ucfirst($prospect->status) }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <em class="icon ni ni-inbox fs-1 d-block mb-2 text-light"></em>
                                                No publishing enquiries have been submitted yet.
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($prospects->hasPages())
                            <div class="card-inner">
                                {{ $prospects->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
