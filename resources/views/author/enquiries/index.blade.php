@extends('layouts.author')

@section('title', 'My Publishing Enquiries')

@section('content')
<div class="max-w-5xl">
    <div class="mb-12">
        <h1 class="text-5xl font-serif font-bold mb-4">My Publishing Enquiries</h1>
        <p class="text-gray-500 text-lg max-w-2xl leading-relaxed">
            Track the progress of your submitted manuscripts and editorial dialogues. A curated ledger of your literary pursuits.
        </p>
    </div>

    <!-- {{-- Action Required: Pending Signatures --}}
    @php
        $pendingSignatures = $enquiries->filter(function($e) {
            return $e->project && 
                   $e->project->latestContract && 
                   $e->project->latestContract->status !== 'signed';
        });
    @endphp

    @if($pendingSignatures->isNotEmpty())
        <div class="mb-12 space-y-4">
            <h2 class="text-xs font-bold text-orange-500 uppercase tracking-widest mb-4">Action Required</h2>
            @foreach($pendingSignatures as $enquiry)
                <div class="bg-orange-50 border border-orange-100 p-6 rounded-sm flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="p-3 bg-orange-500 text-white rounded-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-orange-900">Pending Signature: {{ $enquiry->book_title }}</h4>
                            <p class="text-sm text-orange-700/70">Your publishing agreement is ready for review. Please sign it to move to production.</p>
                        </div>
                    </div>
                    <a href="{{ route('author.contracts.show', $enquiry->project->latestContract->id) }}" class="bg-orange-600 text-white text-xs font-bold py-3 px-6 rounded-sm hover:bg-orange-700 transition-colors shadow-sm">
                        REVIEW & SIGN AGREEMENT
                    </a>
                </div>
            @endforeach
        </div>
    @endif -->

    <div class="space-y-4">
        @forelse($enquiries as $enquiry)
            <div class="bg-white p-8 rounded-sm shadow-sm flex items-center justify-between group hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-12">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-widest w-24">
                        {{ $enquiry->created_at->format('M d, Y') }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-serif font-bold mb-1">{{ $enquiry->book_title }}</h3>
                        <p class="text-sm text-gray-400">
                            {{ $enquiry->genre }} · {{ number_format($enquiry->number_of_words) }} words
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-8">
                    @if($enquiry->project)
                        <div class="flex flex-col items-end min-w-[200px]">
                            <div class="flex items-center justify-between w-full mb-2">
                                <span class="text-[10px] font-bold text-navy-950 uppercase tracking-widest">{{ $enquiry->project->stage_label }}</span>
                                <span class="text-[10px] font-bold text-gray-400">{{ $enquiry->project->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 h-1 rounded-full overflow-hidden">
                                <div class="bg-navy-950 h-full transition-all duration-1000" style="width: {{ $enquiry->project->progress_percentage }}%"></div>
                            </div>
                            <span class="text-[9px] text-gray-400 mt-2 uppercase tracking-tighter">Live Production</span>
                        </div>
                    @else
                        @php
                            $statusClass = 'status-action-required';
                            $statusLabel = 'Under Review';
                            
                            if ($enquiry->status === 'accepted') {
                                $statusClass = 'status-under-review';
                                $statusLabel = 'Accepted';
                            } elseif ($enquiry->status === 'rejected') {
                                $statusClass = 'status-draft';
                                $statusLabel = 'Closed';
                            }
                        @endphp
                        
                        <span class="status-badge {{ $statusClass }}">
                            @if($enquiry->status === 'pending')
                                <span class="inline-block w-2 h-2 rounded-full bg-orange-400 mr-2"></span>{{ $statusLabel }}
                            @else
                               {{ ucfirst($enquiry->status) }}
                            @endif
                        </span>
                    @endif
                    
                    <a href="{{ route('author.enquiries.show', $enquiry->id) }}" class="flex items-center space-x-2 text-sm font-bold group-hover:text-navy-950 transition-colors">
                        <span>VIEW DETAILS</span>
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white p-16 rounded-sm shadow-sm text-center border-2 border-dashed border-gray-100">
                <p class="text-gray-400 italic mb-4">No literary pursuits found in our archives yet.</p>
                <a href="{{ route('enquiry.index') }}" class="text-navy-950 font-bold hover:underline">Begin your submission journey</a>
            </div>
        @endforelse
    </div>
</div>
@endsection
