@extends('layouts.admin')

@section('title', 'Legal & Contract Management')

@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Legal & Contracts</h3>
                    <div class="nk-block-des text-soft">
                        <p>A consolidated registry of all executed submission agreements, author terms, and publishing contracts.</p>
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
                        <h5 class="title">All Executed Agreements</h5>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Agreement Type</th>
                                <th>Author / Signatory</th>
                                <th>Project / Book Title</th>
                                <th>Status</th>
                                <th>Signed Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contracts as $contract)
                            <tr>
                                <td>
                                    @if($contract->contract_type === 'publishing')
                                        <span class="badge bg-primary-dim text-primary">
                                            <em class="icon ni ni-file-docs"></em> Publishing Contract
                                        </span>
                                    @else
                                        <span class="badge bg-info-dim text-info">
                                            <em class="icon ni ni-file-text"></em> Submission Terms
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $contract->prospect->name ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $contract->prospect->email ?? '' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $contract->prospect->book_title ?? $contract->project->prospect->book_title ?? 'Untitled Manuscript' }}</strong>
                                </td>
                                <td>
                                    @if($contract->status === 'signed')
                                        <span class="badge bg-success-dim text-success">
                                            <em class="icon ni ni-check-circle"></em> Signed
                                        </span>
                                    @else
                                        <span class="badge bg-warning-dim text-warning">
                                            <em class="icon ni ni-clock"></em> Pending
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($contract->signed_at)
                                        <span class="fw-bold">{{ $contract->signed_at->format('M d, Y') }}</span><br>
                                        <small class="text-muted">{{ $contract->signed_at->format('H:i') }}</small>
                                    @else
                                        <span class="text-muted italic">Pending Execution</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.contracts.download', $contract) }}" class="btn btn-sm btn-outline-primary">
                                        <em class="icon ni ni-download"></em> Download PDF
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <em>No contracts or legal agreements found in the system.</em>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $contracts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
