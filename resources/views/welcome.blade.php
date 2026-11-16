<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Griots Lounge Publishing - Celebrating African & Global Literature</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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

    <!-- Header Navigation -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('welcome') }}" class="font-serif italic font-bold text-2xl text-brand-dark tracking-tight">Griots Lounge</a>
                </div>
                
                <div class="hidden md:flex items-center space-x-8 text-[11px] uppercase tracking-[0.2em] font-medium text-gray-600">
                    <a href="#about" class="hover:text-brand-terracotta transition-colors">About Us</a>
                    <a href="#publishing-options" class="hover:text-brand-terracotta transition-colors">Publishing Pathways</a>
                    <a href="#imprints" class="hover:text-brand-terracotta transition-colors">Imprints & Catalog</a>
                    <a href="{{ route('login') }}" class="hover:text-brand-terracotta transition-colors">Author Portal</a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-wider text-gray-700 hover:text-brand-terracotta hidden sm:inline-block">Sign In</a>
                    <a href="{{ route('enquiry.index') }}" class="bg-brand-terracotta text-white px-6 py-2.5 text-[11px] uppercase tracking-widest font-bold hover:bg-brand-terracottaHover transition-all rounded-sm shadow-sm">
                        Submit Excerpt
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative overflow-hidden bg-gradient-to-br from-[#00241C] via-[#00382B] to-[#001D17] text-white py-14 sm:py-20 border-b border-[#004D3C]">
        <!-- Ambient Atmospheric Background Elements -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#96BBA4]/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 -left-20 w-80 h-80 bg-[#005743]/25 rounded-full blur-3xl pointer-events-none"></div>
        
        <!-- Subtle Cultural Geometric Grid Overlay -->
        <div class="absolute inset-0 opacity-[0.05] pointer-events-none" style="background-image: radial-gradient(rgba(255,255,255,0.8) 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left: Editorial Narrative & Action -->
                <div class="lg:col-span-6 space-y-6">
                    <div class="inline-flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-[#96BBA4] text-[11px] font-semibold uppercase tracking-[0.2em] shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-[#96BBA4] animate-pulse"></span>
                        <span>Pan-African Literary House &bull; Est. 2012</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif text-white leading-[1.12] tracking-tight">
                        Preserving Stories, <br>
                        <span class="italic font-normal text-[#96BBA4]">Amplifying</span> Diverse Voices.
                    </h1>

                    <p class="text-stone-200/90 text-base sm:text-lg leading-relaxed max-w-xl font-light">
                        Rooted in the timeless oral tradition of the West African <em>Griot</em>, we nurture celebrated African literature, contemporary fiction, poetry, and narrative scholarship with uncompromising editorial craft and global distribution across Nigeria, Canada, and beyond.
                    </p>

                    <!-- CTAs -->
                    <div class="pt-2 flex flex-wrap items-center gap-4">
                        <a href="{{ route('enquiry.index') }}" class="inline-flex items-center space-x-2 bg-[#96BBA4] text-[#00241C] px-7 py-3.5 text-xs font-bold uppercase tracking-widest hover:bg-[#82A891] transition-all rounded-full shadow-lg shadow-black/25 hover:-translate-y-0.5">
                            <span>Submit Manuscript Excerpt</span>
                            <span class="text-[#00241C] font-bold">&rarr;</span>
                        </a>
                        <a href="#publishing-options" class="inline-flex items-center space-x-2 bg-white/10 hover:bg-white/20 text-white border border-white/30 backdrop-blur-sm px-6 py-3.5 text-xs font-bold uppercase tracking-widest transition-all rounded-full shadow-sm">
                            <span>Publishing Pathways</span>
                        </a>
                        <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-wider text-stone-300 hover:text-white transition py-2 px-1">
                            Author Sign In &rarr;
                        </a>
                    </div>

                    <!-- Trust / Metric Badges -->
                    <div class="grid grid-cols-3 gap-6 pt-6 border-t border-white/15 text-xs">
                        <div>
                            <span class="block text-2xl font-serif font-bold text-white">Hybrid &amp; Trad</span>
                            <span class="text-stone-300/80 text-[11px] leading-tight block mt-1">Subsidized &amp; 100% publisher-funded models</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-serif font-bold text-white">Up to 80%</span>
                            <span class="text-stone-300/80 text-[11px] leading-tight block mt-1">Author royalties under collaborative contracts</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-serif font-bold text-white">Global Reach</span>
                            <span class="text-stone-300/80 text-[11px] leading-tight block mt-1">Worldwide bookstore &amp; digital distribution</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Book Showcase Carousel (Matching griotslounge.ca) -->
                <div class="lg:col-span-6" x-data="{
                    active: 0,
                    autoplay: true,
                    timer: null,
                    books: [
                        {
                            title: 'Deer Crossing',
                            badge: 'Forthcoming 2026',
                            badgeColor: 'bg-[#EBF3EF] text-[#00382B] border-[#D1E3DA]',
                            genre: 'Literary Fiction &bull; Main Press',
                            synopsis: 'A lyrical, luminous contemporary novel exploring generational return, identity, and the quiet fault lines of migration.',
                            image: '{{ asset('assets/images/books/deer-crossing.jpg') }}'
                        },
                        {
                            title: 'Globetrotter & Hitler\'s Children',
                            badge: 'New Release',
                            badgeColor: 'bg-emerald-50 text-emerald-800 border-emerald-200',
                            genre: 'Memoir & Historical Witness',
                            synopsis: 'A gripping international account of resilience, cultural courage, and personal testimony spanning multiple continents.',
                            image: '{{ asset('assets/images/books/globetrotter.jpg') }}'
                        },
                        {
                            title: 'Dear Algorithm',
                            badge: 'Award Finalist',
                            badgeColor: 'bg-amber-50 text-amber-800 border-amber-200',
                            genre: 'Contemporary Poetry &bull; Main Press',
                            synopsis: 'Intimate and striking verse capturing the modern digital landscape intertwined with ancestral African cadences.',
                            image: '{{ asset('assets/images/books/dear-algorithm.png') }}'
                        },
                        {
                            title: 'Please Don\'t Interrupt',
                            badge: 'Nwata Imprint',
                            badgeColor: 'bg-teal-50 text-teal-800 border-teal-200',
                            genre: 'Illustrated Children\'s Literature',
                            synopsis: 'Vibrant storytelling crafted for young readers, celebrating curiosity, folklore, and rich oral traditions.',
                            image: '{{ asset('assets/images/books/please-dont-interrupt.png') }}'
                        },
                        {
                            title: 'Sons of the East',
                            badge: 'Bestseller',
                            badgeColor: 'bg-stone-100 text-stone-800 border-stone-300',
                            genre: 'Historical Fiction &bull; Epic Narrative',
                            synopsis: 'An evocative epic unravelling brotherhood, heritage, and the indelible triumphs of community history.',
                            image: '{{ asset('assets/images/books/sons-of-the-east.jpg') }}'
                        }
                    ],
                    next() {
                        this.active = (this.active + 1) % this.books.length;
                    },
                    prev() {
                        this.active = (this.active - 1 + this.books.length) % this.books.length;
                    },
                    init() {
                        this.timer = setInterval(() => {
                            if (this.autoplay) this.next();
                        }, 5000);
                    }
                }" @mouseenter="autoplay = false" @mouseleave="autoplay = true">
                    
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl shadow-black/40 border border-white/10 relative overflow-hidden text-stone-800">
                        <div class="absolute -top-12 -right-12 w-44 h-44 bg-brand-sage/15 rounded-full blur-2xl pointer-events-none"></div>

                        <!-- Showcase Top Bar -->
                        <div class="flex items-center justify-between pb-4 border-b border-[#E2ECE7] mb-6">
                            <div class="flex items-center space-x-2">
                                <span class="w-2 h-2 rounded-full bg-brand-emerald"></span>
                                <span class="text-[11px] uppercase tracking-[0.22em] font-bold text-[#00382B]">Griots Lounge Catalog Spotlight</span>
                            </div>
                            
                            <!-- Prev / Next Controls -->
                            <div class="flex items-center space-x-1.5">
                                <button @click="prev()" aria-label="Previous book" class="w-7 h-7 rounded-full border border-[#D1E3DA] flex items-center justify-center text-stone-600 hover:text-[#00382B] hover:border-[#00382B] hover:bg-[#F4F8F6] transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button @click="next()" aria-label="Next book" class="w-7 h-7 rounded-full border border-[#D1E3DA] flex items-center justify-center text-stone-600 hover:text-[#00382B] hover:border-[#00382B] hover:bg-[#F4F8F6] transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Active Spotlight Book Display -->
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-center">
                            <!-- Book Cover with 3D Realistic Shadow -->
                            <div class="sm:col-span-5 flex justify-center">
                                <div class="relative group cursor-pointer" @click="next()">
                                    <img :src="books[active].image" :alt="books[active].title" 
                                         class="w-40 sm:w-44 h-56 sm:h-60 object-cover rounded-md shadow-2xl shadow-stone-800/30 ring-1 ring-black/10 transform -rotate-1 group-hover:rotate-0 transition duration-300">
                                    <div class="absolute inset-0 rounded-md bg-gradient-to-tr from-black/10 to-transparent pointer-events-none"></div>
                                </div>
                            </div>

                            <!-- Book Metadata & Synopsis -->
                            <div class="sm:col-span-7 space-y-3">
                                <div>
                                    <span :class="books[active].badgeColor" 
                                          class="inline-block px-2.5 py-0.5 text-[10px] uppercase font-bold tracking-wider rounded-full border mb-2"
                                          x-text="books[active].badge"></span>
                                    <h3 class="font-serif font-bold text-2xl text-[#00382B] leading-tight" x-text="books[active].title"></h3>
                                    <p class="text-xs font-semibold text-brand-teal uppercase tracking-wider mt-1" x-text="books[active].genre"></p>
                                </div>

                                <p class="text-xs text-stone-600 leading-relaxed font-light" x-text="books[active].synopsis"></p>

                                <div class="pt-2">
                                    <a href="{{ route('enquiry.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-[#00382B] hover:text-brand-teal uppercase tracking-wider transition">
                                        <span>Publish With Us</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Thumbnail Shelf of Books -->
                        <div class="mt-6 pt-5 border-t border-[#E2ECE7]">
                            <div class="flex items-center justify-between text-[11px] text-stone-500 mb-3">
                                <span class="font-medium">Explore Titles in this Cycle:</span>
                                <span class="font-mono text-[10px]" x-text="`${active + 1} of ${books.length}`"></span>
                            </div>
                            <div class="grid grid-cols-5 gap-2 sm:gap-3">
                                <template x-for="(b, i) in books" :key="i">
                                    <button type="button" @click="active = i" 
                                            class="relative rounded-md overflow-hidden transition-all duration-200 group focus:outline-none"
                                            :class="active === i ? 'ring-2 ring-[#00382B] ring-offset-2 scale-105 shadow-md' : 'opacity-60 hover:opacity-100 hover:scale-102'">
                                        <img :src="b.image" :alt="b.title" class="w-full h-16 sm:h-20 object-cover rounded-sm">
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Ethos Footer -->
                        <div class="mt-5 pt-3 border-t border-[#E2ECE7]/60 flex items-center justify-between text-[11px] text-stone-500">
                            <span class="italic font-serif">"Amplifying African &amp; diverse minority voices"</span>
                            <span class="text-stone-400 text-[10px]">Nigeria &bull; Canada &bull; Global</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </header>

                            <!-- Book Metadata & Synopsis -->
                            <div class="sm:col-span-7 space-y-3">
                                <div>
                                    <span :class="books[active].badgeColor" 
                                          class="inline-block px-2.5 py-0.5 text-[10px] uppercase font-bold tracking-wider rounded-full border mb-2"
                                          x-text="books[active].badge"></span>
                                    <h3 class="font-serif font-bold text-2xl text-[#00382B] leading-tight" x-text="books[active].title"></h3>
                                    <p class="text-xs font-semibold text-brand-teal uppercase tracking-wider mt-1" x-text="books[active].genre"></p>
                                </div>

                                <p class="text-xs text-stone-600 leading-relaxed font-light" x-text="books[active].synopsis"></p>

                                <div class="pt-2">
                                    <a href="{{ route('enquiry.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-[#00382B] hover:text-brand-teal uppercase tracking-wider transition">
                                        <span>Publish With Us</span>
                                        <span>&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Thumbnail Shelf of Books -->
                        <div class="mt-6 pt-5 border-t border-[#E2ECE7]">
                            <div class="flex items-center justify-between text-[11px] text-stone-500 mb-3">
                                <span class="font-medium">Explore Titles in this Cycle:</span>
                                <span class="font-mono text-[10px]" x-text="`${active + 1} of ${books.length}`"></span>
                            </div>
                            <div class="grid grid-cols-5 gap-2 sm:gap-3">
                                <template x-for="(b, i) in books" :key="i">
                                    <button type="button" @click="active = i" 
                                            class="relative rounded-md overflow-hidden transition-all duration-200 group focus:outline-none"
                                            :class="active === i ? 'ring-2 ring-[#00382B] ring-offset-2 scale-105 shadow-md' : 'opacity-60 hover:opacity-100 hover:scale-102'">
                                        <img :src="b.image" :alt="b.title" class="w-full h-16 sm:h-20 object-cover rounded-sm">
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Ethos Footer -->
                        <div class="mt-5 pt-3 border-t border-[#E2ECE7]/60 flex items-center justify-between text-[11px] text-stone-500">
                            <span class="italic font-serif">"Amplifying African &amp; diverse minority voices"</span>
                            <span class="text-stone-400 text-[10px]">Nigeria &bull; Canada &bull; Global</span>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- Three Publishing Pathways Section (Point 6) -->
    <section id="publishing-options" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs uppercase tracking-widest font-bold text-brand-terracotta block mb-2">How We Work With Authors</span>
                <h2 class="text-3xl md:text-4xl font-serif text-brand-dark">Our Publishing Pathways</h2>
                <p class="text-gray-500 text-sm mt-3 leading-relaxed">
                    Whether you are an emerging voice seeking collaborative publication or an established author ready for traditional acquisition, Griots Lounge provides rigorous editorial stewardship.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Pathway 1: Hybrid Publishing -->
                <div class="bg-brand-bg p-8 border border-gray-200 rounded-sm flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-brand-terracotta/10 text-brand-terracotta flex items-center justify-center font-bold text-sm mb-4">
                            01
                        </div>
                        <span class="text-[10px] uppercase tracking-widest font-bold text-brand-terracotta block mb-1">Collaborative Model</span>
                        <h3 class="text-xl font-serif font-bold text-brand-dark mb-3">Hybrid Publishing</h3>
                        <p class="text-xs text-gray-600 leading-relaxed mb-6">
                            A dynamic partnership where the author contributes to subsidized production while retaining up to <strong>70%–80% royalties</strong>. Griots Lounge delivers developmental editing, bespoke cover design, professional typesetting, ISBN application, and international distribution across print and digital.
                        </p>
                        <ul class="text-xs text-gray-500 space-y-2 mb-6 border-t pt-4">
                            <li>✓ Author retains copyright</li>
                            <li>✓ Subsidized press runs & POD</li>
                            <li>✓ High author royalty split</li>
                        </ul>
                    </div>
                    <a href="{{ route('enquiry.index') }}" class="text-xs font-bold text-brand-terracotta uppercase tracking-wider hover:underline">
                        Explore Hybrid Quote &rarr;
                    </a>
                </div>

                <!-- Pathway 2: Traditional Publishing -->
                <div class="bg-brand-bg p-8 border border-gray-200 rounded-sm flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm mb-4">
                            02
                        </div>
                        <span class="text-[10px] uppercase tracking-widest font-bold text-emerald-700 block mb-1">Publisher Funded</span>
                        <h3 class="text-xl font-serif font-bold text-brand-dark mb-3">Traditional Publishing</h3>
                        <p class="text-xs text-gray-600 leading-relaxed mb-6">
                            100% house-funded. <strong>Zero production fees to the author</strong>. Griots Lounge finances editorial development, cover art, book manufacturing, marketing, and bookstore distribution. Authors receive a standard advance and royalty contract. Highly selective curation through our acquisitions board.
                        </p>
                        <ul class="text-xs text-gray-500 space-y-2 mb-6 border-t pt-4">
                            <li>✓ Zero upfront or production fees</li>
                            <li>✓ Standard advance & royalties</li>
                            <li>✓ Direct commercial press run</li>
                        </ul>
                    </div>
                    <a href="{{ route('enquiry.index') }}" class="text-xs font-bold text-emerald-700 uppercase tracking-wider hover:underline">
                        Submit for Traditional Review &rarr;
                    </a>
                </div>

                <!-- Pathway 3: Advisory -->
                <div class="bg-brand-bg p-8 border border-gray-200 rounded-sm flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-900 flex items-center justify-center font-bold text-sm mb-4">
                            03
                        </div>
                        <span class="text-[10px] uppercase tracking-widest font-bold text-brand-accent block mb-1">Editorial Appraisal</span>
                        <h3 class="text-xl font-serif font-bold text-brand-dark mb-3">Advise Me / Assessment</h3>
                        <p class="text-xs text-gray-600 leading-relaxed mb-6">
                            Unsure which model best fits your manuscript? Submit your synopsis and 3,000-word excerpt. Our editors will conduct an appraisal of your book's literary merit and commercial marketability, recommending the optimal publishing route.
                        </p>
                        <ul class="text-xs text-gray-500 space-y-2 mb-6 border-t pt-4">
                            <li>✓ Structured editorial assessment</li>
                            <li>✓ Commercial viability analysis</li>
                            <li>✓ Tailored contract proposal</li>
                        </ul>
                    </div>
                    <a href="{{ route('enquiry.index') }}" class="text-xs font-bold text-brand-accent uppercase tracking-wider hover:underline">
                        Request Advisory Review &rarr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Imprints & Editorial Focus -->
    <section id="imprints" class="py-20 bg-brand-bg border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-xs uppercase tracking-widest font-bold text-brand-terracotta block mb-2">Our Imprints</span>
                    <h2 class="text-3xl font-serif font-bold text-brand-dark mb-4">Crafting Books with Cultural Reverence</h2>
                    <p class="text-sm text-gray-600 leading-relaxed mb-6">
                        From compelling African novels and poignant poetry chapbooks to our dedicated children's literature imprint <strong>Nwata</strong> and assisted publishing imprint <strong>Book Form</strong>, Griots Lounge ensures every title meets rigorous production and typography standards.
                    </p>
                    <div class="space-y-3 text-xs text-gray-700">
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-brand-terracotta"></span>
                            <span><strong>Griots Lounge Main Press:</strong> Fiction, Memoirs, Narrative Non-Fiction, Poetry</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-brand-accent"></span>
                            <span><strong>Nwata Imprint:</strong> Illustrated Children’s Literature & Young Readers</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                            <span><strong>Book Form:</strong> Bespoke Author-Assisted Production & Institutional Memoirs</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-8 border border-gray-200 rounded-sm shadow-sm">
                    <span class="text-xs uppercase tracking-widest font-bold text-gray-400 block mb-3">Submission Snapshot</span>
                    <h3 class="text-xl font-serif font-bold text-gray-900 mb-2">Initial Submission Guidelines</h3>
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">
                        To maintain thorough evaluation timelines, our initial review process requires only:
                    </p>
                    <ol class="list-decimal pl-5 text-xs text-gray-700 space-y-2 mb-6">
                        <li>Author biography & background summary.</li>
                        <li>Comprehensive book synopsis (plot/themes, 200–600 words).</li>
                        <li><strong>Manuscript excerpt of up to 3,000 words</strong> (or first chapter).</li>
                    </ol>
                    <a href="{{ route('enquiry.index') }}" class="block w-full text-center bg-brand-dark text-white py-3 text-xs font-bold uppercase tracking-wider hover:bg-gray-800 transition rounded-sm">
                        Submit Your Excerpt Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <span class="font-serif italic text-2xl text-brand-dark font-bold block">Griots Lounge Publishing</span>
                    <p class="text-xs text-gray-500 mt-1">Preserving narratives • Celebrating culture • Amplifying diverse voices</p>
                </div>
                
                <div class="flex flex-wrap items-center gap-6 text-xs text-gray-600 font-medium">
                    <a href="{{ route('enquiry.index') }}" class="hover:text-brand-terracotta transition">Submit Excerpt</a>
                    <a href="{{ route('login') }}" class="hover:text-brand-terracotta transition">Author Portal</a>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center text-xs text-gray-400 gap-4">
                <span>&copy; {{ date('Y') }} Griots Lounge Publishing (Nigeria & Canada). All Rights Reserved.</span>
                <span class="text-[11px]">Nigeria Headquarters &bull; Winnipeg, Manitoba, Canada</span>
            </div>
        </div>
    </footer>

</body>
</html>
