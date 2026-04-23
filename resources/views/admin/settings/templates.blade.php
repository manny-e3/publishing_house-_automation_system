@extends('layouts.admin')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Email Templates</h3>
                            <div class="nk-block-des text-soft">
                                <p>Customize the content and subject of automated outgoing emails.</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-icon">
                        <em class="icon ni ni-check-circle"></em> {{ session('success') }}
                    </div>
                @endif

                <div class="nk-block">
                    <div class="row g-gs">
                        @foreach($templates as $template)
                        <div class="col-md-6 col-lg-4">
                            <div class="card card-bordered">
                                <div class="card-inner">
                                    <div class="card-title-group align-start mb-2">
                                        <div class="card-title">
                                            <h6 class="title">{{ $template->name }}</h6>
                                        </div>
                                        <div class="card-tools">
                                            <em class="icon ni ni-mail-fill text-primary fs-24px"></em>
                                        </div>
                                    </div>
                                    <p class="text-soft fs-12px mb-4"><strong>Subject:</strong> {{ $template->subject }}</p>
                                    <button class="btn btn-outline-primary btn-dim btn-block" data-bs-toggle="modal" data-bs-target="#editTemplate{{ $template->id }}">
                                        <em class="icon ni ni-edit"></em><span>Edit Content</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="editTemplate{{ $template->id }}">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Template: {{ $template->name }}</h5>
                                            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <em class="icon ni ni-cross"></em>
                                            </a>
                                        </div>
                                        <form action="{{ route('admin.settings.templates.update', $template->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label class="form-label">Email Subject</label>
                                                    <input type="text" name="subject" class="form-control" value="{{ $template->subject }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Email Body (HTML Supported)</label>
                                                    <textarea name="body" class="form-control no-resize" rows="10">{{ $template->body }}</textarea>
                                                </div>
                                                @if($template->placeholders)
                                                <div class="alert alert-fill alert-light mt-3">
                                                    <h6 class="fs-12px overline-title mb-1">Available Placeholders</h6>
                                                    <div class="d-flex flex-wrap" style="gap: 5px;">
                                                        @foreach($template->placeholders as $placeholder)
                                                        <code class="px-2 py-1 bg-white border rounded text-primary">{<span>{</span>{{ $placeholder }}}<span>}</span></code>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer bg-light text-right">
                                                <button type="submit" class="btn btn-primary">Update Template</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
