@extends('layouts.author')

@section('title', 'Author Publishing Portal & Digital Bookshelf')

@section('content')
<main class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-serif font-bold text-gray-900 mb-2">Author Portal & Digital Bookshelf</h1>
            <p class="text-gray-500 text-sm md:text-base">
                Welcome back, <span class="font-semibold text-gray-800">{{ auth()->user()->name }}</span>. Inspect compiled multi-format proofs, monitor production gates, and download live published editions.
            </p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="{{ route('author.enquiries.create') }}" class="inline-flex items-center px-4 py-2.5 bg-navy-950 text-white rounded text-xs font-bold tracking-wider uppercase hover:bg-navy-900 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Proposal
            </a>
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

    @if(session('error'))
        <div class="bg-red-50 text-red-800 p-4 border-l-4 border-red-500 rounded-sm mb-6 text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($activeProspect)
        <!-- Manuscript Selection Tabs -->
        @if($prospects->count() > 1)
        <div class="mb-6">
            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 font-serif">Select Manuscript Bookshelf</div>
            <div class="flex flex-wrap gap-2">
                @foreach($prospects as $p)
                    @php
                        $isActive = $p->id === $activeProspect->id;
                        $pStatus = $p->status?->value ?? $p->status;
                        $pGate4Cleared = !empty($p->metadata_file['distribution_validated']) || $pStatus === \App\Enums\WorkflowStatus::Published->value;
                    @endphp
                    <a href="{{ route('portal.author.index', ['book' => $p->id]) }}" 
                       class="inline-flex items-center px-4 py-2 rounded text-xs font-medium border transition-colors {{ $isActive ? 'bg-navy-950 text-white border-navy-950 shadow-sm' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' }}">
                        <span class="font-bold mr-2">{{ $p->book_title }}</span>
                        @if($pGate4Cleared)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold {{ $isActive ? 'bg-green-500 text-white' : 'bg-green-100 text-green-800' }}">Live</span>
                        @else
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold {{ $isActive ? 'bg-amber-400 text-navy-950' : 'bg-amber-100 text-amber-900' }}">In Layout</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Active Manuscript Publishing Timeline Stepper -->
        <div class="bg-white border border-gray-200 rounded p-6 shadow-sm mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-6">
                <div>
                    <h2 class="text-lg font-serif font-bold text-gray-900">{{ $activeProspect->book_title }}</h2>
                    <p class="text-xs text-gray-500">
                        Author: <strong class="text-gray-700">{{ $activeProspect->name }}</strong> &bull; 
                        Model: <strong class="text-gray-700">{{ $activeProspect->isTraditional() ? 'Traditional Contract' : 'Osisi Hybrid Publishing' }}</strong> &bull; 
                        ISBN: <strong class="text-gray-700">{{ $activeProspect->isbn_code ?? $activeProspect->metadata_file['isbn'] ?? 'In Registration' }}</strong>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('portal.author.show', $activeProspect->id) }}" class="text-xs font-bold text-navy-950 hover:underline inline-flex items-center">
                        Full Pipeline Details
                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Stepper -->
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-center">
                @php
                    $steps = [
                        1 => ['title' => '1. Intake', 'sub' => 'Vetting & ISBN'],
                        2 => ['title' => '2. Editorial', 'sub' => '3-Pass Revision'],
                        3 => ['title' => '3. Design', 'sub' => 'Typeset & Cover'],
                        4 => ['title' => '4. Master Files', 'sub' => 'Press Ready Files'],
                        5 => ['title' => '5. Release', 'sub' => 'Amazon / Global'],
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

        <!-- ===================================================================== -->
        <!-- PROMPT 10 / GATE 4: DIGITAL BOOKSHELF & MULTI-FORMAT DOWNLOADS -->
        <!-- ===================================================================== -->
        <div id="digital-bookshelf" class="bg-white border border-gray-200 rounded p-6 shadow-sm mb-12">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 mb-6 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6 text-navy-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <h2 class="text-xl font-serif font-bold text-gray-900">Digital Bookshelf & Multi-Format Downloads</h2>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Direct repository for your compiled book target files for <strong>{{ $activeProspect->book_title }}</strong>.</p>
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

            <!-- Bookshelf 4 Format Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Format 1: ePub Edition -->
                <div class="bg-gray-50 border border-gray-200 rounded p-5 flex flex-col justify-between text-center hover:border-gray-300 transition-colors">
                    <div>
                        <div class="w-12 h-12 mx-auto rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-sm mb-1">ePub Edition</h3>
                        <p class="text-xs text-gray-500 mb-4 leading-relaxed">Universal eBook format for iPad, Kindle app, Kobo, and smartphones.</p>
                    </div>
                    <div>
                        @if($isGate4Cleared && $activeProspect->epub_file_url)
                            <a href="{{ asset($activeProspect->epub_file_url) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-navy-950 text-white rounded text-xs font-bold tracking-wider uppercase hover:bg-navy-900 transition-colors shadow-sm" target="_blank" download>
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

                <!-- Format 2: eBook PDF Proof -->
                <div class="bg-gray-50 border border-gray-200 rounded p-5 flex flex-col justify-between text-center hover:border-gray-300 transition-colors">
                    <div>
                        <div class="w-12 h-12 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 text-sm mb-1">eBook PDF Proof</h3>
                        <p class="text-xs text-gray-500 mb-4 leading-relaxed">Vector-rendered interior typesetting proof optimized for tablet screens.</p>
                    </div>
                    <div>
                        @if($isGate4Cleared && $activeProspect->ebook_pdf_url)
                            <a href="{{ asset($activeProspect->ebook_pdf_url) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-red-600 text-white rounded text-xs font-bold tracking-wider uppercase hover:bg-red-700 transition-colors shadow-sm" target="_blank">
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
                        @if($isGate4Cleared && $activeProspect->print_ready_pdf_url)
                            <a href="{{ asset($activeProspect->print_ready_pdf_url) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-cyan-700 text-white rounded text-xs font-bold tracking-wider uppercase hover:bg-cyan-800 transition-colors shadow-sm" target="_blank">
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
                        @if($isGate4Cleared && $activeProspect->amazon_kdp_ready_url)
                            <a href="{{ asset($activeProspect->amazon_kdp_ready_url) }}" class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-amber-600 text-white rounded text-xs font-bold tracking-wider uppercase hover:bg-amber-700 transition-colors shadow-sm" target="_blank" download>
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
    @endif

    <!-- All Registered Author Manuscripts -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xs font-bold text-gray-400 uppercase tracking-widest font-serif">All Manuscripts Under Contract</h2>
            <span class="text-xs text-gray-400">{{ $prospects->count() }} Title{{ $prospects->count() === 1 ? '' : 's' }}</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($prospects as $book)
                @php
                    $bStatus = $book->status?->value ?? $book->status;
                    $bGate4Cleared = !empty($book->metadata_file['distribution_validated']) || $bStatus === \App\Enums\WorkflowStatus::Published->value;
                @endphp
                <div class="bg-white border border-gray-200 rounded p-6 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $book->isTraditional() ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $book->isTraditional() ? 'Traditional Contract' : 'Osisi Hybrid Publishing' }}
                            </span>
                            @if($bGate4Cleared)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800">
                                    Published & Live
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-900">
                                    {{ ucfirst(str_replace('_', ' ', $bStatus)) }}
                                </span>
                            @endif
                        </div>

                        <h3 class="font-serif font-bold text-lg text-gray-900 mb-1 leading-snug">{{ $book->book_title }}</h3>
                        <p class="text-xs text-gray-500 mb-4">{{ $book->genre }} &bull; {{ number_format($book->number_of_words) }} words</p>

                        <div class="bg-gray-50 border border-gray-100 rounded p-3 text-xs mb-4">
                            <div class="text-gray-400 text-[10px] uppercase font-bold tracking-wider mb-0.5">Assigned ISBN</div>
                            <div class="font-mono font-semibold text-gray-800">{{ $book->isbn_code ?? $book->metadata_file['isbn'] ?? 'Pending National Library Registration' }}</div>
                        </div>
                    </div>

                    <div class="space-y-2 pt-2 border-t border-gray-100">
                        <a href="{{ route('portal.author.index', ['book' => $book->id]) }}#digital-bookshelf" class="w-full inline-flex items-center justify-center px-3 py-2 bg-gray-100 text-gray-800 rounded text-xs font-bold hover:bg-gray-200 transition-colors">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            View Digital Bookshelf
                        </a>
                        <a href="{{ route('portal.author.show', $book->id) }}" class="w-full inline-flex items-center justify-center px-3 py-2 bg-navy-950 text-white rounded text-xs font-bold hover:bg-navy-900 transition-colors">
                            Track Pipeline & Review Proofs &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-gray-200 rounded p-12 text-center">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <h3 class="font-serif font-bold text-lg text-gray-800 mb-1">No Manuscripts Registered</h3>
                    <p class="text-xs text-gray-500 mb-4">You do not currently have any active publishing proposals registered with your account.</p>
                    <a href="{{ route('enquiry.index') }}" class="inline-flex items-center px-4 py-2 bg-navy-950 text-white rounded text-xs font-bold">
                        Submit a Manuscript Proposal
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</main>
@endsection
