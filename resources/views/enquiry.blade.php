<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publishing Enquiry & Triage - The Curated Archive</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                    <span class="hidden sm:inline-block px-2.5 py-0.5 rounded-full text-[10px] font-semibold tracking-wider uppercase bg-brand-accent/10 text-brand-accent">
                        Osisi Hybrid & Traditional
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
    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="triageForm()" x-cloak>
        
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-5xl font-serif text-gray-900 mb-2">Publishing Submission & Triage</h1>
                    <p class="text-gray-600 max-w-2xl text-sm md:text-base leading-relaxed">
                        Submit your manuscript for automated model evaluation. Our triage engine dynamically evaluates your project for either Traditional Acquisition review or the high-velocity Osisi Hybrid distribution pipeline.
                    </p>
                </div>

                <!-- Currency Localisation Control Toggle -->
                <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm self-start md:self-auto flex items-center space-x-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider pl-2">Currency:</span>
                    <button type="button" @click="setCurrency('USD')" 
                            :class="currency === 'USD' ? 'bg-brand-dark text-white font-bold' : 'text-gray-600 hover:bg-gray-100'"
                            class="px-3 py-1.5 rounded-md text-xs transition-colors flex items-center space-x-1.5">
                        <span>🌐 International ($ USD)</span>
                    </button>
                    <button type="button" @click="setCurrency('NGN')" 
                            :class="currency === 'NGN' ? 'bg-brand-accent text-white font-bold' : 'text-gray-600 hover:bg-gray-100'"
                            class="px-3 py-1.5 rounded-md text-xs transition-colors flex items-center space-x-1.5">
                        <span>🌍 West Africa (₦ NGN)</span>
                    </button>
                </div>
            </div>
        </div>

        @if (session('success'))
        <div class="mb-10 bg-white border-2 border-emerald-500 rounded-lg p-6 md:p-8 shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-gray-100">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center font-bold text-xl flex-shrink-0">
                        ✓
                    </div>
                    <div>
                        <span class="text-xs uppercase tracking-widest font-bold text-emerald-700">Manuscript Ingested Successfully</span>
                        <h3 class="text-xl md:text-2xl font-serif font-bold text-gray-900">{{ session('submitted_title', 'Your Manuscript Submission') }}</h3>
                    </div>
                </div>
                <div class="bg-gray-50 border border-gray-200 px-4 py-2 rounded text-right">
                    <span class="text-[10px] uppercase font-bold text-gray-400 block tracking-wider">Reference Tracker</span>
                    <span class="font-mono font-bold text-base text-gray-800">#TCA-{{ str_pad(session('submitted_prospect_id', rand(100, 999)), 5, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <div class="pt-5 space-y-4">
                <p class="text-sm text-gray-700 leading-relaxed">
                    {{ session('success') }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <div class="bg-gray-50 p-4 rounded border border-gray-100 text-xs space-y-1">
                        <span class="font-bold text-gray-900 block">Classified Pathway:</span>
                        <span class="text-gray-600">
                            @if(session('submitted_model') === 'traditional')
                                🟢 <strong>Traditional Acquisition Board</strong> (Full advance & zero-cost author production review)
                            @else
                                🟡 <strong>Osisi Hybrid Model</strong> (High-velocity 80% royalty digital & physical distribution)
                            @endif
                        </span>
                    </div>

                    <div class="bg-gray-50 p-4 rounded border border-gray-100 text-xs space-y-1">
                        <span class="font-bold text-gray-900 block">Electronic Terms Executed:</span>
                        <span class="text-gray-600">
                            Digital submission agreement automatically signed and counter-signed under IP protection.
                        </span>
                    </div>
                </div>

                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ route('login') }}" class="bg-brand-dark text-white px-5 py-2.5 rounded text-xs font-bold uppercase tracking-wider hover:bg-gray-800 transition">
                        Author Sign In & Portal
                    </a>
                    <a href="{{ route('enquiry.index') }}" class="border border-gray-300 text-gray-700 px-5 py-2.5 rounded text-xs font-bold uppercase tracking-wider hover:bg-gray-100 transition">
                        Submit Another Manuscript
                    </a>
                </div>
            </div>
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-8 bg-red-50 text-red-900 p-5 rounded-md border border-red-200 shadow-sm">
            <h4 class="font-bold text-sm mb-2">Please correct the following errors:</h4>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('enquiry.store') }}" method="POST" enctype="multipart/form-data" @submit="isSubmitting = true">
            @csrf
            
            <input type="hidden" name="marketing_source" value="{{ $utmSource ?? request('utm_source', 'direct') }}">
            <input type="hidden" name="currency" :value="currency">

            <div class="flex flex-col lg:flex-row gap-8 lg:gap-10">

                <!-- Left Column: Interactive Form -->
                <div class="w-full lg:w-7/12 space-y-8">

                    <!-- Section 1: Author Demographics & Triage Parameters -->
                    <section class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-100">
                            <span class="w-7 h-7 bg-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs">1</span>
                            <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">Author Demographics</h2>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Full Legal Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g., Dr. Amina Yusuf"
                                       x-model="authorName"
                                       class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="author@domain.com"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">WhatsApp / Phone <span class="text-red-500">*</span></label>
                                    <input type="tel" name="phone_number" required value="{{ old('phone_number') }}" placeholder="+234 800 000 0000"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                                </div>
                            </div>

                            <!-- Triage Input: Author Age -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                        Author Age <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="author_age" min="15" max="110" required 
                                           x-model.number="authorAge"
                                           placeholder="e.g. 52"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                                    <span class="text-[11px] text-gray-400 mt-1 block">Required for publishing pathway evaluation</span>
                                </div>

                                <div class="space-y-3 pt-1">
                                    <label class="flex items-start space-x-2.5 cursor-pointer">
                                        <input type="checkbox" name="has_social_presence" value="1" 
                                               x-model="hasSocialPresence"
                                               class="w-4 h-4 mt-0.5 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                        <div class="text-xs text-gray-700">
                                            <span class="font-semibold block">Social Media Presence</span>
                                            <span class="text-gray-500">I have an engaged digital follower audience</span>
                                        </div>
                                    </label>

                                    <label class="flex items-start space-x-2.5 cursor-pointer">
                                        <input type="checkbox" name="is_established_author" value="1" 
                                               x-model="isEstablishedAuthor"
                                               class="w-4 h-4 mt-0.5 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                        <div class="text-xs text-gray-700">
                                            <span class="font-semibold block">Established Author</span>
                                            <span class="text-gray-500">I have previously published books or catalog</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section 2: Book Details & Genre Rate Metric -->
                    <section class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-100">
                            <span class="w-7 h-7 bg-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs">2</span>
                            <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">Manuscript Dimensions</h2>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Working Book Title <span class="text-red-500">*</span></label>
                                <input type="text" name="book_title" required value="{{ old('book_title') }}" placeholder="e.g. Chronicles of the Palm Groves"
                                       class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Primary Genre <span class="text-red-500">*</span></label>
                                    
                                    <select name="genre" id="genre-select" required x-model="genre" @change="genre = $event.target.value"
                                            class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                                        <option value="Fiction" 
                                                x-text="currency === 'NGN' ? 'Fiction (₦' + Number(0.010 * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '/word)' : 'Fiction ($0.010/word)'"
                                                data-usd="Fiction ($0.010/word)" 
                                                data-ngn="Fiction (₦15.00/word)">Fiction ($0.010/word)</option>
                                        <option value="Non-Fiction" 
                                                x-text="currency === 'NGN' ? 'Non-Fiction (₦' + Number(0.012 * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '/word)' : 'Non-Fiction ($0.012/word)'"
                                                data-usd="Non-Fiction ($0.012/word)" 
                                                data-ngn="Non-Fiction (₦18.00/word)">Non-Fiction ($0.012/word)</option>
                                        <option value="Biography/Memoir" 
                                                x-text="currency === 'NGN' ? 'Biography / Memoir (₦' + Number(0.014 * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '/word)' : 'Biography / Memoir ($0.014/word)'"
                                                data-usd="Biography / Memoir ($0.014/word)" 
                                                data-ngn="Biography / Memoir (₦21.00/word)">Biography / Memoir ($0.014/word)</option>
                                        <option value="Sci-Fi/Fantasy" 
                                                x-text="currency === 'NGN' ? 'Sci-Fi / Fantasy (₦' + Number(0.015 * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '/word)' : 'Sci-Fi / Fantasy ($0.015/word)'"
                                                data-usd="Sci-Fi / Fantasy ($0.015/word)" 
                                                data-ngn="Sci-Fi / Fantasy (₦22.50/word)">Sci-Fi / Fantasy ($0.015/word)</option>
                                        <option value="Academic/Business" 
                                                x-text="currency === 'NGN' ? 'Academic / Business (₦' + Number(0.018 * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '/word)' : 'Academic / Business ($0.018/word)'"
                                                data-usd="Academic / Business ($0.018/word)" 
                                                data-ngn="Academic / Business (₦27.00/word)">Academic / Business ($0.018/word)</option>
                                    </select>

                                    <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                        <span>Active Word Rate: <strong class="text-brand-dark font-bold font-mono" x-text="genreRateLabel"></strong></span>
                                        <span class="text-[11px] text-gray-400">Live rate per word</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Total Word Count <span class="text-red-500">*</span></label>
                                    <input type="number" name="number_of_words" min="500" required 
                                           x-model.number="wordCount"
                                           placeholder="e.g. 65000"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Manuscript Stage</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center space-x-2 border p-3 rounded cursor-pointer hover:bg-gray-50"
                                           :class="stage === 'Completed' ? 'border-brand-dark bg-gray-50' : 'border-gray-200'">
                                        <input type="radio" name="stage_of_manuscript" value="Completed" x-model="stage" class="text-brand-dark focus:ring-brand-dark">
                                        <span class="text-xs font-medium text-gray-800">Completed Draft</span>
                                    </label>
                                    <label class="flex items-center space-x-2 border p-3 rounded cursor-pointer hover:bg-gray-50"
                                           :class="stage === 'In Progress' ? 'border-brand-dark bg-gray-50' : 'border-gray-200'">
                                        <input type="radio" name="stage_of_manuscript" value="In Progress" x-model="stage" class="text-brand-dark focus:ring-brand-dark">
                                        <span class="text-xs font-medium text-gray-800">In-Progress WIP</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section 3: Add-On Production Services (Hidden if Traditional) -->
                    <section x-show="!isTraditional" x-transition.opacity.duration.300ms
                             class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-100">
                            <span class="w-7 h-7 bg-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs">3</span>
                            <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">Add-On Production Services</h2>
                        </div>

                        <div class="space-y-3">
                            <label class="flex items-start space-x-3 p-3.5 border rounded cursor-pointer hover:bg-gray-50 transition"
                                   :class="includeProofreading ? 'border-brand-accent bg-amber-50/20' : 'border-gray-200'">
                                <input type="checkbox" name="include_proofreading" value="1" x-model="includeProofreading"
                                       class="w-4 h-4 mt-0.5 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                <div class="flex-1 flex justify-between items-center">
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Professional Proofreading</span>
                                        <span class="text-xs text-gray-500">Orthographic line edit and grammatical refinement</span>
                                    </div>
                                    <span class="text-xs font-bold text-brand-dark" x-text="formatCurrency(cleanWordCount * 0.005) + ' (+' + (currency === 'NGN' ? '₦' + (0.005 * exchangeRate).toFixed(2) : '$0.005') + '/w)'"></span>
                                </div>
                            </label>

                            <label class="flex items-start space-x-3 p-3.5 border rounded cursor-pointer hover:bg-gray-50 transition"
                                   :class="includeCoverDesign ? 'border-brand-accent bg-amber-50/20' : 'border-gray-200'">
                                <input type="checkbox" name="include_cover_design" value="1" x-model="includeCoverDesign"
                                       class="w-4 h-4 mt-0.5 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                <div class="flex-1 flex justify-between items-center">
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Custom Cover Design Package</span>
                                        <span class="text-xs text-gray-500">Bespoke print-ready jacket, barcode alignment & 3D mockups</span>
                                    </div>
                                    <span class="text-xs font-bold text-brand-dark" x-text="formatCurrency(300)"></span>
                                </div>
                            </label>

                            <label class="flex items-start space-x-3 p-3.5 border rounded cursor-pointer hover:bg-gray-50 transition"
                                   :class="includeMarketingPackage ? 'border-brand-accent bg-amber-50/20' : 'border-gray-200'">
                                <input type="checkbox" name="include_marketing_package" value="1" x-model="includeMarketingPackage"
                                       class="w-4 h-4 mt-0.5 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                <div class="flex-1 flex justify-between items-center">
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Author Marketing & Launch Package</span>
                                        <span class="text-xs text-gray-500">ARC campaign distribution, store blurb optimization & pre-order setup</span>
                                    </div>
                                    <span class="text-xs font-bold text-brand-dark" x-text="formatCurrency(500)"></span>
                                </div>
                            </label>
                        </div>
                    </section>

                    <!-- Section 4: Physical Bulk Print Calculator (Hidden if Traditional) -->
                    <section x-show="!isTraditional" x-transition.opacity.duration.300ms
                             class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-100">
                            <span class="w-7 h-7 bg-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs">4</span>
                            <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">Physical Bulk Print Calculator</h2>
                        </div>

                        <div class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Book Trim Size <span class="text-red-500">*</span></label>
                                    <select name="trim_size" x-model="trimSize"
                                            class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                                        <option value="five_by_eight">5" x 8" Pocket (300 words/page)</option>
                                        <option value="six_by_nine">6" x 9" Trade Paperback (350 words/page)</option>
                                        <option value="seven_by_ten">7" x 10" Executive (400 words/page)</option>
                                    </select>
                                    <span class="text-[11px] text-gray-500 mt-1 block">
                                        Estimated Book Pages: <strong class="text-gray-900" x-text="estimatedPages"></strong>
                                    </span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Binding Format <span class="text-red-500">*</span></label>
                                    
                                    <select name="binding_type" id="binding-select" required x-model="bindingType" @change="bindingType = $event.target.value"
                                            class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                                        <option value="paperback" 
                                                x-text="currency === 'NGN' ? 'Standard Paperback (₦' + Number(2.50 * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' base)' : 'Standard Paperback ($2.50 base)'"
                                                data-usd="Standard Paperback ($2.50 base)" 
                                                data-ngn="Standard Paperback (₦3,750.00 base)">Standard Paperback ($2.50 base)</option>
                                        <option value="hardcover" 
                                                x-text="currency === 'NGN' ? 'Premium Hardcover (₦' + Number(6.00 * exchangeRate).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' base)' : 'Premium Hardcover ($6.00 base)'"
                                                data-usd="Premium Hardcover ($6.00 base)" 
                                                data-ngn="Premium Hardcover (₦9,000.00 base)">Premium Hardcover ($6.00 base)</option>
                                    </select>

                                    <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                        <span>Unit Press Cost: <strong class="text-brand-dark font-bold font-mono" x-text="formatCurrency(unitPrintCostUsd) + ' / copy'"></strong></span>
                                        <span class="text-[11px] text-gray-400" x-text="estimatedPages + ' pgs'"></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                    Physical Print Quantity (Minimum 500 Copies) <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center space-x-4">
                                    <input type="number" name="print_quantity" min="500" step="50"
                                           x-model.number="printQuantity"
                                           class="w-40 bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 font-bold focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                                    <span class="text-xs text-gray-500">Enforced partner press minimum run: 500 copies.</span>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded border border-gray-200 text-xs flex justify-between items-center">
                                <div>
                                    <span class="font-bold text-gray-700 block">Estimated Unit Printing Cost:</span>
                                    <span class="text-gray-500" x-text="formatCurrency(unitPrintCostUsd) + ' per manufactured copy'"></span>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold text-gray-700 block">Total Bulk Press Allocation:</span>
                                    <span class="text-sm font-bold text-brand-dark" x-text="formatCurrency(totalBulkPrintUsd)"></span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section 5: Manuscript Upload & Electronic Verification -->
                    <section class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-100">
                            <span class="w-7 h-7 bg-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs">5</span>
                            <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">File Assets & Verification</h2>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                    Manuscript Document (.pdf, .doc, .docx) <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="manuscript_file" required accept=".pdf,.doc,.docx"
                                       class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                <span class="text-[11px] text-gray-400 mt-1 block">Please attach your full or excerpt manuscript file.</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Cover Concept (Optional)</label>
                                <input type="file" name="cover_design_file" accept=".jpeg,.png,.jpg,.pdf"
                                       class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            </div>

                            <div class="pt-4 border-t border-gray-100 space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Author Electronic Signature Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="agreement_name" required placeholder="Type your full legal name to verify"
                                           :value="signatureName || authorName"
                                           @input="signatureName = $event.target.value"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-dark focus:ring-0 transition">
                                </div>

                                <label class="flex items-start space-x-2.5 cursor-pointer">
                                    <input type="checkbox" name="agreement_terms" value="1" checked required
                                           class="w-4 h-4 mt-0.5 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                    <span class="text-xs text-gray-600">
                                        I hereby certify that I hold full legal intellectual property rights to this work and agree to the submission evaluation terms.
                                    </span>
                                </label>
                            </div>
                        </div>
                    </section>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                :disabled="isSubmitting"
                                class="w-full py-4 bg-brand-dark text-white font-serif tracking-widest text-sm uppercase font-bold hover:bg-gray-800 transition shadow-sm disabled:opacity-50">
                            <span x-show="!isSubmitting">Submit Manuscript for Evaluation</span>
                            <span x-show="isSubmitting">Transmitting Document Package...</span>
                        </button>
                    </div>

                </div>

                <!-- Right Column: Live Dynamic Overview Panel -->
                <div class="w-full lg:w-5/12">
                    <div class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm sticky top-28 space-y-6">

                        <!-- Triage Category Indicator Badge -->
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Automated Triage Classification</span>
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full" :class="isTraditional ? 'bg-emerald-500 animate-pulse' : 'bg-brand-accent'"></span>
                                <span class="font-serif font-bold text-lg text-gray-900" x-text="isTraditional ? 'Traditional Publishing Candidate' : 'Osisi Hybrid Publishing Model'"></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1" x-text="triageExplanation"></p>
                        </div>

                        <!-- CASE 1: TRADITIONAL CANDIDATE BANNER -->
                        <template x-if="isTraditional">
                            <div class="bg-emerald-50 border border-emerald-200 rounded-sm p-5 space-y-3">
                                <div class="flex items-center space-x-2 text-emerald-800 font-bold text-sm">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    <span>Pre-Qualified Acquisition Candidate</span>
                                </div>
                                <p class="text-xs text-emerald-900 leading-relaxed">
                                    Based on your demographic profile, audience reach, and high-potential fiction classification, your project is pre-qualified for <strong>Traditional Acquisition Board Review</strong>.
                                </p>
                                <div class="bg-white/80 p-3 rounded text-[11px] text-emerald-950 font-medium">
                                    ✓ All setup fees waived<br>
                                    ✓ All editorial production funded by publisher<br>
                                    ✓ Bulk print module bypassed
                                </div>
                                <p class="text-[11px] text-emerald-700 italic">
                                    Upon submission, the Acquisition Admin team will review your manuscript chapter proof for advance offer terms.
                                </p>
                            </div>
                        </template>

                        <!-- CASE 2: OSISI HYBRID DYNAMIC COST BREAKDOWN -->
                        <template x-if="!isTraditional">
                            <div class="space-y-4">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100 pb-2">
                                    Project Financial Overview
                                </h3>

                                <div class="space-y-2.5 text-xs text-gray-600">
                                    <div class="flex justify-between items-center">
                                        <span>Base Operations Fee</span>
                                        <span class="font-semibold text-gray-900" x-text="formatCurrency(150)"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span>Genre Editorial Fee (<span x-text="genreRateLabel"></span> • <span x-text="cleanWordCount.toLocaleString() + ' words'"></span>)</span>
                                        <span class="font-semibold text-gray-900" x-text="formatCurrency(genreWordFeeUsd)"></span>
                                    </div>

                                    <template x-if="includeProofreading">
                                        <div class="flex justify-between items-center text-brand-dark">
                                            <span>Proofreading Service</span>
                                            <span class="font-semibold" x-text="formatCurrency(cleanWordCount * 0.005)"></span>
                                        </div>
                                    </template>

                                    <template x-if="includeCoverDesign">
                                        <div class="flex justify-between items-center text-brand-dark">
                                            <span>Cover Design Package</span>
                                            <span class="font-semibold" x-text="formatCurrency(300)"></span>
                                        </div>
                                    </template>

                                    <template x-if="includeMarketingPackage">
                                        <div class="flex justify-between items-center text-brand-dark">
                                            <span>Marketing Launch Package</span>
                                            <span class="font-semibold" x-text="formatCurrency(500)"></span>
                                        </div>
                                    </template>

                                    <div class="pt-2 border-t border-dashed border-gray-200 flex justify-between items-center font-medium text-gray-700">
                                        <span>Subtotal Editorial & Design</span>
                                        <span class="font-bold text-gray-900" x-text="formatCurrency(totalEditorialFeeUsd)"></span>
                                    </div>

                                    <div class="flex justify-between items-center pt-1 text-gray-700">
                                        <div>
                                            <span>Bulk Physical Print (<span x-text="printQuantity"></span> copies)</span>
                                            <span class="block text-[10px] text-gray-400" x-text="estimatedPages + ' pgs • ' + bindingType"></span>
                                        </div>
                                        <span class="font-bold text-gray-900" x-text="formatCurrency(totalBulkPrintUsd)"></span>
                                    </div>
                                </div>

                                <!-- Grand Total -->
                                <div class="pt-4 border-t-2 border-gray-900 flex justify-between items-baseline">
                                    <div>
                                        <span class="text-xs uppercase font-bold text-gray-500 tracking-wider block">Estimated Total</span>
                                        <span class="text-[10px] text-gray-400" x-text="'Merchant Gateway: ' + (currency === 'USD' ? 'Stripe International' : 'Paystack Nigeria')"></span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-2xl font-serif font-bold text-brand-dark" x-text="formatCurrency(grandTotalUsd)"></span>
                                    </div>
                                </div>

                                <!-- Gateway Badge -->
                                <div class="bg-gray-50 p-3 rounded flex items-center justify-between text-[11px] text-gray-500">
                                    <span>Routing Merchant:</span>
                                    <span class="font-bold text-gray-800" x-text="currency === 'USD' ? '💳 Stripe Payment Intent' : '⚡ Paystack Naira Gateway'"></span>
                                </div>
                            </div>
                        </template>

                        <!-- Legal Disclaimer Notice -->
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-[11px] text-gray-400 italic leading-relaxed">
                                {{ $disclaimer }}
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-gray-200 py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-gray-500 flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="font-serif italic text-gray-900 font-bold">The Curated Archive</span>
            <span>&copy; {{ date('Y') }} The Curated Archive. All Rights Reserved.</span>
        </div>
    </footer>

    <!-- Alpine.js Dynamic Triage & Calculation Engine -->
    <script>
        function triageForm() {
            return {
                currency: 'USD',
                exchangeRate: {{ (float) ($exchangeRate ?? 1500) }},
                isSubmitting: false,
                
                // Author Demographics
                authorName: '{{ old('name', '') }}',
                signatureName: '{{ old('agreement_name', '') }}',
                authorAge: {{ (int) old('author_age', 32) }},
                hasSocialPresence: false,
                isEstablishedAuthor: false,

                // Manuscript Dimensions
                genre: '{{ old('genre', 'Fiction') }}',
                wordCount: {{ (int) old('number_of_words', 60000) }},
                stage: 'Completed',

                // Add-ons
                includeProofreading: false,
                includeCoverDesign: true,
                includeMarketingPackage: false,

                // Production specs
                trimSize: 'six_by_nine',
                bindingType: 'paperback',
                printQuantity: 500,

                init() {
                    this.updateDropdownLabels();
                },

                setCurrency(curr) {
                    this.currency = curr;
                    this.$nextTick(() => {
                        this.updateDropdownLabels();
                    });
                },

                updateDropdownLabels() {
                    let isNgn = (this.currency === 'NGN');
                    
                    let genreSelect = document.getElementById('genre-select');
                    if (genreSelect) {
                        for (let i = 0; i < genreSelect.options.length; i++) {
                            let opt = genreSelect.options[i];
                            let label = isNgn ? opt.getAttribute('data-ngn') : opt.getAttribute('data-usd');
                            if (label) {
                                opt.text = label;
                                opt.textContent = label;
                            }
                        }
                    }

                    let bindingSelect = document.getElementById('binding-select');
                    if (bindingSelect) {
                        for (let i = 0; i < bindingSelect.options.length; i++) {
                            let opt = bindingSelect.options[i];
                            let label = isNgn ? opt.getAttribute('data-ngn') : opt.getAttribute('data-usd');
                            if (label) {
                                opt.text = label;
                                opt.textContent = label;
                            }
                        }
                    }
                },

                // Triage Logic
                get isTraditional() {
                    let g = (this.genre || '').toLowerCase();
                    let isNonFiction = g.includes('non') || g.includes('biography') || g.includes('memoir') || g.includes('academic') || g.includes('business');
                    let isHighPotentialFiction = !isNonFiction && (g.includes('fiction') || g.includes('sci-fi') || g.includes('fantasy'));

                    let age = parseInt(this.authorAge, 10);
                    let isUnder35 = !isNaN(age) && age > 0 && age < 35;
                    let isSenior = !isNaN(age) && age >= 50;

                    // 1. Strict Osisi Hybrid: Age >= 50 and Non-Fiction
                    if (isSenior && isNonFiction) {
                        return false;
                    }

                    // 2. Platform audience or established track record routes to Traditional
                    if (this.hasSocialPresence || this.isEstablishedAuthor) {
                        return true;
                    }

                    // 3. Breakout young talent (<35) writing high-potential fiction
                    if (isHighPotentialFiction && isUnder35) {
                        return true;
                    }

                    // All other submissions (including non-fiction without existing platform) route to Osisi Hybrid
                    return false;
                },

                get triageExplanation() {
                    if (this.isTraditional) {
                        return 'Pre-qualified for Traditional Board Acquisition evaluation.';
                    }
                    if (this.authorAge >= 50) {
                        return 'Classified under the Osisi Hybrid model (demographic & non-fiction specialization).';
                    }
                    return 'Classified under the Osisi Hybrid publishing & print distribution pipeline.';
                },

                get wordsPerPage() {
                    if (this.trimSize === 'five_by_eight') return 300;
                    if (this.trimSize === 'seven_by_ten') return 400;
                    return 350; // six_by_nine default
                },

                get cleanWordCount() {
                    let n = parseInt(this.wordCount, 10);
                    return isNaN(n) || n < 1 ? 0 : n;
                },

                get estimatedPages() {
                    let w = Math.max(500, this.cleanWordCount);
                    return Math.max(40, Math.ceil(w / this.wordsPerPage));
                },

                get genreRateUsd() {
                    let g = (this.genre || '').toLowerCase();
                    if (g.includes('academic') || g.includes('business')) return 0.018;
                    if (g.includes('sci-fi') || g.includes('fantasy')) return 0.015;
                    if (g.includes('biography') || g.includes('memoir')) return 0.014;
                    if (g.includes('non')) return 0.012;
                    return 0.010; // Fiction default
                },

                get genreWordFeeUsd() {
                    return this.cleanWordCount * this.genreRateUsd;
                },

                get genreRateLabel() {
                    if (this.currency === 'NGN') {
                        let ngnRate = (this.genreRateUsd * this.exchangeRate).toFixed(2);
                        return '₦' + Number(ngnRate).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '/w';
                    }
                    return '$' + this.genreRateUsd.toFixed(3) + '/w';
                },

                get totalEditorialFeeUsd() {
                    let total = 150.00; // Base Operations Fee
                    total += this.genreWordFeeUsd;
                    if (this.includeProofreading) total += this.cleanWordCount * 0.005;
                    if (this.includeCoverDesign) total += 300.00;
                    if (this.includeMarketingPackage) total += 500.00;
                    return total;
                },

                get unitPrintCostUsd() {
                    let isHard = (this.bindingType === 'hardcover');
                    let base = isHard ? 6.00 : 2.50;
                    let perPage = isHard ? 0.022 : 0.015;
                    return base + (this.estimatedPages * perPage);
                },

                get cleanPrintQuantity() {
                    let n = parseInt(this.printQuantity, 10);
                    return isNaN(n) || n < 500 ? 500 : n;
                },

                get totalBulkPrintUsd() {
                    return this.unitPrintCostUsd * this.cleanPrintQuantity;
                },

                get grandTotalUsd() {
                    return this.totalEditorialFeeUsd + this.totalBulkPrintUsd;
                },

                formatCurrency(usdAmount) {
                    let val = Number(usdAmount);
                    if (isNaN(val)) val = 0;
                    if (this.currency === 'NGN') {
                        let ngn = val * (this.exchangeRate || 1500);
                        return '₦' + ngn.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                    return '$' + val.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            }
        }
    </script>
</body>

</html>
