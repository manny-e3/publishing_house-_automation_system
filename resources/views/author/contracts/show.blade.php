@extends('layouts.author')

@section('title', 'Review & Sign Agreement')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-12">
        <h1 class="text-5xl font-serif font-bold mb-4">Publishing Agreement</h1>
        <p class="text-gray-500 text-lg leading-relaxed">
            Please review the legal terms of our partnership for <strong>"{{ $contract->project->prospect->book_title }}"</strong>.
        </p>
    </div>

    <div class="bg-white shadow-sm rounded-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-8 border-b border-gray-50 bg-gray-50 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-navy-950 text-white rounded-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-navy-950">Publishing_Agreement_{{ $contract->project->id }}.pdf</h3>
                    <p class="text-xs text-gray-400 uppercase tracking-widest">Legal Document · Generated {{ $contract->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            <a href="{{ asset('storage/' . $contract->document_path) }}" target="_blank" class="text-xs font-bold text-navy-950 hover:underline">DOWNLOAD PDF</a>
        </div>

        <div class="p-12 h-[600px] overflow-y-auto bg-gray-100/50 font-serif leading-relaxed text-gray-800 scrollbar-thin">
            <div class="bg-white p-12 shadow-sm max-w-2xl mx-auto border border-gray-200">
                <div class="text-center mb-12">
                    <h2 class="text-2xl font-bold uppercase tracking-tighter">Official Publishing Agreement</h2>
                    <p class="text-sm text-gray-400">The Curated Archive Publishing House</p>
                </div>

                <div class="space-y-6 text-sm">
                    <p>This Agreement is entered into on this <strong>{{ $contract->created_at->format('F d, Y') }}</strong> between <strong>The Curated Archive</strong> (hereinafter "The Publisher") and <strong>{{ $contract->project->prospect->name }}</strong> (hereinafter "The Author").</p>
                    
                    <h4 class="font-bold">1. GRANT OF RIGHTS</h4>
                    <p>The Author hereby grants and assigns to the Publisher the exclusive right to publish, distribute, and sell the Work titled <strong>"{{ $contract->project->prospect->book_title }}"</strong> in all formats throughout the world.</p>

                    <h4 class="font-bold">2. ROYALTIES</h4>
                    <p>The Publisher shall pay to the Author a royalty based on the Net Sales of the Work as defined in the associated invoice and payment schedule.</p>

                    <h4 class="font-bold">3. REPRESENTATIONS AND WARRANTIES</h4>
                    <p>The Author represents and warrants that they are the sole author of the Work and that the Work is original and does not infringe upon any copyright or proprietary right of any third party.</p>

                    <p class="italic text-gray-400 mt-12">[End of Preview]</p>
                </div>
            </div>
        </div>
    </div>

    @if($contract->status !== 'signed')
    <div class="bg-navy-950 p-12 rounded-sm text-white">
        <h3 class="text-3xl font-serif font-bold mb-6">Digital Signature</h3>
        <p class="text-navy-200 mb-8 max-w-xl">By signing this document, you enter into a legally binding contract with The Curated Archive. A finalized PDF will be generated and sent to your email.</p>

        <form action="{{ route('author.contracts.sign', $contract->id) }}" method="POST" class="max-w-md">
            @csrf
            <div class="mb-6">
                <label class="block text-xs font-bold uppercase tracking-widest text-navy-400 mb-2">Your Full Legal Name</label>
                <input type="text" name="name" required class="w-full bg-navy-900 border-navy-800 text-white p-4 focus:ring-1 focus:ring-white outline-none" placeholder="Type your name precisely as it appears above">
            </div>

            <div class="mb-8 flex items-start space-x-3">
                <input type="checkbox" name="agree" required id="agree" class="mt-1">
                <label for="agree" class="text-sm text-navy-200 leading-relaxed cursor-pointer">
                    I have read the agreement and I understand that by typing my name and clicking "Sign Agreement", I am providing a legal digital signature.
                </label>
            </div>

            <button type="submit" class="w-full bg-white text-navy-950 font-bold p-4 hover:bg-navy-100 transition-colors uppercase tracking-widest text-sm">
                Sign Agreement & Begin Production
            </button>
        </form>
    </div>
    @else
    <div class="bg-green-50 border border-green-100 p-12 rounded-sm text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h3 class="text-3xl font-serif font-bold text-green-900 mb-4">Agreement Signed</h3>
        <p class="text-green-700 mb-8">This contract was digitally signed on {{ $contract->signed_at->format('F d, Y \a\t H:i') }}.</p>
        <a href="{{ route('author.dashboard') }}" class="inline-block bg-green-900 text-white font-bold py-3 px-8 rounded-sm hover:bg-green-800 transition-colors">Return to Dashboard</a>
    </div>
    @endif
</div>
@endsection
