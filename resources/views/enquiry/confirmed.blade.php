<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manuscript Submission Confirmed - The Curated Archive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            dark: '#0B1120',
                            accent: '#A67C00',
                            accentHover: '#8C6800',
                            surface: '#FFFFFF',
                            bg: '#FAFAFA'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-brand-bg font-sans text-gray-800 antialiased selection:bg-brand-accent selection:text-white flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('welcome') }}" class="font-serif italic font-bold text-2xl text-gray-900 tracking-tight">The Curated Archive</a>
                    <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-emerald-100 text-emerald-800">
                        Traditional Acquisition Pipeline
                    </span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Sign In</a>
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
            <div class="bg-emerald-900 text-white p-8 md:p-10 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-radial pointer-events-none"></div>
                <div class="w-16 h-16 bg-emerald-500/20 text-emerald-300 border border-emerald-400/40 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold shadow-inner">
                    ✓
                </div>
                <span class="text-xs uppercase tracking-[0.25em] text-emerald-300 font-bold block mb-2">Submission Successfully Ingested</span>
                <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mb-3">Pre-Qualified for Traditional Review</h1>
                <p class="text-emerald-100/80 max-w-xl mx-auto text-sm leading-relaxed">
                    Congratulations, <strong>{{ $prospect->name }}</strong>. Based on your audience reach, narrative classification, and literary profile, your manuscript has been pre-qualified for evaluation by our Senior Acquisitions Board.
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
                        </div>
                        <div class="text-left sm:text-right">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">System Reference</span>
                            <span class="font-mono text-base font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded inline-block">
                                #TCA-{{ str_pad($prospect->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 text-xs">
                        <div>
                            <span class="text-gray-400 block uppercase font-medium text-[10px]">Primary Genre</span>
                            <span class="font-semibold text-gray-800">{{ $prospect->genre }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block uppercase font-medium text-[10px]">Word Count</span>
                            <span class="font-semibold text-gray-800">{{ number_format($prospect->number_of_words) }} words</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block uppercase font-medium text-[10px]">Author Investment</span>
                            <span class="font-semibold text-emerald-700">₦0.00 / $0.00 (Waived)</span>
                        </div>
                        <div>
                            <span class="text-gray-400 block uppercase font-medium text-[10px]">Ingested On</span>
                            <span class="font-semibold text-gray-800">{{ $prospect->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Next Steps Roadmap -->
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">
                        What Happens Next
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-3.5 p-4 rounded border border-emerald-100 bg-emerald-50/40">
                            <div class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">1</div>
                            <div class="text-xs">
                                <span class="font-bold text-gray-900 block">Acquisitions Board Review</span>
                                <span class="text-gray-600">Our editorial reading team will assess your sample chapter and synopsis against current seasonal imprint needs.</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5 p-4 rounded border border-gray-100 bg-gray-50/60">
                            <div class="w-6 h-6 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">2</div>
                            <div class="text-xs">
                                <span class="font-bold text-gray-900 block">Editorial Response & Advance Terms</span>
                                <span class="text-gray-600">Within 5–7 business days, you will receive an editorial feedback digest and, if accepted, standard royalty advance terms.</span>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3.5 p-4 rounded border border-gray-100 bg-gray-50/60">
                            <div class="w-6 h-6 rounded-full bg-gray-300 text-gray-700 flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5">3</div>
                            <div class="text-xs">
                                <span class="font-bold text-gray-900 block">Production & Global Imprint Distribution</span>
                                <span class="text-gray-600">Publisher-funded copy editing, proofreading, cover typography, and distribution across Amazon, Selar, and partner bookstores.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action CTAs -->
                <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('welcome') }}" class="w-full sm:w-auto text-center px-6 py-3 border border-gray-300 text-gray-700 text-xs font-bold uppercase tracking-wider hover:bg-gray-50 transition">
                        Return to Catalog
                    </a>

                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-brand-dark text-white text-xs font-bold uppercase tracking-wider hover:bg-gray-800 transition">
                            Author Sign In
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-gray-200 py-6 bg-white">
        <div class="max-w-7xl mx-auto px-4 text-xs text-gray-500 text-center">
            &copy; {{ date('Y') }} The Curated Archive. All Rights Reserved.
        </div>
    </footer>

</body>

</html>

