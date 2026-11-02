@extends('layouts.author')

@section('title', 'Tracking: ' . $prospect->book_title)

@section('content')
<main class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Navigation & Title -->
    <div class="mb-8">
        <a href="{{ route('portal.author.index') }}" class="text-xs font-bold text-gray-500 uppercase tracking-widest hover:text-navy-950 inline-flex items-center mb-3">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Digital Bookshelf
        </a>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-serif font-bold text-gray-900 mb-1">{{ $prospect->book_title }}</h1>
                <p class="text-xs text-gray-500">
                    Publishing Model: <strong class="text-gray-800">{{ $prospect->isTraditional() ? 'Traditional Contract' : 'Osisi Hybrid Publishing' }}</strong> &bull; 
                    Assigned ISBN: <strong class="text-gray-800">{{ $prospect->isbn_code ?? $prospect->metadata_file['isbn'] ?? 'In Registration' }}</strong> &bull; 
                    Word Count: <strong class="text-gray-800">{{ number_format($prospect->number_of_words) }} words</strong>
                </p>
            </div>
            <div>
                @php
                    $status = $prospect->status?->value ?? $prospect->status;
                @endphp
                @if($isGate4Cleared)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300 shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Published & Live
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 p-4 border-l-4 border-green-500 rounded-sm mb-6 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- 5-Stage Publishing Timeline Stepper -->
    <div class="bg-white border border-gray-200 rounded p-6 shadow-sm mb-8">
        <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest font-serif mb-4">Publishing Production Timeline</h2>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center">
            @php
                $steps = [
                    1 => ['title' => '1. Submission Intake', 'sub' => 'Vetting & ISBN'],
                    2 => ['title' => '2. Editorial Review', 'sub' => '3-Pass Revision'],
                    3 => ['title' => '3. Design Studio', 'sub' => 'Typeset & Cover'],
                    4 => ['title' => '4. Master Compilation', 'sub' => 'Press Ready Files'],
                    5 => ['title' => '5. Global Release', 'sub' => 'Amazon / Selar / ABC'],
                ];
            @endphp
            @foreach($steps as $num => $step)
                @php
                    $isCompleted = $currentStep > $num;
                    $isCurrent = $currentStep === $num;
                @endphp
                <div class="p-3 border rounded {{ $isCurrent ? 'bg-navy-950 text-white shadow-sm border-navy-950' : ($isCompleted ? 'bg-green-50 text-green-800 border-green-200' : 'bg-gray-50 text-gray-400 border-gray-100') }}">
                    <div class="mb-1 flex justify-center">
                        @if($isCompleted)
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        @elseif($isCurrent)
                            <svg class="w-5 h-5 text-amber-400 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        @else
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke-width="2"></circle></svg>
                        @endif
                    </div>
                    <div class="font-bold text-xs">{{ $step['title'] }}</div>
                    <div class="text-[10px] opacity-75">{{ $step['sub'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Gate 4: Gated Digital Bookshelf -->
    <div id="digital-bookshelf" class="bg-white border border-gray-200 rounded p-6 shadow-sm mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-6 border-b border-gray-100">
            <div>
                <div class="flex items-center gap-2">
                    <svg class="w-6 h-6 text-navy-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <h2 class="text-xl font-serif font-bold text-gray-900">Digital Bookshelf & Multi-Format Downloads</h2>
                </div>
                <p class="text-xs text-gray-500 mt-1">Direct repository for your compiled book target files.</p>
            </div>
            <div>
                @if($isGate4Cleared)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300 shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                        Gate 4 Cleared: Downloads Active
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-sm">
                        <svg class="w-3.5 h-3.5 mr-1.5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Gate 4: In Production Layout Stage
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Format 1: ePub -->
            <div class="bg-gray-50 border border-gray-200 rounded p-5 flex flex-col justify-between text-center hover:border-gray-300 transition-colors">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1">ePub Edition</h3>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">Universal eBook format for iPad, Kindle app, Kobo, and smartphones.</p>
                </div>
                <div>
                    @if($isGate4Cleared && $prospect->epub_file_url)
                        <a href="{{ asset($prospect->epub_file_url) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-navy-950 text-white rounded text-xs font-bold tracking-wider uppercase hover:bg-navy-900 transition-colors shadow-sm" target="_blank" download>
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download ePub
                        </a>
                    @else
                        <span class="inline-flex items-center justify-center w-full px-3 py-2 rounded text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-sm cursor-not-allowed">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-amber-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            In Production Layout Stage
                        </span>
                    @endif
                </div>
            </div>

            <!-- Format 2: eBook PDF -->
            <div class="bg-gray-50 border border-gray-200 rounded p-5 flex flex-col justify-between text-center hover:border-gray-300 transition-colors">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1">eBook PDF Proof</h3>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">Vector-rendered interior typesetting proof optimized for tablet screens.</p>
                </div>
                <div>
                    @if($isGate4Cleared && $prospect->ebook_pdf_url)
                        <a href="{{ asset($prospect->ebook_pdf_url) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-red-600 text-white rounded text-xs font-bold tracking-wider uppercase hover:bg-red-700 transition-colors shadow-sm" target="_blank">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download PDF Proof
                        </a>
                    @else
                        <span class="inline-flex items-center justify-center w-full px-3 py-2 rounded text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-sm cursor-not-allowed">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-amber-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            In Production Layout Stage
                        </span>
                    @endif
                </div>
            </div>

            <!-- Format 3: Print Ready PDF -->
            <div class="bg-gray-50 border border-gray-200 rounded p-5 flex flex-col justify-between text-center hover:border-gray-300 transition-colors">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1">Print-Ready PDF</h3>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">Physical press master with trim alignment, margins, and CMYK color plates.</p>
                </div>
                <div>
                    @if($isGate4Cleared && $prospect->print_ready_pdf_url)
                        <a href="{{ asset($prospect->print_ready_pdf_url) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-cyan-700 text-white rounded text-xs font-bold tracking-wider uppercase hover:bg-cyan-800 transition-colors shadow-sm" target="_blank">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download Press File
                        </a>
                    @else
                        <span class="inline-flex items-center justify-center w-full px-3 py-2 rounded text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-sm cursor-not-allowed">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-amber-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            In Production Layout Stage
                        </span>
                    @endif
                </div>
            </div>

            <!-- Format 4: Amazon KDP Package -->
            <div class="bg-gray-50 border border-gray-200 rounded p-5 flex flex-col justify-between text-center hover:border-gray-300 transition-colors">
                <div>
                    <div class="w-12 h-12 mx-auto rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm mb-1">Amazon KDP Package</h3>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">Syndicated Amazon Kindle direct publishing asset bundle with verified metadata.</p>
                </div>
                <div>
                    @if($isGate4Cleared && $prospect->amazon_kdp_ready_url)
                        <a href="{{ asset($prospect->amazon_kdp_ready_url) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-amber-600 text-white rounded text-xs font-bold tracking-wider uppercase hover:bg-amber-700 transition-colors shadow-sm" target="_blank" download>
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download KDP Bundle
                        </a>
                    @else
                        <span class="inline-flex items-center justify-center w-full px-3 py-2 rounded text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 shadow-sm cursor-not-allowed">
                            <svg class="w-3.5 h-3.5 mr-1.5 text-amber-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            In Production Layout Stage
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Author Proof Feedback & History -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Submit Proof Feedback -->
        <div class="bg-white border border-gray-200 rounded p-6 shadow-sm">
            <h3 class="text-lg font-serif font-bold text-gray-900 mb-1">Submit Proof Feedback</h3>
            <p class="text-xs text-gray-500 mb-4">
                Reviewing your interior formatting or cover layout? Leave timestamped feedback, typo notes, or spread adjustments directly for our studio team.
            </p>

            <form action="{{ route('portal.author.feedback', $prospect->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Page Reference / Spread (Optional)</label>
                    <input type="text" name="page_reference" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-navy-950" placeholder="e.g. Page 18, Chapter 2 header, Back cover bio">
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Feedback / Revision Request</label>
                    <textarea name="note" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-navy-950" placeholder="Detail any spelling errors, typography comments, or spacing adjustments..." required></textarea>
                </div>
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-navy-950 text-white rounded text-xs font-bold tracking-wider uppercase hover:bg-navy-900 transition-colors shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Submit Revision Feedback
                </button>
            </form>
        </div>

        <!-- Feedback & Proof History Stream -->
        <div class="bg-white border border-gray-200 rounded p-6 shadow-sm">
            <h3 class="text-lg font-serif font-bold text-gray-900 mb-1">Editorial & Proof History</h3>
            <p class="text-xs text-gray-500 mb-4">Milestone annotations and feedback logged between you and the publishing house.</p>

            <div class="space-y-3 overflow-y-auto" style="max-height: 330px;">
                @php
                    $notes = $prospect->metadata_file['proof_annotations'] ?? [];
                @endphp
                @forelse(array_reverse($notes) as $note)
                    <div class="p-3 bg-gray-50 rounded border-l-4 {{ ($note['role'] ?? '') === 'author' ? 'border-green-500' : 'border-navy-950' }} text-xs">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="font-bold text-gray-800">
                                {{ $note['user_name'] ?? 'Production Desk' }}
                                <span class="text-[10px] text-gray-400 font-normal">({{ ucfirst($note['role'] ?? 'studio') }})</span>
                            </span>
                            <span class="text-[10px] text-gray-400">{{ \Carbon\Carbon::parse($note['created_at'])->diffForHumans() }}</span>
                        </div>
                        @if(!empty($note['page_number']))
                            <div class="inline-block px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-200 text-gray-700 mb-1">
                                Ref: {{ $note['page_number'] }}
                            </div>
                        @endif
                        <p class="text-gray-700 leading-relaxed">{{ $note['note'] }}</p>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-400 text-xs">
                        <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        No proof notes or feedback logged yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection
