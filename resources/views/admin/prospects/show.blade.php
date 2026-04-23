@extends('layouts.admin')

@section('title', 'Review Manuscript')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between g-3">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Manuscript Review / <strong class="text-primary small">{{ $prospect->book_title }}</strong></h3>
                            <div class="nk-block-des text-soft">
                                <ul class="list-inline">
                                    <li>Author: <span class="text-base">{{ $prospect->name }}</span></li>
                                    <li>Submitted: <span class="text-base">{{ $prospect->created_at->format('d M, Y h:i A') }}</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{ route('admin.prospects.index') }}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="row g-gs">
                        <div class="col-lg-8">
                            <div class="card card-bordered shadow-sm">
                                <div class="card-inner">
                                    <div class="card-head mb-3">
                                        <h6 class="title text-soft uppercase tracking-wider">Manuscript Details</h6>
                                    </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <span class="sub-text">Book Title</span>
                                                    <span class="lead-text">{{ $prospect->book_title }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <span class="sub-text">Genre</span>
                                                    <span class="lead-text">{{ Str::title($prospect->genre) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <span class="sub-text">Manuscript Stage</span>
                                                    <span class="lead-text text-primary">{{ Str::title(str_replace('_', ' ', $prospect->stage_of_manuscript)) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <span class="sub-text">Word Count</span>
                                                    <span class="lead-text">{{ number_format($prospect->number_of_words) }} Words</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <span class="sub-text">Estimated Investment</span>
                                                    <span class="lead-text text-success font-bold">₦{{ number_format($prospect->estimated_cost, 2) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <span class="sub-text">Services Required</span>
                                                    <div class="mt-2 d-flex flex-wrap g-2">
                                                        @if($prospect->quote_for_services)
                                                            @foreach($prospect->quote_for_services as $service)
                                                                <span class="badge badge-dim bg-outline-primary text-uppercase">{{ $service }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-soft fs-12px italic">Default (Editing only)</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @if(is_array($prospect->quote_for_services) && in_array('printing', $prospect->quote_for_services))
                                <div class="card-inner border-top bg-lighter">
                                    <div class="card-head mb-3">
                                        <h6 class="title text-soft uppercase tracking-wider">Production Selections</h6>
                                    </div>
                                    <div class="row g-gs">
                                        <div class="col-sm-4">
                                            <div class="form-group mb-0">
                                                <span class="sub-text">Quantity</span>
                                                <span class="lead-text text-base">{{ $prospect->print_quantity }} copies</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group mb-0">
                                                <span class="sub-text">Interior Paper</span>
                                                <span class="lead-text text-base">{{ $prospect->interior_paper ?? 'Not Specified' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group mb-0">
                                                <span class="sub-text">Cover Type</span>
                                                <span class="lead-text text-base">{{ $prospect->cover_paper ?? 'Not Specified' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <span class="sub-text">Enhancements</span>
                                            <div class="mt-1 d-flex flex-wrap" style="gap: 0.5rem;">
                                                @if($prospect->is_hard_cover) <span class="badge badge-dim bg-outline-primary">Hard Cover</span> @endif
                                                @if($prospect->is_embossed) <span class="badge badge-dim bg-outline-info">Embossing</span> @endif
                                                @if($prospect->is_packaged) <span class="badge badge-dim bg-outline-warning">Premium Packaging</span> @endif
                                                @if(!$prospect->is_hard_cover && !$prospect->is_embossed && !$prospect->is_packaged)
                                                    <span class="text-soft font-italic">No special effects selected.</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="card-inner border-top">
                                    <div class="card-head">
                                        <h6 class="title text-soft uppercase tracking-wider">Submitted Assets</h6>
                                    </div>
                                    <ul class="d-flex flex-wrap" style="gap: 1rem;">
                                        @if($prospect->manuscript_file_path)
                                        <li>
                                            <a href="{{ asset('storage/' . $prospect->manuscript_file_path) }}" target="_blank" class="btn btn-outline-primary"><em class="icon ni ni-file-docs"></em><span>Download Excerpt</span></a>
                                        </li>
                                        @endif
                                        @if($prospect->cover_design_path)
                                        <li>
                                            <a href="{{ asset('storage/' . $prospect->cover_design_path) }}" target="_blank" class="btn btn-outline-secondary"><em class="icon ni ni-img"></em><span>View Cover Idea</span></a>
                                        </li>
                                        @endif
                                        @if(!$prospect->manuscript_file_path && !$prospect->cover_design_path)
                                        <li><span class="text-soft">No files were uploaded.</span></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="card card-bordered shadow-sm mt-4">
                                <div class="card-inner">
                                    <div class="card-head mb-3">
                                        <h6 class="title text-soft uppercase tracking-wider">Author Information</h6>
                                    </div>
                                    <div class="row g-gs">
                                        <div class="col-md-6 text-sm">
                                            <div class="form-group mb-0">
                                                <span class="sub-text text-xs uppercase tracking-widest">Full Name</span>
                                                <span class="lead-text text-base">{{ $prospect->name }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-sm">
                                            <div class="form-group mb-0">
                                                <span class="sub-text text-xs uppercase tracking-widest">Email Address</span>
                                                <span class="lead-text text-base">{{ $prospect->email }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-sm">
                                            <div class="form-group mb-0">
                                                <span class="sub-text text-xs uppercase tracking-widest">Phone Number</span>
                                                <span class="lead-text text-base">{{ $prospect->phone_number }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-sm">
                                            <div class="form-group mb-0">
                                                <span class="sub-text text-xs uppercase tracking-widest">Agreement Name</span>
                                                <span class="lead-text text-base">{{ $prospect->agreement_name ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12 text-sm border-top pt-2">
                                            <div class="form-group mb-0">
                                               <span class="sub-text text-xs italic">Submission IP: {{ $prospect->ip_address }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-bordered shadow-sm mt-4" x-data="breakdownCalculator()">
                                <div class="card-inner">
                                    <div class="card-head">
                                        <h5 class="title">Cost Breakdown Estimate</h5>
                                    </div>
                                    <p class="text-soft text-sm mt-1 mb-3">System-generated itemized breakdown based on the submitted parameters.</p>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm text-sm mt-3">
                                             <thead class="bg-light">
                                                 <tr>
                                                     <th class="py-2 px-3 text-soft uppercase tracking-widest fs-10px">Service Element</th>
                                                     <th class="py-2 px-3 text-end text-soft uppercase tracking-widest fs-10px">Estimated Cost</th>
                                                 </tr>
                                             </thead>
                                             <tbody>
                                                 <tr>
                                                     <td class="px-3"><span class="sub-text">Base Setup Fee</span></td>
                                                     <td class="text-end px-3">₦<span x-text="format(setupFee)"></span></td>
                                                 </tr>
                                                 <tr x-show="services.includes('editing')" style="display: none;">
                                                     <td class="px-3">
                                                        <span class="sub-text">Editing (Base + Word Count)</span><br>
                                                        <small class="text-muted"><span x-text="format(words)"></span> words</small>
                                                     </td>
                                                     <td class="text-end px-3">₦<span x-text="format(editingCost)"></span></td>
                                                 </tr>
                                                 <tr x-show="services.includes('formatting')" style="display: none;">
                                                     <td class="px-3">
                                                        <span class="sub-text">Formatting (Base + Word Count)</span><br>
                                                        <small class="text-muted"><span x-text="format(words)"></span> words</small>
                                                     </td>
                                                     <td class="text-end px-3">₦<span x-text="format(formattingCost)"></span></td>
                                                 </tr>
                                                 <tr x-show="services.includes('cover')" style="display: none;">
                                                     <td class="px-3"><span class="sub-text">Cover Design</span></td>
                                                     <td class="text-end px-3">₦<span x-text="format(coverCost)"></span></td>
                                                 </tr>
                                                 <tr x-show="services.includes('printing')" style="display: none;">
                                                     <td class="px-3">
                                                        <span class="sub-text">Printing Calculation</span><br>
                                                        <small class="text-muted"><span x-text="print_quantity"></span> copies</small>
                                                     </td>
                                                     <td class="text-end px-3">₦<span x-text="format(printingCost)"></span></td>
                                                 </tr>
                                             </tbody>
                                             <tfoot class="bg-lighter">
                                                 <tr>
                                                     <td class="px-3 py-3 font-bold text-dark uppercase tracking-widest fs-11px">Total Estimated Quoted Cost</td>
                                                     <td class="text-end px-3 py-3 h5 text-success">₦<span x-text="format(totalCost)"></span></td>
                                                 </tr>
                                             </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card card-bordered shadow-sm">
                                <div class="card-inner">
                                    <div class="card-head mb-3">
                                        <h6 class="title text-soft uppercase tracking-wider">Acquisitions Review</h6>
                                    </div>
                                    <p class="text-soft mb-4">Evaluate the manuscript excerpt against our baseline criteria.</p>
                                    
                                    @if($prospect->status == 'prospect')
                                    <form action="{{ route('admin.prospects.status', $prospect->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <ul class="custom-control-group g-3 mb-4">
                                            @foreach($criteria as $item)
                                            <li>
                                                <div class="custom-control custom-checkbox custom-control-pro">
                                                    <input type="checkbox" name="evaluation[{{ $item->id }}][passed]" class="custom-control-input" id="chk-{{ $item->id }}">
                                                    <label class="custom-control-label" for="chk-{{ $item->id }}">
                                                        <span class="text-dark">{{ $item->label }}</span> 
                                                        <br><small class="text-soft">{{ $item->description }}</small>
                                                    </label>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>

                                        @if($criteria->isEmpty())
                                            <p class="text-soft italic fs-13px mb-4">No specific criteria configured. You may proceed with the decision below.</p>
                                        @endif

                                        <div class="form-group mb-4">
                                            <label class="form-label" for="reviewer_notes">Internal Notes (Optional)</label>
                                            <div class="form-control-wrap">
                                                <textarea class="form-control no-resize" name="reviewer_notes" id="reviewer_notes" placeholder="Internal remarks..." rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="d-flex" style="gap: 0.5rem;">
                                            <button type="submit" name="status" value="accepted" class="btn btn-success fw-bold flex-grow-1"><em class="icon ni ni-check-circle"></em><span>Accept Manuscript</span></button>
                                            <button type="button" class="btn btn-outline-danger btn-icon" data-bs-toggle="modal" data-bs-target="#rejectModal"><em class="icon ni ni-cross-circle"></em></button>
                                        </div>
                                    </form>
                                    @else
                                    <div class="alert alert-{{ $prospect->status == 'accepted' ? 'success' : 'danger' }} alert-icon">
                                        <em class="icon ni ni-{{ $prospect->status == 'accepted' ? 'check' : 'cross' }}-circle"></em> 
                                        This manuscript was <strong>{{ strtoupper($prospect->status) }}</strong>.
                                    </div>
                                    @endif
                                    
                                    <!-- Modal Alert Reject -->
                                    <div class="modal fade" tabindex="-1" id="rejectModal">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body modal-body-lg text-center">
                                                    <div class="nk-modal">
                                                        <em class="nk-modal-icon icon icon-circle icon-circle-xxl ni ni-cross bg-danger"></em>
                                                        <h4 class="nk-modal-title">Reject Manuscript?</h4>
                                                        <div class="nk-modal-text">
                                                            <p class="lead">Are you sure you want to officially reject this manuscript from {{ $prospect->name }}? This action will mark it closed.</p>
                                                        </div>
                                                        <form action="{{ route('admin.prospects.status', $prospect->id) }}" method="POST" class="mt-4">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="nk-modal-action d-flex justify-content-center" style="gap:0.5rem;">
                                                                <button type="submit" name="status" value="rejected" class="btn btn-lg btn-mw btn-danger">Yes, Reject</button>
                                                                <a href="#" class="btn btn-lg btn-mw btn-light" data-bs-dismiss="modal">Cancel</a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Modal -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('breakdownCalculator', () => ({
            words: {{ $prospect->number_of_words ?? 0 }},
            services: @json($prospect->quote_for_services ?? []),
            print_quantity: {{ $prospect->print_quantity ?? 1 }},
            estimated_pages: 120, // Default assumption matching frontend
            interior_paper: '{{ $prospect->interior_paper ?? '' }}',
            cover_paper: '{{ $prospect->cover_paper ?? '' }}',
            is_hard_cover: {{ $prospect->is_hard_cover ? 'true' : 'false' }},
            is_embossed: {{ $prospect->is_embossed ? 'true' : 'false' }},
            is_packaged: {{ $prospect->is_packaged ? 'true' : 'false' }},
            allRates: @json($rates),

            // Computed Costs
            get setupFee() {
                return parseFloat(this.allRates.fixed_setup_fee) || 150000;
            },
            get editingCost() {
                if (!this.services.includes('editing')) return 0;
                let fixed = parseFloat(this.allRates.fixed_editing_fee) || 0;
                let perWord = parseFloat(this.allRates.editing_per_word) || 5;
                return fixed + (this.words * perWord);
            },
            get formattingCost() {
                if (!this.services.includes('formatting')) return 0;
                let fixed = parseFloat(this.allRates.fixed_formatting_fee) || 0;
                let perWord = parseFloat(this.allRates.formatting_per_word) || 2;
                return fixed + (this.words * perWord);
            },
            get coverCost() {
                if (!this.services.includes('cover')) return 0;
                return parseFloat(this.allRates.fixed_cover_design_fee) || 50000;
            },
            get printingCost() {
                if (!this.services.includes('printing')) return 0;
                let r = this.allRates;
                let total = 0;
                
                if (this.interior_paper && r[this.interior_paper]) {
                    let rims = Math.ceil((this.print_quantity * this.estimated_pages) / (32 * 500));
                    total += rims * parseFloat(r[this.interior_paper]);
                } else {
                    total += parseFloat(r.fixed_printing_fee) || 100000;
                }

                if (this.cover_paper && r[this.cover_paper]) {
                    total += parseFloat(r[this.cover_paper]);
                }

                let finishing = (parseFloat(r.calc_folding) || 50) + 
                                (parseFloat(r.calc_lamination) || 70) + 
                                (parseFloat(r.calc_binding) || 50) + 
                                (parseFloat(r.calc_cutting) || 20);
                
                total += (finishing * this.print_quantity);

                if (this.is_hard_cover) total += (parseFloat(r.calc_hard_cover) || 3000) * this.print_quantity;
                if (this.is_embossed) total += (parseFloat(r.calc_embossing) || 500) * this.print_quantity;
                if (this.is_packaged) total += (parseFloat(r.calc_packaging) || 50) * this.print_quantity;
                
                total += (parseFloat(r.calc_interior_plating) || 4500) * Math.ceil(this.estimated_pages / 16);
                total += (parseFloat(r.calc_cover_plating) || 14000);
                
                return total;
            },
            get totalCost() {
                return this.setupFee + this.editingCost + this.formattingCost + this.coverCost + this.printingCost;
            },
            format(num) {
                return isNaN(num) ? '0' : new Intl.NumberFormat('en-NG').format(num);
            }
        }));
    });
</script>
@endpush
