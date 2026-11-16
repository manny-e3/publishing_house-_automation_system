<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manuscript Enquiry & Excerpt Submission - Griots Lounge Publishing</title>
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
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-brand-bg font-sans text-gray-800 antialiased selection:bg-brand-terracotta selection:text-white flex flex-col min-h-screen">

    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('welcome') }}" class="font-serif italic font-bold text-2xl text-brand-dark tracking-tight">Griots Lounge</a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-wider text-gray-700 hover:text-brand-terracotta transition">
                        Author Sign In
                    </a>
                    <a href="{{ route('welcome') }}" class="bg-brand-dark text-white px-4 py-2 text-xs font-bold tracking-wider uppercase hover:bg-gray-800 transition-colors">
                        Catalog
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="triageForm()" x-cloak>
        
        <!-- Header Banner & Regional Currency Control -->
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <span class="text-xs uppercase tracking-widest font-bold text-brand-terracotta block mb-1">Open Submissions & Enquiries</span>
                    <h1 class="text-3xl md:text-5xl font-serif text-brand-dark mb-2">Submit Your Manuscript for Review</h1>
                    <p class="text-gray-600 max-w-2xl text-sm md:text-base leading-relaxed">
                        Submit your book details, synopsis, and sample excerpt (up to 3,000 words). Our editorial board evaluates every submission for literary excellence and market viability.
                    </p>
                </div>

                <!-- Currency Localisation Control Toggle (Point 4: Geo-IP Priority Nigeria) -->
                <div class="bg-white p-2 rounded-lg border border-gray-200 shadow-sm self-start md:self-auto flex items-center space-x-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider pl-2">Currency:</span>
                    <button type="button" @click="setCurrency('NGN')" 
                            :class="currency === 'NGN' ? 'bg-brand-terracotta text-white font-bold' : 'text-gray-600 hover:bg-gray-100'"
                            class="px-3 py-1.5 rounded-md text-xs transition-colors flex items-center space-x-1.5">
                        <span>🇳🇬 West Africa (₦ NGN)</span>
                    </button>
                    <button type="button" @click="setCurrency('USD')" 
                            :class="currency === 'USD' ? 'bg-brand-dark text-white font-bold' : 'text-gray-600 hover:bg-gray-100'"
                            class="px-3 py-1.5 rounded-md text-xs transition-colors flex items-center space-x-1.5">
                        <span>🌐 International ($ USD)</span>
                    </button>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="mb-8 bg-red-50 text-red-900 p-5 rounded-md border border-red-200 shadow-sm">
            <h4 class="font-bold text-sm mb-2">Please correct the following fields before submitting:</h4>
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

                    <!-- SECTION 1: PROMINENT PUBLISHING OPTION (Point 6) -->
                    <section class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm">
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <span class="w-7 h-7 bg-brand-terracotta text-white rounded-full flex items-center justify-center font-bold text-xs">1</span>
                                <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">Choose Publishing Option</h2>
                            </div>
                            <span class="text-[11px] text-gray-400 font-medium">Select desired pathway</span>
                        </div>

                        <p class="text-xs text-gray-500 mb-5 leading-relaxed">
                            Select the publishing model that best aligns with your goals. Our editorial board evaluates your synopsis and excerpt based on the selected framework.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            
                            <!-- Option 1: Hybrid Publishing -->
                            <label class="relative flex flex-col p-4 border rounded-md cursor-pointer transition text-left"
                                   :class="publishingOption === 'hybrid' ? 'border-brand-terracotta bg-amber-50/20 ring-1 ring-brand-terracotta' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="publishing_option" value="hybrid" x-model="publishingOption" class="sr-only">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs uppercase tracking-widest font-bold text-brand-terracotta">Collaborative</span>
                                    <span class="w-4 h-4 rounded-full border flex items-center justify-center"
                                          :class="publishingOption === 'hybrid' ? 'border-brand-terracotta bg-brand-terracotta text-white' : 'border-gray-300'">
                                        <span x-show="publishingOption === 'hybrid'" class="w-1.5 h-1.5 bg-white rounded-full"></span>
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 mb-1">Hybrid Publishing</h3>
                                <p class="text-[11px] text-gray-500 leading-normal flex-grow">
                                    Author funds subsidized production; retains high royalties (70–80%) with full house editorial, custom cover art & worldwide distribution.
                                </p>
                            </label>

                            <!-- Option 2: Traditional Publishing -->
                            <label class="relative flex flex-col p-4 border rounded-md cursor-pointer transition text-left"
                                   :class="publishingOption === 'traditional' ? 'border-brand-terracotta bg-amber-50/20 ring-1 ring-brand-terracotta' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="publishing_option" value="traditional" x-model="publishingOption" class="sr-only">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs uppercase tracking-widest font-bold text-emerald-700">House Funded</span>
                                    <span class="w-4 h-4 rounded-full border flex items-center justify-center"
                                          :class="publishingOption === 'traditional' ? 'border-brand-terracotta bg-brand-terracotta text-white' : 'border-gray-300'">
                                        <span x-show="publishingOption === 'traditional'" class="w-1.5 h-1.5 bg-white rounded-full"></span>
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 mb-1">Traditional Publishing</h3>
                                <p class="text-[11px] text-gray-500 leading-normal flex-grow">
                                    100% publisher-funded. Zero cost to author. Griots Lounge covers editing, design, print & marketing with standard advance & royalties. Highly selective.
                                </p>
                            </label>

                            <!-- Option 3: Advise Me / Editorial Assessment -->
                            <label class="relative flex flex-col p-4 border rounded-md cursor-pointer transition text-left"
                                   :class="publishingOption === 'advise_me' ? 'border-brand-terracotta bg-amber-50/20 ring-1 ring-brand-terracotta' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="publishing_option" value="advise_me" x-model="publishingOption" class="sr-only">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs uppercase tracking-widest font-bold text-brand-accent">Guidance</span>
                                    <span class="w-4 h-4 rounded-full border flex items-center justify-center"
                                          :class="publishingOption === 'advise_me' ? 'border-brand-terracotta bg-brand-terracotta text-white' : 'border-gray-300'">
                                        <span x-show="publishingOption === 'advise_me'" class="w-1.5 h-1.5 bg-white rounded-full"></span>
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 mb-1">Advise Me</h3>
                                <p class="text-[11px] text-gray-500 leading-normal flex-grow">
                                    Not sure? Submit your synopsis and excerpt; our editorial team assesses your manuscript profile and pitches the most viable path.
                                </p>
                            </label>

                        </div>
                    </section>

                    <!-- SECTION 2: ABOUT THE AUTHOR (Point 5) -->
                    <section class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-100">
                            <span class="w-7 h-7 bg-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs">2</span>
                            <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">About the Author</h2>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Full Legal Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Dr. Amina Yusuf"
                                           x-model="authorName"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Pen Name / Byline (Optional)</label>
                                    <input type="text" name="pen_name" value="{{ old('pen_name') }}" placeholder="If different from legal name"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" required value="{{ old('email') }}" placeholder="author@domain.com"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">WhatsApp / Phone <span class="text-red-500">*</span></label>
                                    <input type="tel" name="phone_number" required value="{{ old('phone_number') }}" placeholder="+234 800 000 0000"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Location (City, State, Country) <span class="text-red-500">*</span></label>
                                <input type="text" name="author_location" required value="{{ old('author_location') }}" placeholder="e.g. Lagos, Nigeria or Winnipeg, Manitoba, Canada"
                                       class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                    Author Biography <span class="text-red-500">*</span>
                                </label>
                                <textarea name="author_bio" rows="4" required x-model="authorBio"
                                          placeholder="Tell us about your background, writing experience, professional achievements, and literary passions..."
                                          class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">{{ old('author_bio') }}</textarea>
                                <span class="text-[11px] text-gray-400 mt-0.5 block">Recommended 100 to 300 words.</span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Prior Publications (Optional)</label>
                                <textarea name="prior_publications" rows="2" placeholder="List any previously published books, essays, journals, or poetry collections (publisher, year)..."
                                          class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">{{ old('prior_publications') }}</textarea>
                            </div>

                            <!-- Social Media Profiles (Optional, Point 2 & 5) -->
                            <div class="pt-2">
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">Author Web & Social Media Links (Optional)</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <input type="url" name="social_twitter" value="{{ old('social_twitter') }}" placeholder="X / Twitter (https://x.com/username)"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2 px-3 text-xs text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0">
                                    <input type="url" name="social_instagram" value="{{ old('social_instagram') }}" placeholder="Instagram (https://instagram.com/username)"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2 px-3 text-xs text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0">
                                    <input type="url" name="social_linkedin" value="{{ old('social_linkedin') }}" placeholder="LinkedIn profile URL"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2 px-3 text-xs text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0">
                                    <input type="url" name="social_website" value="{{ old('social_website') }}" placeholder="Personal Website / Blog URL"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2 px-3 text-xs text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0">
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- SECTION 3: ABOUT THE BOOK & SYNOPSIS (Point 5) -->
                    <section class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-100">
                            <span class="w-7 h-7 bg-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs">3</span>
                            <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">About the Book</h2>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Working Book Title <span class="text-red-500">*</span></label>
                                <input type="text" name="book_title" required value="{{ old('book_title') }}" placeholder="e.g. Chronicles of the Palm Groves"
                                       x-model="bookTitle"
                                       class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Primary Genre <span class="text-red-500">*</span></label>
                                    <select name="genre" required x-model="genre"
                                            class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                        <option value="" disabled selected>Select Primary Genre</option>
                                        <option value="Fiction">Fiction (Literary, Historical, Contemporary)</option>
                                        <option value="Non-Fiction">Non-Fiction (Essays, Cultural Studies)</option>
                                        <option value="Biography/Memoir">Biography / Memoir / Autobiography</option>
                                        <option value="Poetry">Poetry / Chapbook</option>
                                        <option value="Children's Literature">Children's Literature (Nwata Imprint)</option>
                                        <option value="Sci-Fi/Fantasy">Speculative Fiction / Sci-Fi / Fantasy</option>
                                        <option value="Academic/Business">Academic / Business / Professional</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Total Estimated Word Count <span class="text-red-500">*</span></label>
                                    <input type="number" name="number_of_words" min="500" required 
                                           x-model.number="wordCount"
                                           placeholder="e.g. 65000"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                    Comprehensive Book Synopsis <span class="text-red-500">*</span>
                                </label>
                                <textarea name="synopsis" rows="5" required x-model="synopsis"
                                          placeholder="Provide a comprehensive summary of your manuscript: for fiction, detail the core plot, protagonist's conflict, and resolution; for non-fiction, summarize the main thesis, themes, and key takeaways..."
                                          class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">{{ old('synopsis') }}</textarea>
                                <span class="text-[11px] text-gray-400 mt-0.5 block">Recommended 200 to 600 words. Crucial for editorial assessment.</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Target Audience</label>
                                    <input type="text" name="target_audience" value="{{ old('target_audience') }}" placeholder="e.g. Young adults, African literature scholars"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Comparable Titles / Authors</label>
                                    <input type="text" name="comparable_titles" value="{{ old('comparable_titles') }}" placeholder="e.g. Readers of Chinua Achebe, Buchi Emecheta"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Manuscript Draft Status</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center space-x-2 border p-3 rounded cursor-pointer hover:bg-gray-50"
                                           :class="stage === 'Completed' ? 'border-brand-terracotta bg-amber-50/20' : 'border-gray-200'">
                                        <input type="radio" name="stage_of_manuscript" value="Completed" x-model="stage" class="text-brand-terracotta focus:ring-brand-terracotta">
                                        <span class="text-xs font-medium text-gray-800">Completed Draft</span>
                                    </label>
                                    <label class="flex items-center space-x-2 border p-3 rounded cursor-pointer hover:bg-gray-50"
                                           :class="stage === 'In Progress' ? 'border-brand-terracotta bg-amber-50/20' : 'border-gray-200'">
                                        <input type="radio" name="stage_of_manuscript" value="In Progress" x-model="stage" class="text-brand-terracotta focus:ring-brand-terracotta">
                                        <span class="text-xs font-medium text-gray-800">In-Progress WIP</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- SECTION 4: PRODUCTION & PRINT CALCULATOR (Gated, Points 2, 7, 8, 9) -->
                    <section x-show="publishingOption === 'hybrid'" x-transition.opacity.duration.300ms
                             class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-100">
                            <span class="w-7 h-7 bg-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs">4</span>
                            <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">Production & Print Preferences (Hybrid)</h2>
                        </div>

                        <!-- Add-On Production Services (All defaulted to FALSE, Point 7) -->
                        <div class="space-y-3 mb-6">
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Optional Editorial & Creative Add-ons</label>
                            
                            <label class="flex items-start space-x-3 p-3.5 border rounded cursor-pointer hover:bg-gray-50 transition"
                                   :class="includeProofreading ? 'border-brand-accent bg-amber-50/20' : 'border-gray-200'">
                                <input type="checkbox" name="include_proofreading" value="1" x-model="includeProofreading"
                                       class="w-4 h-4 mt-0.5 text-brand-terracotta border-gray-300 rounded focus:ring-brand-terracotta">
                                <div class="flex-1 flex justify-between items-center">
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Professional Proofreading</span>
                                        <span class="text-xs text-gray-500">Orthographic line edit and grammatical refinement</span>
                                    </div>
                                    <span class="text-xs font-bold text-brand-dark" x-text="formatCurrency(cleanWordCount * 0.005)"></span>
                                </div>
                            </label>

                            <label class="flex items-start space-x-3 p-3.5 border rounded cursor-pointer hover:bg-gray-50 transition"
                                   :class="includeCoverDesign ? 'border-brand-accent bg-amber-50/20' : 'border-gray-200'">
                                <input type="checkbox" name="include_cover_design" value="1" x-model="includeCoverDesign"
                                       class="w-4 h-4 mt-0.5 text-brand-terracotta border-gray-300 rounded focus:ring-brand-terracotta">
                                <div class="flex-1 flex justify-between items-center">
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Custom Cover Design Package</span>
                                        <span class="text-xs text-gray-500">Bespoke print-ready jacket, spine mechanical & 3D mockups</span>
                                    </div>
                                    <span class="text-xs font-bold text-brand-dark" x-text="formatCurrency(300)"></span>
                                </div>
                            </label>

                            <label class="flex items-start space-x-3 p-3.5 border rounded cursor-pointer hover:bg-gray-50 transition"
                                   :class="includeMarketingPackage ? 'border-brand-accent bg-amber-50/20' : 'border-gray-200'">
                                <input type="checkbox" name="include_marketing_package" value="1" x-model="includeMarketingPackage"
                                       class="w-4 h-4 mt-0.5 text-brand-terracotta border-gray-300 rounded focus:ring-brand-terracotta">
                                <div class="flex-1 flex justify-between items-center">
                                    <div>
                                        <span class="text-sm font-bold text-gray-900 block">Author Marketing & Launch Package</span>
                                        <span class="text-xs text-gray-500">Press release distribution, store blurb optimization & review seeding</span>
                                    </div>
                                    <span class="text-xs font-bold text-brand-dark" x-text="formatCurrency(500)"></span>
                                </div>
                            </label>
                        </div>

                        <!-- Physical Print Specs (Expanded Trim Sizes, Points 8 & 9) -->
                        <div class="space-y-4 pt-4 border-t border-gray-100">
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">Physical Print Specifications</label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Book Trim Size <span class="text-red-500">*</span></label>
                                    <select name="trim_size" x-model="trimSize"
                                            class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                        <option value="five_by_eight">5" x 8" Pocket / Poetry / Novella</option>
                                        <option value="five_half_by_eight_half">5.5" x 8.5" Standard Fiction / Digest</option>
                                        <option value="six_by_nine">6" x 9" Trade Paperback / Non-Fiction</option>
                                        <option value="quarto">Quarto / 8" x 10" Illustrated & Children's</option>
                                        <option value="seven_by_ten">7" x 10" Executive / Academic</option>
                                    </select>
                                    <span class="text-[11px] text-gray-500 mt-1 block">
                                        Estimated Book Pages: <strong class="text-gray-900" x-text="estimatedPages"></strong>
                                    </span>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Binding Format <span class="text-red-500">*</span></label>
                                    <select name="binding_type" x-model="bindingType"
                                            class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                        <option value="paperback">Standard Paperback</option>
                                        <option value="hardcover">Premium Hardcover (Casebound / Cloth)</option>
                                    </select>
                                    <div class="mt-2 flex items-center justify-between text-xs text-gray-500">
                                        <span>Unit Manufacturing Cost: <strong class="text-brand-dark font-bold font-mono" x-text="formatCurrency(unitPrintCostUsd) + ' / copy'"></strong></span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                    Initial Press Run (Minimum 500 Copies) <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center space-x-4">
                                    <input type="number" name="print_quantity" min="500" step="50"
                                           x-model.number="printQuantity"
                                           class="w-40 bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 font-bold focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                    <span class="text-xs text-gray-500">Partner press bulk manufacturing run.</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- SECTION 5: EXCERPT UPLOAD & VERIFICATION (Point 11) -->
                    <section class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm">
                        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-100">
                            <span class="w-7 h-7 bg-brand-dark text-white rounded-full flex items-center justify-center font-bold text-xs">5</span>
                            <h2 class="text-lg font-serif font-bold text-gray-900 uppercase tracking-wide">Sample Excerpt & Electronic Sign-off</h2>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">
                                    Manuscript Excerpt / First Chapter (Max 3,000 Words) <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="manuscript_file" required accept=".pdf,.doc,.docx"
                                       class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                                <span class="text-[11px] text-gray-500 mt-1 block">
                                    Please attach only the first chapter or a representative excerpt up to 3,000 words (.doc, .docx, or .pdf). Complete manuscripts are requested only after excerpt clearance.
                                </span>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Cover Concept or Moodboard (Optional)</label>
                                <input type="file" name="cover_design_file" accept=".jpeg,.png,.jpg,.pdf"
                                       class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            </div>

                            <!-- Non-Acceptance Legal Disclaimer (Point 11) -->
                            <div class="bg-amber-50/70 border border-amber-200 p-4 rounded text-xs text-amber-900 space-y-1">
                                <span class="font-bold block">Important Publishing Submission Policy:</span>
                                <p class="leading-relaxed">
                                    The submission of an enquiry, synopsis, or excerpt does <strong>not</strong> constitute an offer, contract, or acceptance for publication. All submissions undergo initial editorial review. Full manuscripts are requested only upon successful preliminary appraisal.
                                </p>
                            </div>

                            <div class="pt-4 border-t border-gray-100 space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1.5">Author Electronic Signature Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="agreement_name" required placeholder="Type your full legal name to verify"
                                           :value="signatureName || authorName"
                                           @input="signatureName = $event.target.value"
                                           class="w-full bg-gray-50 border border-gray-200 rounded py-2.5 px-3.5 text-sm text-gray-900 focus:bg-white focus:border-brand-terracotta focus:ring-0 transition">
                                </div>

                                <label class="flex items-start space-x-2.5 cursor-pointer">
                                    <input type="checkbox" name="agreement_terms" value="1" checked required
                                           class="w-4 h-4 mt-0.5 text-brand-terracotta border-gray-300 rounded focus:ring-brand-terracotta">
                                    <span class="text-xs text-gray-600">
                                        I hereby certify that I hold full legal intellectual property rights to this work and agree to the submission evaluation terms.
                                    </span>
                                </label>

                                <!-- Marketing Consent Checkbox (Point 15 & 23) -->
                                <label class="flex items-start space-x-2.5 cursor-pointer">
                                    <input type="checkbox" name="marketing_opt_in" value="1" checked
                                           class="w-4 h-4 mt-0.5 text-brand-terracotta border-gray-300 rounded focus:ring-brand-terracotta">
                                    <span class="text-xs text-gray-600">
                                        I wish to receive literary opportunities, publishing tips, and seasonal catalogue news from Griots Lounge Publishing.
                                    </span>
                                </label>
                            </div>
                        </div>
                    </section>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                :disabled="isSubmitting"
                                class="w-full py-4 bg-brand-dark text-white font-serif tracking-widest text-sm uppercase font-bold hover:bg-gray-800 transition shadow-sm disabled:opacity-50 inline-flex items-center justify-center">
                            <span x-show="!isSubmitting">Submit Manuscript Excerpt for Evaluation</span>
                            <span x-show="isSubmitting" class="inline-flex items-center space-x-2">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Transmitting Submission Package...</span>
                            </span>
                        </button>
                    </div>

                </div>

                <!-- Right Column: Live Dynamic Overview Panel -->
                <div class="w-full lg:w-5/12">
                    <div class="bg-white p-6 md:p-8 shadow-sm border border-gray-200 rounded-sm sticky top-28 space-y-6">

                        <!-- Selected Pathway Indicator -->
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Selected Publishing Framework</span>
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full" 
                                      :class="publishingOption === 'traditional' ? 'bg-emerald-500' : (publishingOption === 'advise_me' ? 'bg-amber-500' : 'bg-brand-terracotta')"></span>
                                <span class="font-serif font-bold text-lg text-gray-900" 
                                      x-text="publishingOption === 'traditional' ? 'Traditional Publishing Review' : (publishingOption === 'advise_me' ? 'Editorial Assessment Request' : 'Hybrid Publishing Model')"></span>
                            </div>
                        </div>

                        <!-- CASE A: TRADITIONAL PUBLISHING SELECTED -->
                        <template x-if="publishingOption === 'traditional'">
                            <div class="bg-emerald-50 border border-emerald-200 rounded-sm p-5 space-y-3">
                                <div class="flex items-center space-x-2 text-emerald-800 font-bold text-sm">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    <span>Traditional Acquisition Review</span>
                                </div>
                                <p class="text-xs text-emerald-950 leading-relaxed">
                                    Under Traditional Publishing, <strong>no production fees apply</strong>. If accepted, Griots Lounge Publishing funds developmental editing, proofreading, bespoke cover design, typesetting, and print runs.
                                </p>
                                <div class="bg-white/80 p-3 rounded text-[11px] text-emerald-950 font-medium space-y-1">
                                    <div>✓ 100% publisher-funded production</div>
                                    <div>✓ Standard author royalty & advance contract</div>
                                    <div>✓ Commercial print & worldwide distribution</div>
                                </div>
                                <p class="text-[11px] text-emerald-700 italic">
                                    Our acquisitions team will evaluate your synopsis and excerpt. Acceptance decisions are rendered within 5 to 10 business days.
                                </p>
                            </div>
                        </template>

                        <!-- CASE B: ADVISE ME SELECTED -->
                        <template x-if="publishingOption === 'advise_me'">
                            <div class="bg-amber-50/70 border border-amber-200 rounded-sm p-5 space-y-3">
                                <div class="flex items-center space-x-2 text-amber-900 font-bold text-sm">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Editorial Assessment & Pathway Guidance</span>
                                </div>
                                <p class="text-xs text-amber-950 leading-relaxed">
                                    You have requested our editorial team to review your manuscript profile. We will evaluate your synopsis and excerpt to determine whether Traditional acquisition or our high-velocity Hybrid distribution offers the best return.
                                </p>
                                <div class="bg-white/80 p-3 rounded text-[11px] text-amber-950 font-medium space-y-1">
                                    <div>✓ Comprehensive literary assessment report</div>
                                    <div>✓ Commercial audience & distribution recommendation</div>
                                    <div>✓ Tailored publishing proposal upon review</div>
                                </div>
                            </div>
                        </template>

                        <!-- CASE C: HYBRID PUBLISHING (Gated Live Estimate, Points 2 & 7) -->
                        <template x-if="publishingOption === 'hybrid'">
                            <div>
                                <!-- Prompt if Required Baseline Inputs are Missing (Point 2) -->
                                <template x-if="!hasRequiredBaseline">
                                    <div class="bg-gray-50 border border-dashed border-gray-300 rounded p-6 text-center space-y-2">
                                        <div class="w-10 h-10 bg-gray-200 text-gray-500 rounded-full flex items-center justify-center mx-auto text-sm">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </div>
                                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Print & Production Estimator Locked</h4>
                                        <p class="text-xs text-gray-500 leading-relaxed max-w-xs mx-auto">
                                            Please enter your <strong>book title</strong>, <strong>genre</strong>, <strong>word count</strong>, <strong>synopsis</strong>, and <strong>author bio</strong> above to unlock the live production and bulk print calculator.
                                        </p>
                                    </div>
                                </template>

                                <!-- Unlocked Live Financial Breakdown -->
                                <template x-if="hasRequiredBaseline">
                                    <div class="space-y-4">
                                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                                Project Financial Overview
                                            </h3>
                                            <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded">Provisional Estimate</span>
                                        </div>

                                        <div class="space-y-2.5 text-xs text-gray-600">
                                            <div class="flex justify-between items-center">
                                                <span>Base Operations Fee</span>
                                                <span class="font-semibold text-gray-900" x-text="formatCurrency(150)"></span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span>Genre Editorial Fee (<span x-text="cleanWordCount.toLocaleString() + ' words'"></span>)</span>
                                                <span class="font-semibold text-gray-900" x-text="formatCurrency(genreWordFeeUsd)"></span>
                                            </div>

                                            <template x-if="includeProofreading">
                                                <div class="flex justify-between items-center text-brand-terracotta">
                                                    <span>Proofreading Service</span>
                                                    <span class="font-semibold" x-text="formatCurrency(cleanWordCount * 0.005)"></span>
                                                </div>
                                            </template>

                                            <template x-if="includeCoverDesign">
                                                <div class="flex justify-between items-center text-brand-terracotta">
                                                    <span>Cover Design Package</span>
                                                    <span class="font-semibold" x-text="formatCurrency(300)"></span>
                                                </div>
                                            </template>

                                            <template x-if="includeMarketingPackage">
                                                <div class="flex justify-between items-center text-brand-terracotta">
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
                                                <span class="text-[10px] text-gray-400">Tentative pending editorial review</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-2xl font-serif font-bold text-brand-dark" x-text="formatCurrency(grandTotalUsd)"></span>
                                            </div>
                                        </div>

                                        <!-- Payment Gateway Routing Notice -->
                                        <div class="bg-gray-50 p-3 rounded flex items-center justify-between text-[11px] text-gray-500">
                                            <span>Primary Merchant:</span>
                                            <span class="font-bold text-gray-800" x-text="currency === 'USD' ? '💳 Stripe International' : '⚡ Paystack Nigeria'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- Legal Disclaimer Notice (Point 3) -->
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-[11px] text-gray-400 italic leading-relaxed">
                                {{ $disclaimer }}
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </form>

        <!-- FREQUENTLY ASKED QUESTIONS SECTION (Point 10) -->
        <section class="mt-16 pt-12 border-t border-gray-200" x-data="{ activeFaq: null }">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs uppercase tracking-widest font-bold text-brand-terracotta block mb-1">Author Resources</span>
                <h2 class="text-2xl md:text-3xl font-serif font-bold text-gray-900">Frequently Asked Questions</h2>
                <p class="text-gray-500 text-xs md:text-sm mt-2">Everything you need to know about publishing with Griots Lounge.</p>
            </div>

            <div class="max-w-3xl mx-auto space-y-3">
                <div class="bg-white border border-gray-200 rounded p-4">
                    <button type="button" @click="activeFaq = (activeFaq === 1 ? null : 1)" class="w-full flex justify-between items-center text-left font-bold text-sm text-gray-900">
                        <span>What is the difference between Traditional and Hybrid Publishing at Griots Lounge?</span>
                        <span class="text-brand-terracotta text-lg" x-text="activeFaq === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 1" x-collapse class="mt-3 text-xs text-gray-600 leading-relaxed border-t pt-3">
                        Traditional Publishing is 100% publisher-funded where Griots Lounge covers all editing, design, printing, and distribution costs, and compensates the author with an advance and royalties. Hybrid Publishing is a collaborative partnership where the author contributes to subsidized production costs while retaining significantly higher royalties (70–80%) and faster time-to-market.
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded p-4">
                    <button type="button" @click="activeFaq = (activeFaq === 2 ? null : 2)" class="w-full flex justify-between items-center text-left font-bold text-sm text-gray-900">
                        <span>Why are initial submissions limited to an excerpt of 3,000 words?</span>
                        <span class="text-brand-terracotta text-lg" x-text="activeFaq === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 2" x-collapse class="mt-3 text-xs text-gray-600 leading-relaxed border-t pt-3">
                        In line with international publishing best practices, our editorial team evaluates initial voice, pacing, prose mechanics, and market alignment from a representative excerpt and synopsis before inviting full manuscripts. This ensures timely, focused feedback for all authors.
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded p-4">
                    <button type="button" @click="activeFaq = (activeFaq === 3 ? null : 3)" class="w-full flex justify-between items-center text-left font-bold text-sm text-gray-900">
                        <span>Will I retain the copyright to my manuscript?</span>
                        <span class="text-brand-terracotta text-lg" x-text="activeFaq === 3 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 3" x-collapse class="mt-3 text-xs text-gray-600 leading-relaxed border-t pt-3">
                        Yes, absolutely. Under both our Traditional and Hybrid publishing models, authors retain full legal copyright to their intellectual property. Griots Lounge obtains exclusive publishing and distribution rights for the term agreed in the publishing contract.
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded p-4">
                    <button type="button" @click="activeFaq = (activeFaq === 4 ? null : 4)" class="w-full flex justify-between items-center text-left font-bold text-sm text-gray-900">
                        <span>When is payment required?</span>
                        <span class="text-brand-terracotta text-lg" x-text="activeFaq === 4 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeFaq === 4" x-collapse class="mt-3 text-xs text-gray-600 leading-relaxed border-t pt-3">
                        <strong>No payment is ever required on enquiry submission.</strong> For Traditional submissions, there are zero author fees. For Hybrid submissions, invoicing occurs only after your synopsis and excerpt have been evaluated, an official assessment report issued, and a formal publishing agreement mutually signed.
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-gray-200 py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-xs text-gray-500 flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="font-serif italic text-gray-900 font-bold">Griots Lounge Publishing</span>
            <span>&copy; {{ date('Y') }} Griots Lounge Publishing (Nigeria & Canada). All Rights Reserved.</span>
        </div>
    </footer>

    <!-- Alpine.js Dynamic Gated Engine -->
    <script>
        function triageForm() {
            return {
                currency: '{{ $defaultCurrency ?? "NGN" }}',
                exchangeRate: {{ (float) ($exchangeRate ?? 1500) }},
                isSubmitting: false,
                
                // Publishing Option (Point 6)
                publishingOption: 'hybrid',

                // Author Demographics (Point 5)
                authorName: '{{ old('name', '') }}',
                authorBio: '{{ old('author_bio', '') }}',
                signatureName: '{{ old('agreement_name', '') }}',

                // Manuscript Dimensions (Point 2 & 5)
                bookTitle: '{{ old('book_title', '') }}',
                genre: '{{ old('genre', '') }}',
                synopsis: '{{ old('synopsis', '') }}',
                wordCount: {{ old('number_of_words') ? (int) old('number_of_words') : 'null' }},
                stage: 'Completed',

                // Add-ons (Defaulted to FALSE, Point 7)
                includeProofreading: false,
                includeCoverDesign: false,
                includeMarketingPackage: false,

                // Production specs (Points 8 & 9)
                trimSize: 'five_half_by_eight_half',
                bindingType: 'paperback',
                printQuantity: 500,

                setCurrency(curr) {
                    this.currency = curr;
                },

                // Baseline Input Gate (Point 2)
                get hasRequiredBaseline() {
                    let w = parseInt(this.wordCount, 10);
                    let validWordCount = !isNaN(w) && w >= 500;
                    let validTitle = (this.bookTitle || '').trim().length > 0;
                    let validGenre = (this.genre || '').trim().length > 0;
                    let validSynopsis = (this.synopsis || '').trim().length >= 20;
                    let validBio = (this.authorBio || '').trim().length >= 15;

                    return validWordCount && validTitle && validGenre && validSynopsis && validBio;
                },

                get wordsPerPage() {
                    if (this.trimSize === 'five_by_eight') return 300;
                    if (this.trimSize === 'five_half_by_eight_half') return 320;
                    if (this.trimSize === 'quarto') return 450;
                    if (this.trimSize === 'seven_by_ten') return 400;
                    return 350; // six_by_nine
                },

                get trimMultiplier() {
                    if (this.trimSize === 'five_by_eight') return 1.0;
                    if (this.trimSize === 'five_half_by_eight_half') return 1.05;
                    if (this.trimSize === 'six_by_nine') return 1.15;
                    if (this.trimSize === 'quarto') return 1.45;
                    if (this.trimSize === 'seven_by_ten') return 1.25;
                    return 1.0;
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
                    return (base + (this.estimatedPages * perPage)) * this.trimMultiplier;
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
