@extends('layouts.admin')

@section('title', 'Vetting: ' . $prospect->book_title)

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <a href="{{ route('dashboard.acquisition.index') }}" class="text-muted small">&larr; Back to Acquisition Queue</a>
                    <h3 class="nk-block-title page-title mt-1">{{ $prospect->book_title }}</h3>
                    <div class="nk-block-des text-soft">
                        <p>Submitted by <strong>{{ $prospect->name }}</strong> ({{ $prospect->email }}) &bull; Classified under <strong>{{ $prospect->isTraditional() ? 'Traditional Model' : 'Osisi Hybrid Publishing' }}</strong></p>
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

        <div class="row g-gs">
            <!-- Left Column: Acquisition Checklist & ISBN -->
            <div class="col-lg-7">
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <div class="card-title-group mb-3">
                            <div class="card-title">
                                <h5 class="title">Acquisition Checklist (4 Core Gates)</h5>
                            </div>
                            <div class="card-tools">
                                <span class="badge bg-primary">{{ $progress['completed'] }} of {{ $progress['total'] }} Done ({{ $progress['percentage'] }}%)</span>
                            </div>
                        </div>

                        <div class="progress progress-sm mb-4">
                            <div class="progress-bar bg-primary" data-progress="{{ $progress['percentage'] }}" style="width: {{ $progress['percentage'] }}%;"></div>
                        </div>

                        <div class="list-group list-group-flush border rounded" x-data="{
                            toggleTask(key, checked) {
                                fetch('{{ route('dashboard.acquisition.task.toggle', $prospect->id) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ task_key: key, completed: checked })
                                }).then(r => r.json()).then(data => {
                                    window.location.reload();
                                });
                            }
                        }">
                            @foreach($acquisitionTasks as $key => $title)
                                @php $isDone = !empty($prospect->checklist_progress[$key]['completed']); @endphp
                                <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="task_{{ $key }}" 
                                            {{ $isDone ? 'checked' : '' }}
                                            @change="toggleTask('{{ $key }}', $event.target.checked)">
                                        <label class="custom-control-label fw-bold {{ $isDone ? 'text-decoration-line-through text-muted' : '' }}" for="task_{{ $key }}">
                                            {{ $title }}
                                        </label>
                                    </div>
                                    @if($isDone)
                                        <span class="badge bg-success-dim text-success">
                                            <em class="icon ni ni-check"></em> Verified
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted">Pending</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ISBN Assignment Card -->
                <div class="card card-bordered">
                    <div class="card-inner">
                        <h5 class="title mb-3">Assign International Standard Book Number (ISBN)</h5>
                        <p class="text-muted small mb-3">
                            Once assigned by the National Library or publishing registrar, enter the official 13-digit ISBN. This completes both the ISBN application and registry checklist tasks.
                        </p>
                        <form action="{{ route('dashboard.acquisition.isbn.assign', $prospect->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <span class="input-group-text"><em class="icon ni ni-property-add"></em></span>
                                <input type="text" name="isbn" class="form-control" placeholder="e.g. 978-978-789-012-3" value="{{ $prospect->metadata_file['isbn'] ?? '' }}" required>
                                <button type="submit" class="btn btn-primary">Save & Register ISBN</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Metadata & Raw Manuscript -->
            <div class="col-lg-5">
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <h5 class="title mb-3">Author & Triage Profile</h5>
                        <table class="table table-sm">
                            <tr>
                                <td class="text-muted">Author Name:</td>
                                <td class="fw-bold">{{ $prospect->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Author Age:</td>
                                <td class="fw-bold">{{ $prospect->author_age ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Marketing Source:</td>
                                <td><span class="badge bg-outline-primary">{{ ucfirst($prospect->marketing_source ?: 'direct') }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Social Media Presence:</td>
                                <td class="fw-bold">{{ $prospect->has_social_presence ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Established Author:</td>
                                <td class="fw-bold">{{ $prospect->is_established_author ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Assigned Model:</td>
                                <td>
                                    <span class="badge {{ $prospect->isTraditional() ? 'bg-success' : 'bg-primary' }}">
                                        {{ $prospect->isTraditional() ? 'Traditional' : 'Osisi Hybrid' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Word Count:</td>
                                <td class="fw-bold">{{ number_format($prospect->number_of_words) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Genre:</td>
                                <td class="fw-bold">{{ $prospect->genre }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Payment Status:</td>
                                <td>
                                    @php
                                        $isPaid = ($prospect->payment_status === 'paid') || $prospect->invoices()->where('status', 'paid')->exists();
                                        $paidInvoice = $prospect->invoices()->where('status', 'paid')->latest()->first();
                                    @endphp
                                    <span class="badge {{ $isPaid ? 'bg-success' : 'bg-warning' }}">
                                        {{ $isPaid ? 'Paid' : 'Unpaid' }}
                                    </span>
                                    @if($paidInvoice)
                                        <span class="small text-muted ms-1">({{ $paidInvoice->currency_symbol }}{{ number_format($paidInvoice->is_installment ? $paidInvoice->total_paid : $paidInvoice->amount, 2) }})</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <hr>

                        <h6 class="title mb-2">Raw Manuscript File</h6>
                        @if($prospect->manuscript_file_path)
                            <a href="{{ asset('storage/' . $prospect->manuscript_file_path) }}" class="btn btn-outline-primary w-100" target="_blank">
                                <em class="icon ni ni-download"></em> Download Raw Manuscript
                            </a>
                        @else
                            <div class="alert alert-light text-center small text-muted">
                                No raw document file uploaded yet.
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

