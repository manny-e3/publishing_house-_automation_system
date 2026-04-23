@extends('layouts.author')

@section('title', 'Enquiry Details: ' . $prospect->book_title)

@section('content')
<div class="max-w-4xl">
    <div class="mb-12 border-b border-gray-100 pb-8">
        <a href="{{ route('author.dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-navy-950 flex items-center mb-6 tracking-widest transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            BACK TO ENQUIRIES
        </a>
        <h1 class="text-5xl font-serif font-bold mb-4">{{ $prospect->book_title }}</h1>
        <div class="flex items-center space-x-6">
            <span class="text-xs font-bold text-gray-400 tracking-widest uppercase">Submitted {{ $prospect->created_at->format('M d, Y') }}</span>
            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
            
            @if($prospect->project)
                <div class="flex items-center space-x-4">
                    <span class="text-[10px] font-bold text-navy-950 uppercase tracking-widest px-3 py-1 bg-navy-50 border border-navy-100 rounded-full">
                        {{ $prospect->project->stage_label }}
                    </span>
                    <div class="w-24 bg-gray-100 h-1 rounded-full overflow-hidden">
                        <div class="bg-navy-950 h-full" style="width: {{ $prospect->project->progress_percentage }}%"></div>
                    </div>
                    <span class="text-[10px] font-bold text-gray-400">{{ $prospect->project->progress_percentage }}%</span>
                </div>
            @else
                @php
                    $statusClass = 'status-action-required';
                    if ($prospect->status === 'accepted') $statusClass = 'status-under-review';
                    elseif ($prospect->status === 'rejected') $statusClass = 'status-draft';
                @endphp
                <span class="status-badge {{ $statusClass }}">
                    {{ ucfirst($prospect->status) }}
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-3 gap-12">
        <div class="col-span-2 space-y-12">
            <section>
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 font-serif">MANUSCRIPT OVERVIEW</h2>
                <div class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Genre</label>
                        <p class="text-xl font-serif">{{ $prospect->genre }}</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Estimated Length</label>
                        <p class="text-xl font-serif">{{ number_format($prospect->number_of_words) }} words</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Current Production Stage</label>
                        @if($prospect->project)
                            <p class="text-xl font-serif text-navy-950">{{ $prospect->project->stage_label }}</p>
                        @else
                            <p class="text-xl font-serif">{{ str_replace('_', ' ', ucfirst($prospect->stage_of_manuscript)) }}</p>
                        @endif
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 font-serif">EDITORIAL DIALOGUE</h2>
                <div class="bg-white p-8 rounded-sm shadow-sm">
                    @if($prospect->evaluations->count() > 0)
                        <!-- Loop through evaluations if needed -->
                    @else
                        <p class="text-gray-400 italic">No editorial notes have been shared for this submission yet.</p>
                    @endif
                </div>
            </section>
        </div>

        <div class="space-y-8">
            <div class="bg-navy-950 p-8 rounded-sm text-white">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">
                    {{ $prospect->project ? 'Project Investment' : 'Investment Summary' }}
                </h3>
                <p class="text-3xl font-serif font-bold mb-2">₦{{ number_format($prospect->estimated_cost, 2) }}</p>
                <p class="text-xs text-gray-400 leading-relaxed mb-6">Estimated production cost based on your current selection of editing, design, and formatting services.</p>
                
                @if($prospect->project)
                    @php 
                        $latestPaidInvoice = $prospect->invoices()->where('status', 'paid')->latest()->first();
                        $isPartial = $latestPaidInvoice && $latestPaidInvoice->total_paid > 0 && $latestPaidInvoice->total_paid < $latestPaidInvoice->amount;
                    @endphp
                    <div class="space-y-3">
                        <div class="flex items-center space-x-2 text-[10px] uppercase font-bold text-green-400 tracking-widest">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            <span>{{ $isPartial ? 'INSTALLMENT PAID' : 'CONFIRMED & PAID' }}</span>
                        </div>
                        
                        @if($isPartial)
                            <div class="p-3 bg-navy-900 rounded-sm">
                                <div class="flex justify-between text-[10px] mb-1">
                                    <span class="text-gray-400">Paid to date</span>
                                    <span class="text-white">₦{{ number_format($latestPaidInvoice->total_paid, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-[10px]">
                                    <span class="text-gray-400">Balance</span>
                                    <span class="text-red-400">₦{{ number_format($latestPaidInvoice->amount - $latestPaidInvoice->total_paid, 2) }}</span>
                                </div>
                            </div>
                            
                            @if(($latestPaidInvoice->amount - $latestPaidInvoice->total_paid) > 0)
                                <a href="{{ route('payments.checkout', $latestPaidInvoice->id) }}" class="block w-full text-center py-2 bg-navy-800 text-white text-[10px] font-bold tracking-widest rounded-sm hover:bg-navy-700 transition-colors">PAY OUTSTANDING BALANCE</a>
                            @endif
                        @endif
                    </div>
                @elseif($prospect->status === 'accepted')
                     @if($prospect->invoices->count() > 0)
                          <a href="{{ route('payments.checkout', $prospect->invoices->last()->id) }}" class="block w-full text-center py-3 bg-white text-navy-950 text-xs font-bold tracking-widest rounded-sm hover:bg-gray-100 transition-colors">PROCEED TO CONTRACT (PAYMENT)</a>
                     @else
                          <div class="p-3 bg-white/10 text-white text-xs text-center border-l-2 border-brand-accent">
                               Your manuscript holds an accepted status! We are currently drafting your invoice.
                          </div>
                     @endif
                @endif
            </div>

            <div class="p-6 border border-gray-100 rounded-sm">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Archives</h3>
                <div class="space-y-4">
                    <a href="{{ Storage::url($prospect->manuscript_file_path) }}" class="flex items-center text-sm font-medium hover:text-navy-950 transition-colors">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        Manuscript Excerpt
                    </a>
                    @if($prospect->cover_design_path)
                    <a href="{{ Storage::url($prospect->cover_design_path) }}" class="flex items-center text-sm font-medium hover:text-navy-950 transition-colors">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Cover Art Concepts
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
