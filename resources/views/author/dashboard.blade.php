@extends('layouts.author')

@section('title', 'Author Dashboard Overview')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-12">
        @php
            $hour = now()->hour;
            $greeting = 'Good Morning';
            if ($hour >= 12 && $hour < 17) $greeting = 'Good Afternoon';
            elseif ($hour >= 17 || $hour < 5) $greeting = 'Good Evening';
        @endphp
        <h1 class="text-5xl font-serif font-bold mb-4">{{ $greeting }}, {{ auth()->user()->name }}</h1>
        <p class="text-gray-500 text-lg leading-relaxed">
            Welcome to your curated overview of your literary projects.
        </p>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-8 rounded-sm shadow-sm border-l-4 border-navy-950">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 font-serif">Active Projects</h4>
            <p class="text-4xl font-bold text-navy-950">{{ $activeProjectsCount }}</p>
        </div>
        <div class="bg-white p-8 rounded-sm shadow-sm border-l-4 border-gold">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 font-serif">Pending Enquiries</h4>
            <p class="text-4xl font-bold text-navy-950">{{ $pendingEnquiriesCount }}</p>
        </div>
        <div class="bg-white p-8 rounded-sm shadow-sm border-l-4 border-orange-500">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 font-serif">Unsigned Contracts</h4>
            <p class="text-4xl font-bold text-navy-950">{{ $pendingSignatures->count() }}</p>
        </div>
        <div class="bg-white p-8 rounded-sm shadow-sm border-l-4 border-green-500">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 font-serif">Unpaid Invoices</h4>
            <p class="text-4xl font-bold text-navy-950">{{ $unpaidInvoicesCount }}</p>
        </div>
    </div>

    <!-- {{-- Critical Alerts --}}
    @if($pendingSignatures->isNotEmpty())
        <div class="mb-12">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 font-serif">Action Required</h3>
            <div class="space-y-4">
                @foreach($pendingSignatures as $enquiry)
                    <div class="bg-orange-50 border border-orange-100 p-8 rounded-sm flex items-center justify-between">
                        <div class="flex items-center space-x-6">
                            <div class="p-3 bg-orange-500 text-white rounded-full">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-orange-900 text-xl">Sign Publishing Agreement: {{ $enquiry->book_title }}</h4>
                                <p class="text-orange-700/70">Legal signature required before we can move to the production stage.</p>
                            </div>
                        </div>
                        <a href="{{ route('author.contracts.show', $enquiry->project->latestContract->id) }}" class="bg-orange-600 text-white text-xs font-bold py-4 px-8 rounded-sm hover:bg-orange-700 transition-colors shadow-sm">
                            REVIEW & SIGN
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif -->

    {{-- Recent Submissions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest font-serif">Recent Submissions</h3>
                <a href="{{ route('author.enquiries.index') }}" class="text-xs font-bold text-navy-950 hover:underline">View All</a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentEnquiries as $enquiry)
                    <div class="bg-white p-6 rounded-sm shadow-sm flex items-center justify-between group">
                        <div class="flex items-center space-x-8">
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter w-16">
                                {{ $enquiry->created_at->format('M d') }}
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-1">{{ $enquiry->book_title }}</h3>
                                <p class="text-xs text-gray-400">{{ $enquiry->genre }}</p>
                            </div>
                        </div>
                        <a href="{{ route('author.enquiries.show', $enquiry->id) }}" class="text-navy-950 opacity-0 group-hover:opacity-100 transition-opacity">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                @empty
                    <p class="text-gray-400 italic">No recent activity.</p>
                @endforelse
            </div>
        </div>

        <div>
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-8 font-serif">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('author.enquiries.create') }}" class="block w-full text-center bg-navy-950 text-white py-4 font-bold text-xs tracking-widest uppercase hover:bg-navy-900 transition-colors rounded-sm">
                    New Manuscript Submission
                </a>
                <a href="{{ route('author.invoices') }}" class="block w-full text-center border border-gray-200 py-4 font-bold text-xs tracking-widest uppercase hover:bg-gray-50 transition-colors rounded-sm text-gray-600">
                    Manage Billing
                </a>
                <a href="{{ route('author.transactions') }}" class="block w-full text-center border border-gray-200 py-4 font-bold text-xs tracking-widest uppercase hover:bg-gray-50 transition-colors rounded-sm text-gray-600">
                    Payment History
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
