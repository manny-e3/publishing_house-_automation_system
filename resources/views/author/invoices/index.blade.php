@extends('layouts.author')

@section('content')
<main class="flex-grow w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-10">
        <h1 class="text-3xl md:text-4xl font-serif text-gray-900">My Invoices</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 border-l-4 border-green-500 mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 text-red-700 p-4 border-l-4 border-red-500 mb-6 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200">
        <ul class="divide-y divide-gray-200">
            @forelse($invoices as $invoice)
                <li class="p-6 sm:p-8 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-serif text-gray-900 mb-1">Invoice #{{ $invoice->invoice_number }}</h2>
                            <p class="text-sm text-gray-500 mb-2">For Manuscript: {{ $invoice->prospect->book_title ?? 'Unknown' }}</p>
                            <div class="flex flex-wrap items-center gap-3 mt-3">
                                @if($invoice->status == 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-sm text-xs font-bold bg-green-100 text-green-800">
                                        PAID
                                    </span>
                                @elseif($invoice->status == 'partially_paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-sm text-xs font-bold bg-yellow-100 text-yellow-800">
                                        PARTIALLY PAID
                                    </span>
                                @elseif($invoice->status == 'overdue')
                                    <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-sm text-xs font-bold bg-red-100 text-red-800">
                                        OVERDUE
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-sm text-xs font-bold bg-gray-100 text-gray-800">
                                        {{ strtoupper($invoice->status) }}
                                    </span>
                                @endif
                                <span class="text-xs text-gray-400">&bull;</span>
                                <span class="text-xs text-gray-500 font-medium tracking-wider">
                                    CREATED {{ $invoice->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col items-start sm:items-end w-full sm:w-auto">
                            <div class="text-right w-full mb-3 text-lg font-bold text-gray-900">
                                ₦{{ number_format($invoice->amount, 2) }}
                            </div>
                            @if($invoice->status !== 'paid')
                            <div class="flex gap-2 w-full sm:w-auto justify-end">
                                <a href="{{ route('payments.checkout', $invoice->id) }}" class="inline-flex justify-center items-center px-4 py-2 bg-navy-950 text-white rounded-sm text-xs font-bold tracking-widest hover:bg-navy-900 transition-colors">
                                    VIEW INVOICE / PAY
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-4 border-t border-gray-200 text-sm font-bold text-gray-900 tracking-widest uppercase">No Invoices</h3>
                    <p class="mt-2 text-sm text-gray-500">You do not have any invoices yet. They will appear here when an administration approves a manuscript submission.</p>
                </li>
            @endforelse
        </ul>
    </div>
</main>
@endsection
