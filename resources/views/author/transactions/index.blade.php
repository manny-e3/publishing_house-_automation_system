@extends('layouts.author')

@section('content')
<main class="flex-grow w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex items-center justify-between mb-10">
        <h1 class="text-3xl md:text-4xl font-serif text-gray-900">My Transactions</h1>
    </div>

    <div class="bg-white border border-gray-200">
        <ul class="divide-y divide-gray-200">
            @forelse($transactions as $transaction)
                <li class="p-6 sm:p-8 hover:bg-gray-50 transition-colors">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex-1">
                            <h2 class="text-base font-serif text-gray-900 font-bold mb-1">
                                Payment for Invoice #{{ $transaction->invoice->invoice_number }}
                            </h2>
                            <p class="text-xs text-gray-500 mb-2">Book: {{ $transaction->invoice->prospect->book_title ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500 font-mono mb-2">Ref: {{ $transaction->transaction_reference }}</p>
                            <div class="flex flex-wrap items-center gap-3 mt-3">
                                @if($transaction->status == 'successful' || $transaction->status == 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-sm text-xs font-bold bg-green-100 text-green-800">
                                        SUCCESSFUL
                                    </span>
                                @elseif($transaction->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-sm text-xs font-bold bg-yellow-100 text-yellow-800">
                                        PENDING
                                    </span>
                                @elseif($transaction->status == 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-sm text-xs font-bold bg-red-100 text-red-800">
                                        FAILED
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 mt-1 rounded-sm text-xs font-bold bg-gray-100 text-gray-800">
                                        {{ strtoupper($transaction->status) }}
                                    </span>
                                @endif
                                <span class="text-xs text-gray-400">&bull;</span>
                                <span class="text-xs text-gray-500 font-medium tracking-wider">
                                    {{ $transaction->created_at->format('M d, Y h:i A') }}
                                </span>
                                <span class="text-xs text-gray-400">&bull;</span>
                                <span class="text-xs text-gray-500 font-medium tracking-wider uppercase">
                                    {{ $transaction->gateway_slug }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col items-start sm:items-end w-full sm:w-auto">
                            <div class="text-right w-full mb-3 text-lg font-bold text-gray-900">
                                {{ strtoupper($transaction->currency) }} {{ number_format($transaction->amount, 2) }}
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-4 border-t border-gray-200 text-sm font-bold text-gray-900 tracking-widest uppercase">No Transactions</h3>
                    <p class="mt-2 text-sm text-gray-500">You don't have any payment history on record.</p>
                </li>
            @endforelse
        </ul>
    </div>
</main>
@endsection
