@extends('layouts.admin')

@section('title', 'Print & Multi-Channel Distribution')

@section('content')
<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <!-- Page Header with Search & Quick Stats -->
                <div class="nk-block-head nk-block-head-sm mb-4">
                    <div class="nk-block-between align-items-center flex-wrap gap-2">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title mb-1">Print Distribution & Multi-Platform Syndication</h3>
                            <div class="nk-block-des text-soft">
                                <p class="mb-0">Manage digital storefronts (Amazon KDP, Selar, African Book Collective), physical warehouse stock, and Gate 3 & Gate 4 access locks.</p>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <form action="{{ route('dashboard.distribution.index') }}" method="GET" class="d-flex align-items-center gap-2">
                                <div class="form-control-wrap">
                                    <div class="form-icon form-icon-left">
                                        <em class="icon ni ni-search"></em>
                                    </div>
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search book, author, ISBN..." value="{{ request('search') }}">
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                                @if(request('search'))
                                    <a href="{{ route('dashboard.distribution.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-icon mb-4 border border-success">
                        <em class="icon ni ni-check-circle"></em> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-icon mb-4 border border-danger">
                        <em class="icon ni ni-alert-circle"></em> {{ session('error') }}
                    </div>
                @endif

                @if($lowStockItems->count() > 0)
                <!-- Low Inventory Stock Alert -->
                <div class="alert alert-warning alert-icon mb-4 border border-warning shadow-sm">
                    <em class="icon ni ni-alert-fill fs-3 text-warning me-2"></em>
                    <div>
                        <strong>Low Inventory Warning:</strong> {{ $lowStockItems->count() }} title(s) have fallen below the 20-copy safety threshold in warehouse storage. Bulk reprint reorders recommended.
                    </div>
                </div>
                @endif

                <!-- Distribution & Channels Table -->
                <div class="card card-bordered mb-4">
                    <div class="card-inner">
                        <div class="card-title-group mb-3 pb-2 border-bottom">
                            <div class="card-title">
                                <h5 class="title mb-1">Multi-Channel Syndication & Release Validation (Gates 3 & 4)</h5>
                                <p class="text-muted small mb-0">Titles awaiting final release validation are pinned to the top of the queue.</p>
                            </div>
                            <div class="card-tools">
                                <span class="badge bg-light text-secondary border">
                                    Showing {{ $manuscripts->count() }} of {{ $manuscripts->total() }} Titles
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Book Title & ISBN</th>
                                        <th>Author</th>
                                        <th>Target Artifacts</th>
                                        <th class="text-center">Amazon KDP</th>
                                        <th class="text-center">Selar</th>
                                        <th class="text-center">ABC</th>
                                        <th class="text-center">Gate 4 Clearance Action</th>
                                        <th class="text-end">Panel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($manuscripts as $book)
                                    @php
                                        $listings = $book->channelListings->keyBy('channel');
                                        $isAmazonLive = !empty($listings['amazon_kdp']) && $listings['amazon_kdp']->is_live;
                                        $isSelarLive = !empty($listings['selar']) && $listings['selar']->is_live;
                                        $isAbcLive = !empty($listings['african_book_collective']) && $listings['african_book_collective']->is_live;
                                        $isDistributionValidated = !empty($book->metadata_file['distribution_validated']);
                                        $bookStatus = $book->status?->value ?? $book->status;
                                        $isPublished = $bookStatus === 'published' || $isDistributionValidated;
                                    @endphp
                                    <tr class="{{ !$isPublished ? 'table-warning-subtle' : '' }}">
                                        <td>
                                            <div class="fw-bold text-dark fs-6">{{ $book->book_title }}</div>
                                            <div class="small text-muted">
                                                ISBN: <strong class="text-dark">{{ $book->isbn_code ?? $book->metadata_file['isbn'] ?? 'Not registered' }}</strong>
                                                &bull; <span class="badge {{ $book->isTraditional() ? 'bg-success-dim text-success' : 'bg-primary-dim text-primary' }}" style="font-size: 10px;">{{ $book->isTraditional() ? 'Traditional' : 'Osisi Hybrid' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $book->name }}</div>
                                            <small class="text-muted">{{ $book->email }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <span class="badge {{ $book->epub_file_url ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="font-size: 10px;">
                                                    <em class="icon ni {{ $book->epub_file_url ? 'ni-check' : 'ni-cross' }}"></em> ePub
                                                </span>
                                                <span class="badge {{ $book->amazon_kdp_ready_url ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="font-size: 10px;">
                                                    <em class="icon ni {{ $book->amazon_kdp_ready_url ? 'ni-check' : 'ni-cross' }}"></em> KDP
                                                </span>
                                                <span class="badge {{ $book->print_ready_pdf_url ? 'bg-success text-white' : 'bg-light text-muted border' }}" style="font-size: 10px;">
                                                    <em class="icon ni {{ $book->print_ready_pdf_url ? 'ni-check' : 'ni-cross' }}"></em> PDF
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <!-- Amazon KDP Gate 3 Form -->
                                            <form action="{{ route('dashboard.distribution.channel.update', $book->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="channel" value="amazon_kdp">
                                                <input type="hidden" name="is_live" value="{{ $isAmazonLive ? '0' : '1' }}">
                                                @if($isAmazonLive)
                                                    <button type="submit" class="btn btn-xs btn-success" title="Click to deactivate Amazon KDP">
                                                        <em class="icon ni ni-check"></em> Live
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-xs {{ $book->amazon_kdp_ready_url ? 'btn-outline-primary' : 'btn-outline-danger' }}" 
                                                        title="{{ $book->amazon_kdp_ready_url ? 'Activate Amazon' : 'Blocked by Gate 3: Upload Amazon Ready file first' }}">
                                                        <em class="icon ni {{ $book->amazon_kdp_ready_url ? 'ni-plus' : 'ni-lock' }}"></em> Activate
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <!-- Selar Gate 3 Form -->
                                            <form action="{{ route('dashboard.distribution.channel.update', $book->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="channel" value="selar">
                                                <input type="hidden" name="is_live" value="{{ $isSelarLive ? '0' : '1' }}">
                                                @if($isSelarLive)
                                                    <button type="submit" class="btn btn-xs btn-success" title="Click to deactivate Selar">
                                                        <em class="icon ni ni-check"></em> Live
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-xs {{ $book->epub_file_url ? 'btn-outline-primary' : 'btn-outline-danger' }}"
                                                        title="{{ $book->epub_file_url ? 'Activate Selar' : 'Blocked by Gate 3: Upload ePub file first' }}">
                                                        <em class="icon ni {{ $book->epub_file_url ? 'ni-plus' : 'ni-lock' }}"></em> Activate
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <!-- African Book Collective Form -->
                                            <form action="{{ route('dashboard.distribution.channel.update', $book->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="channel" value="african_book_collective">
                                                <input type="hidden" name="is_live" value="{{ $isAbcLive ? '0' : '1' }}">
                                                <button type="submit" class="btn btn-xs {{ $isAbcLive ? 'btn-success' : 'btn-outline-secondary' }}">
                                                    <em class="icon ni {{ $isAbcLive ? 'ni-check' : 'ni-plus' }}"></em> {{ $isAbcLive ? 'Live' : 'List' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <!-- Gate 4 Release Validation Button -->
                                            <form action="{{ route('dashboard.distribution.release.validate', $book->id) }}" method="POST" class="d-inline-flex align-items-center gap-1">
                                                @csrf
                                                @if($isDistributionValidated || $bookStatus === 'published')
                                                    <input type="hidden" name="validated" value="0">
                                                    <span class="badge bg-success text-white py-1.5 px-2.5">
                                                        <em class="icon ni ni-shield-check me-1"></em> Published & Live
                                                    </span>
                                                    <button type="submit" class="btn btn-xs btn-outline-danger" title="Revoke validation / Lock Gate 4">
                                                        <em class="icon ni ni-lock"></em>
                                                    </button>
                                                @else
                                                    <input type="hidden" name="validated" value="1">
                                                    <button type="submit" class="btn btn-sm btn-primary text-nowrap shadow-sm">
                                                        <em class="icon ni ni-check-circle me-1"></em> Validate Final Print & Release
                                                    </button>
                                                @endif
                                            </form>
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-xs btn-outline-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#distModal-{{ $book->id }}">
                                                <em class="icon ni ni-layout-alt me-1"></em> Distribution Panel
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No titles matching query in distribution staging.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $manuscripts->links() }}
                        </div>
                    </div>
                </div>

                <!-- Modals rendered outside table to prevent browser DOM foster-parenting & CSS distortion -->
                @foreach($manuscripts as $book)
                @php
                    $listings = $book->channelListings->keyBy('channel');
                    $isAmazonLive = !empty($listings['amazon_kdp']) && $listings['amazon_kdp']->is_live;
                    $isSelarLive = !empty($listings['selar']) && $listings['selar']->is_live;
                    $isAbcLive = !empty($listings['african_book_collective']) && $listings['african_book_collective']->is_live;
                    $isDistributionValidated = !empty($book->metadata_file['distribution_validated']);
                    $bookStatus = $book->status?->value ?? $book->status;
                    $isPublished = $bookStatus === 'published' || $isDistributionValidated;
                @endphp
                <div class="modal fade" id="distModal-{{ $book->id }}" tabindex="-1" aria-labelledby="distModalLabel-{{ $book->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content shadow">
                            <div class="modal-header bg-lighter border-bottom">
                                <div>
                                    <h5 class="modal-title text-dark" id="distModalLabel-{{ $book->id }}">Distribution Panel: {{ $book->book_title }}</h5>
                                    <span class="small text-muted">Author: {{ $book->name }} &bull; ISBN: {{ $book->isbn_code ?? $book->metadata_file['isbn'] ?? 'In Registration' }}</span>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <!-- Master Release Validation Action Box (Gate 4) -->
                                <div class="p-3 mb-4 rounded border {{ $isPublished ? 'border-success bg-success-dim' : 'border-primary bg-primary-dim' }}">
                                    <div class="row align-items-center g-3">
                                        <div class="col-md-7 col-12">
                                            <h6 class="mb-1 text-dark fw-bold">
                                                <em class="icon ni {{ $isPublished ? 'ni-shield-check text-success' : 'ni-lock text-primary' }} fs-5 me-1 align-middle"></em>
                                                Gate 4 Digital Bookshelf Release Gate
                                            </h6>
                                            <p class="small mb-0 text-dark" style="line-height: 1.45;">
                                                @if($isPublished)
                                                    Global release has been validated! The manuscript is live across storefronts and the author's digital bookshelf downloads are unlocked.
                                                @else
                                                    This manuscript is currently in production layout stage. Click to validate final print & release, clear Gate 4, and unlock all multi-format files in the Author Portal.
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-5 col-12 text-md-end text-start">
                                            <form action="{{ route('dashboard.distribution.release.validate', $book->id) }}" method="POST" class="m-0 d-inline-block">
                                                @csrf
                                                @if($isPublished)
                                                    <input type="hidden" name="validated" value="0">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger bg-white shadow-sm text-nowrap">
                                                        <em class="icon ni ni-lock me-1"></em> Lock Bookshelf (Revoke Release)
                                                    </button>
                                                @else
                                                    <input type="hidden" name="validated" value="1">
                                                    <button type="submit" class="btn btn-primary btn-sm shadow text-nowrap">
                                                        <em class="icon ni ni-check-circle me-1"></em> Validate Final Print & Release
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Production Target Files Summary -->
                                <h6 class="overline-title text-muted mb-2">Compiled Production Artifacts</h6>
                                <div class="row g-2 mb-4">
                                    <div class="col-sm-3 col-6">
                                        <div class="p-2 border rounded text-center {{ $book->epub_file_url ? 'bg-light' : 'bg-lighter text-muted' }}">
                                            <div class="small fw-bold">ePub Mobile</div>
                                            <span class="badge {{ $book->epub_file_url ? 'bg-success' : 'bg-light text-muted border' }}" style="font-size: 10px;">
                                                {{ $book->epub_file_url ? 'Ready' : 'Missing' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <div class="p-2 border rounded text-center {{ $book->ebook_pdf_url ? 'bg-light' : 'bg-lighter text-muted' }}">
                                            <div class="small fw-bold">eBook PDF</div>
                                            <span class="badge {{ $book->ebook_pdf_url ? 'bg-success' : 'bg-light text-muted border' }}" style="font-size: 10px;">
                                                {{ $book->ebook_pdf_url ? 'Ready' : 'Missing' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <div class="p-2 border rounded text-center {{ $book->print_ready_pdf_url ? 'bg-light' : 'bg-lighter text-muted' }}">
                                            <div class="small fw-bold">Press Blueprint</div>
                                            <span class="badge {{ $book->print_ready_pdf_url ? 'bg-success' : 'bg-light text-muted border' }}" style="font-size: 10px;">
                                                {{ $book->print_ready_pdf_url ? 'Ready' : 'Missing' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <div class="p-2 border rounded text-center {{ $book->amazon_kdp_ready_url ? 'bg-light' : 'bg-lighter text-muted' }}">
                                            <div class="small fw-bold">KDP Package</div>
                                            <span class="badge {{ $book->amazon_kdp_ready_url ? 'bg-success' : 'bg-light text-muted border' }}" style="font-size: 10px;">
                                                {{ $book->amazon_kdp_ready_url ? 'Ready' : 'Missing' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Gate 3 Channels Section -->
                                <h6 class="overline-title text-muted mb-2">Channel Storefronts (Gate 3 Enforcement)</h6>
                                <div class="list-group mb-2">
                                    <!-- Amazon -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <div>
                                            <div class="fw-bold">Amazon KDP Global Direct</div>
                                            <small class="text-muted">Requires compiled Amazon KDP Package (.kpf / .zip / .mobi)</small>
                                        </div>
                                        <form action="{{ route('dashboard.distribution.channel.update', $book->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="channel" value="amazon_kdp">
                                            <input type="hidden" name="is_live" value="{{ $isAmazonLive ? '0' : '1' }}">
                                            @if($isAmazonLive)
                                                <button type="submit" class="btn btn-sm btn-success">Active on Amazon</button>
                                            @else
                                                <button type="submit" class="btn btn-sm {{ $book->amazon_kdp_ready_url ? 'btn-primary' : 'btn-outline-danger' }}"
                                                    {{ $book->amazon_kdp_ready_url ? '' : 'disabled' }}>
                                                    {{ $book->amazon_kdp_ready_url ? 'Publish to Amazon KDP' : 'Gate 3 Locked (Upload KDP Package)' }}
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                    <!-- Selar -->
                                    <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                                        <div>
                                            <div class="fw-bold">Selar Digital Storefront (NGN / USD)</div>
                                            <small class="text-muted">Requires reflowable .epub format</small>
                                        </div>
                                        <form action="{{ route('dashboard.distribution.channel.update', $book->id) }}" method="POST" class="m-0">
                                            @csrf
                                            <input type="hidden" name="channel" value="selar">
                                            <input type="hidden" name="is_live" value="{{ $isSelarLive ? '0' : '1' }}">
                                            @if($isSelarLive)
                                                <button type="submit" class="btn btn-sm btn-success">Active on Selar</button>
                                            @else
                                                <button type="submit" class="btn btn-sm {{ $book->epub_file_url ? 'btn-primary' : 'btn-outline-danger' }}"
                                                    {{ $book->epub_file_url ? '' : 'disabled' }}>
                                                    {{ $book->epub_file_url ? 'Publish to Selar Store' : 'Gate 3 Locked (Upload ePub File)' }}
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-light border-top">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close Panel</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Physical Warehouse Inventory Ledger -->
                <div class="card card-bordered">
                    <div class="card-inner">
                        <div class="card-title-group mb-3 pb-2 border-bottom">
                            <div class="card-title">
                                <h5 class="title mb-1"><em class="icon ni ni-box text-primary me-1"></em> Physical Warehouse Inventory Ledger</h5>
                                <p class="text-muted small mb-0">Track real-time bulk print storage, reorder safety thresholds, and warehouse logistics.</p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Book Title</th>
                                        <th>Format</th>
                                        <th>Warehouse Location</th>
                                        <th class="text-center">Quantity Ordered</th>
                                        <th class="text-center">Quantity In Stock</th>
                                        <th class="text-center">Threshold</th>
                                        <th>Stock Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $inventories = \App\Models\PhysicalInventory::with('prospect')->latest()->get();
                                    @endphp
                                    @forelse($inventories as $inv)
                                    <tr class="{{ $inv->isLowStock() ? 'table-warning' : '' }}">
                                        <td>
                                            <strong>{{ $inv->prospect->book_title ?? 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $inv->prospect->name ?? 'Author' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ ucfirst($inv->format?->value ?? $inv->format ?? 'paperback') }}
                                            </span>
                                        </td>
                                        <td>{{ $inv->warehouse_location ?: 'Main Warehouse' }}</td>
                                        <td class="text-center">{{ number_format($inv->quantity_ordered) }}</td>
                                        <td class="text-center fw-bold fs-6 {{ $inv->isLowStock() ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($inv->quantity_in_stock) }}
                                        </td>
                                        <td class="text-center text-muted">{{ $inv->reorder_threshold }}</td>
                                        <td>
                                            @if($inv->isLowStock())
                                                <span class="badge bg-danger text-white"><em class="icon ni ni-alert-fill me-1"></em> Low Stock (&le;{{ $inv->reorder_threshold }})</span>
                                            @else
                                                <span class="badge bg-success text-white"><em class="icon ni ni-check-circle me-1"></em> In Stock</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">No physical inventory batches recorded yet.</td>
                                    </tr>
                                    @endforelse
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
