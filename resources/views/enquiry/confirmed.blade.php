<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiry & Excerpt Received - Griots Lounge Publishing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=Jost:ital,wght@0,300..700;1,300..700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"EB Garamond"', 'Georgia', 'serif'],
                        sans: ['"Jost"', 'Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#131313',
                            emerald: '#00382B',
                            emeraldHover: '#00271E',
                            sage: '#96BBA4',
                            sageHover: '#82A891',
                            teal: '#009688',
                            tealHover: '#00796B',
                            terracotta: '#00382B', // Backward compatible alias
                            terracottaHover: '#00271E',
                            accent: '#96BBA4',
                            accentHover: '#82A891',
                            surface: '#FFFFFF',
                            bg: '#FCFCFC',
                            linen: '#FDF7F2'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-brand-bg font-sans text-gray-800 antialiased selection:bg-brand-emerald selection:text-white flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('welcome') }}" class="font-serif italic font-bold text-2xl text-brand-dark tracking-tight">Griots Lounge</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-brand-terracotta transition">Author Sign In</a>
                    <a href="{{ route('welcome') }}" class="bg-brand-dark text-white px-4 py-2 text-xs font-bold tracking-wider uppercase hover:bg-gray-800 transition-colors">
                        Catalog
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="bg-white border border-gray-200 rounded-sm shadow-sm overflow-hidden">
            
            <!-- Banner Head -->
            <div class="bg-brand-dark text-white p-8 md:p-10 text-center relative overflow-hidden">
                <div class="w-16 h-16 bg-brand-terracotta/20 text-brand-terracotta border border-brand-terracotta/40 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold shadow-inner">
                    ✓
                </div>
                <span class="text-xs uppercase tracking-[0.25em] text-brand-accent font-bold block mb-2">Submission Successfully Ingested</span>
                <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mb-3">Manuscript Excerpt Under Editorial Appraisal</h1>
                <p class="text-gray-300 max-w-xl mx-auto text-sm leading-relaxed">
                    Thank you, <strong>{{ $prospect->name }}</strong>. Your book synopsis and 3,000-word excerpt for <em class="text-white font-medium">"{{ $prospect->book_title }}"</em> have been logged and placed into our editorial evaluation queue.
                </p>
            </div>

            <!-- Body Details -->
            <div class="p-6 md:p-10 space-y-8">
                
                <!-- Project Metrics Card -->
                <div class="bg-gray-50 border border-gray-200 p-6 rounded-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-gray-200">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">Working Title</span>
                            <h2 class="text-xl font-serif font-bold text-gray-900">{{ $prospect->book_title }}</h2>
                            @if($prospect->pen_name)
                                <span class="text-xs text-gray-500">Pen Name: {{ $prospect->pen_name }}</span>
                            @endif
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">System Reference</span>
                            <span class="font-mono text-base font-bold text-brand-terracotta bg-white border border-brand-terracotta/30 px-3 py-1 rounded inline-block shadow-sm">
                                #GLP-{{ str_pad($prospect->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 text-xs">
                        <div>
                            <span class="text-gray-400 block uppercase font-medium text-[10px]">Primary Genre</span>
                            <span class="font-semibold text-gray-800">{{ $prospect->genre }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block uppercase font-medium text-[10px]">Total Word Count</span>
                            <span class="font-semibold text-gray-800">{{ number_format($prospect->number_of_words) }} words</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block uppercase font-medium text-[10px]">Selected Pathway</span>
                            <span class="font-semibold text-brand-terracotta uppercase">
                                @if(($prospect->publishing_option ?? '') === 'traditional')
                                    Traditional Publishing
                                @elseif(($prospect->publishing_option ?? '') === 'advise_me')
                                    Editorial Assessment
                                @else
                                    Hybrid Publishing
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 block uppercase font-medium text-[10px]">Current Status</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800">
                                Under Editorial Review
                            </span>
                        </div>
                    </div>

                    @if($prospect->synopsis)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <span class="text-gray-400 block uppercase font-medium text-[10px] mb-1">Submitted Synopsis</span>
                        <p class="text-xs text-gray-700 italic leading-relaxed line-clamp-3">
                            {{ Str::limit($prospect->synopsis, 250) }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- 4-Stage Transparent Milestone Roadmap (Point 12) -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-4 pb-2 border-b border-gray-100">
                        What Happens Next: 4-Stage Publishing Assessment Procedure
                    </h3>

                    <div class="space-y-4">
                        <!-- Step 1 -->
                        <div class="flex items-start space-x-4 p-4 rounded bg-emerald-50/50 border border-emerald-100">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                                ✓
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Step 1: Submission Ingested & Logged</h4>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    Your author profile, project details, and 3,000-word excerpt have been securely recorded. An electronic submission agreement has been stamped under intellectual property rights.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex items-start space-x-4 p-4 rounded bg-amber-50/60 border border-amber-200">
                            <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                                2
                            </div>
                            <div>
                                <div class="flex items-center space-x-2">
                                    <h4 class="text-sm font-bold text-gray-900">Step 2: Editorial Appraisal (Active Stage)</h4>
                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase bg-amber-200 text-amber-900 rounded">In Progress</span>
                                </div>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    The Griots Lounge editorial team evaluates your synopsis and excerpt for prose craft, thematic originality, audience resonance, and catalog fit. Turnaround is typically <strong>5 to 10 business days</strong>.
                                </p>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex items-start space-x-4 p-4 rounded bg-gray-50 border border-gray-100 opacity-75">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                3
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Step 3: Formal Editorial Recommendation & Pitch</h4>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    You will receive a formal notification containing editorial review notes. If accepted, you will receive either an offer for our Traditional Acquisitions Board or a tailored collaborative Hybrid publishing proposal.
                                </p>
                            </div>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex items-start space-x-4 p-4 rounded bg-gray-50 border border-gray-100 opacity-75">
                            <div class="w-8 h-8 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                4
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">Step 4: Publishing Agreement & Production Kickoff</h4>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    Only upon mutual contract agreement are invoices and deposits initialized. No payment is required or requested prior to contract execution.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Non-Acceptance Legal Disclaimer (Point 11) -->
                <div class="bg-gray-50 p-4 rounded border border-gray-200 text-xs text-gray-500 leading-relaxed">
                    <p class="font-semibold text-gray-700 mb-1">Editorial Policy Notice:</p>
                    The submission of an enquiry or excerpt does not constitute an offer, contract, or acceptance for publication. All submissions undergo initial editorial review. Full manuscripts are requested only after excerpt clearance.
                </div>

                <!-- Action Links -->
                <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('welcome') }}" class="text-xs font-bold text-gray-500 hover:text-brand-dark uppercase tracking-wider">
                        &larr; Return to Homepage
                    </a>

                    <div class="flex items-center space-x-3 w-full sm:w-auto">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-brand-dark text-white rounded text-xs font-bold uppercase tracking-wider hover:bg-gray-800 transition">
                            Author Sign In
                        </a>
                        <a href="{{ route('enquiry.index') }}" class="w-full sm:w-auto text-center px-5 py-3 border border-gray-300 text-gray-700 rounded text-xs font-bold uppercase tracking-wider hover:bg-gray-100 transition">
                            Submit Another Title
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-gray-200 py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-gray-500 flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="font-serif italic text-gray-900 font-bold">Griots Lounge Publishing</span>
            <span>&copy; {{ date('Y') }} Griots Lounge Publishing (Nigeria & Canada). All Rights Reserved.</span>
        </div>
    </footer>

</body>
</html>