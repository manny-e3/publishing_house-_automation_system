@extends('layouts.author')

@section('content')
    <main class="flex-grow w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <h1 class="text-4xl md:text-5xl font-serif text-gray-900 mb-4">Publishing Enquiry</h1>
            <p class="text-gray-600 max-w-3xl leading-relaxed">Begin the journey of bringing your manuscript to the
                world. Provide us with the foundational details of your work, and our editorial team will curate a
                bespoke strategy.</p>
        </div>

        @if (session('success'))
        <div class="mb-8 bg-green-50 text-green-800 p-4 rounded-md border border-green-200">
            <p class="font-medium text-sm">{{ session('success') }}</p>
        </div>
        @endif
        @if ($errors->any())
        <div class="mb-8 bg-red-50 text-red-800 p-4 rounded-md border border-red-200">
            <ul class="list-disc pl-5 text-sm font-medium">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('enquiry.store') }}" method="POST" enctype="multipart/form-data" x-data="enquiryForm">
            @csrf

            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

                <!-- Left Column (Form Fields) -->
                <div class="w-full lg:w-2/3 space-y-8">

                    <!-- Progress Bar -->
                    <div class="bg-white p-4 shadow-sm border border-gray-200 sticky top-0 z-10 hidden md:block">
                        <div class="flex items-center justify-between">
                            <div class="w-1/2 text-center border-r"
                                :class="{'text-brand-accent font-bold': step === 1, 'text-green-600': step > 1 }">1.
                                Project Details</div>
                            <div class="w-1/2 text-center"
                                :class="{'text-brand-accent font-bold': step === 2, 'text-gray-400': step < 2}">2.
                                Uploads & Verification</div>
                        </div>
                        <div class="w-full bg-gray-200 h-1.5 mt-4 rounded-full overflow-hidden">
                            <div class="bg-brand-accent h-1.5 transition-all duration-300"
                                :style="'width: ' + ((step/maxStep)*100) + '%'"></div>
                        </div>
                    </div>

                    <div class="md:hidden mb-4 text-xs font-bold text-gray-500 uppercase tracking-widest">
                        Step <span x-text="step"></span> of <span x-text="maxStep"></span>
                    </div>

                    <!-- STEP 1: PROJECT DETAILS -->
                    <div id="step-container-1" x-show="step === 1" x-transition.opacity.duration.300ms
                        class="step-container space-y-8">
                        <section class="bg-white p-6 md:p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center space-x-3 mb-6">
                                <h2 class="text-xl font-serif text-gray-900 uppercase tracking-wide">Author Information
                                </h2>
                            </div>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm text-gray-700 font-medium mb-1">Full Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" required value="{{ old('name', auth()->user()->name) }}" placeholder="Enter your full name"
                                        class="w-full bg-gray-100 border-b border-gray-300 focus:bg-gray-100 focus:border-brand-dark focus:ring-0 rounded-t-sm py-2 px-4 text-gray-600 transition-colors" readonly>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm text-gray-700 font-medium mb-1">Phone number <span class="text-red-500">*</span></label>
                                        <input type="tel" name="phone_number" required value="{{ old('phone_number', auth()->user()->phone_number ?? '') }}" placeholder="Enter your phone number"
                                            class="w-full bg-gray-50 border-b border-gray-300 focus:bg-gray-100 focus:border-brand-dark focus:ring-0 rounded-t-sm py-2 px-4 transition-colors">
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700 font-medium mb-1">Email <span class="text-red-500">*</span></label>
                                        <input type="email" name="email" required value="{{ old('email', auth()->user()->email) }}" placeholder="Enter your email address"
                                            class="w-full bg-gray-100 border-b border-gray-300 focus:bg-gray-100 focus:border-brand-dark focus:ring-0 rounded-t-sm py-2 px-4 text-gray-600 transition-colors" readonly>
                                    </div>
                                </div>
                            </div>
                        </section>

                         <section class="bg-white p-6 md:p-8 shadow-sm border border-gray-100 border-l-4 border-l-brand-accent">
                             <div class="flex items-center space-x-3 mb-6">
                                 <span class="w-8 h-8 bg-brand-accent/10 text-brand-accent rounded-full flex items-center justify-center font-bold text-sm">1</span>
                                 <h2 class="text-xl font-serif text-gray-900 uppercase tracking-wide">About The Book</h2>
                             </div>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm text-gray-700 font-medium mb-1">Book Title <span class="text-red-500">*</span></label>
                                    <input type="text" name="book_title" required value="{{ old('book_title') }}" placeholder="Enter your book title"
                                        class="w-full bg-gray-50 border-b border-gray-300 focus:bg-gray-100 focus:border-brand-dark focus:ring-0 rounded-t-sm py-2 px-4 transition-colors">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm text-gray-700 font-medium mb-1">Genre <span class="text-red-500">*</span></label>
                                        <select name="genre" required
                                            class="w-full bg-gray-50 border border-gray-300 focus:bg-white focus:border-brand-dark focus:ring-0 rounded-sm py-2 px-4 text-gray-700 transition-colors">
                                            <option value="">Choose</option>
                                            <option value="Fiction">Fiction</option>
                                            <option value="Non-Fiction">Non-Fiction</option>
                                            <option value="Biography">Biography</option>
                                            <option value="Poetry">Poetry</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm text-gray-700 font-medium mb-1">Number of Words <span class="text-red-500">*</span></label>
                                        <input type="number" x-model.number="words" name="number_of_words" required min="1" value="{{ old('number_of_words') }}" placeholder="Enter estimated word count"
                                            class="w-full bg-gray-50 border-b border-gray-300 focus:bg-gray-100 focus:border-brand-dark focus:ring-0 rounded-t-sm py-2 px-4 transition-colors">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-700 font-medium mb-4">Stage of Manuscript
                                        <span class="text-red-500">*</span></label>
                                    <div class="space-y-3">
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="radio" name="stage_of_manuscript" value="Completed" required {{ old('stage_of_manuscript') == 'Completed' ? 'checked' : '' }}
                                                class="w-4 h-4 text-brand-dark border-gray-300 focus:ring-brand-dark">
                                            <span class="text-sm text-gray-700">Completed</span>
                                        </label>
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="radio" name="stage_of_manuscript" value="In Progress" required {{ old('stage_of_manuscript') == 'In Progress' ? 'checked' : '' }}
                                                class="w-4 h-4 text-brand-dark border-gray-300 focus:ring-brand-dark">
                                            <span class="text-sm text-gray-700">In Progress</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-gray-100">
                                    <label class="block text-sm text-gray-700 font-medium mb-4">Preferred Services
                                        <span class="text-red-500">*</span></label>
                                    <p class="text-xs text-gray-500 mb-4 italic">Select the services you require for your project.</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-200 rounded hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" name="services[]" value="editing" x-model="services"
                                                class="w-4 h-4 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-700">Editing</span>
                                                <span class="text-[10px] text-gray-500">Professional editorial review</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-200 rounded hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" name="services[]" value="formatting" x-model="services"
                                                class="w-4 h-4 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-700">Formatting</span>
                                                <span class="text-[10px] text-gray-500">Layout & interior design</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-200 rounded hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" name="services[]" value="cover" x-model="services"
                                                class="w-4 h-4 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-700">Cover Design</span>
                                                <span class="text-[10px] text-gray-500">Bespoke artistic cover</span>
                                            </div>
                                        </label>
                                        <label class="flex items-center space-x-3 cursor-pointer p-3 border border-gray-200 rounded hover:bg-gray-50 transition-colors">
                                            <input type="checkbox" name="services[]" value="printing" x-model="services"
                                                class="w-4 h-4 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-700">Printing & Dist.</span>
                                                <span class="text-[10px] text-gray-500">Physical production & sales</span>
                                            </div>
                                        </label>
                                    </div>
                                    <template x-if="services.length === 0">
                                        <p class="mt-2 text-xs text-red-500">Please select at least one service.</p>
                                    </template>
                                </div>

                                <!-- BOOK PRODUCTION OPTIONS (Conditional on Printing) -->
                                <div x-show="services.includes('printing')" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="pt-8 border-t border-gray-100 space-y-6">
                                    <div class="flex items-center space-x-3 mb-6">
                                        <span class="w-8 h-8 bg-brand-accent/10 text-brand-accent rounded-full flex items-center justify-center font-bold text-sm">2</span>
                                        <h2 class="text-xl font-serif text-gray-900 uppercase tracking-wide">Production Details</h2>
                                    </div>

                                    <input type="hidden" name="estimated_cost" :value="estimatedTotal">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm text-gray-700 font-medium mb-1">Print Quantity <span class="text-red-500">*</span></label>
                                            <input type="number" x-model.number="print_quantity" name="print_quantity" :required="services.includes('printing')" min="1" placeholder="e.g. 500 copies"
                                                class="w-full bg-gray-50 border-b border-gray-300 focus:bg-gray-100 focus:border-brand-dark focus:ring-0 rounded-t-sm py-2 px-4 transition-colors">
                                            <p class="text-[10px] text-gray-500 mt-1">Number of physical copies you wish to produce.</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-700 font-medium mb-1">Estimated Page Count <span class="text-red-500">*</span></label>
                                            <input type="number" x-model.number="estimated_pages" name="estimated_pages" :required="services.includes('printing')" min="1" placeholder="e.g. 200 pages"
                                                class="w-full bg-gray-50 border-b border-gray-300 focus:bg-gray-100 focus:border-brand-dark focus:ring-0 rounded-t-sm py-2 px-4 transition-colors">
                                            <p class="text-[10px] text-gray-500 mt-1">Rough number of pages in your final book.</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm text-gray-700 font-medium mb-1">Interior Paper <span class="text-red-500">*</span></label>
                                            <select name="interior_paper" x-model="interior_paper" :required="services.includes('printing')"
                                                class="w-full bg-gray-50 border border-gray-300 focus:bg-white focus:border-brand-dark focus:ring-0 rounded-sm py-2 px-4 text-gray-700 transition-colors">
                                                <option value="">Select Paper Type</option>
                                                @foreach($groupedRates['interior_paper'] ?? [] as $rate)
                                                    <option value="{{ $rate->key }}">{{ $rate->label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm text-gray-700 font-medium mb-1">Cover Paper <span class="text-red-500">*</span></label>
                                            <select name="cover_paper" x-model="cover_paper" :required="services.includes('printing')"
                                                class="w-full bg-gray-50 border border-gray-300 focus:bg-white focus:border-brand-dark focus:ring-0 rounded-sm py-2 px-4 text-gray-700 transition-colors">
                                                <option value="">Select Cover Material</option>
                                                @foreach($groupedRates['special_paper'] ?? [] as $rate)
                                                    <option value="{{ $rate->key }}">{{ $rate->label }}</option>
                                                @endforeach
                                                @foreach($groupedRates['cover_paper'] ?? [] as $rate)
                                                    <option value="{{ $rate->key }}">{{ $rate->label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="space-y-4">
                                        <label class="block text-sm text-gray-700 font-medium mb-2">Enhancements</label>
                                        <div class="flex flex-wrap gap-4">
                                            <label class="flex items-center space-x-2 cursor-pointer group">
                                                <input type="checkbox" name="is_hard_cover" x-model="is_hard_cover" class="w-4 h-4 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                                <span class="text-xs text-gray-600 group-hover:text-gray-900 transition-colors">Hard Cover</span>
                                            </label>
                                            <label class="flex items-center space-x-2 cursor-pointer group">
                                                <input type="checkbox" name="is_embossed" x-model="is_embossed" class="w-4 h-4 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                                <span class="text-xs text-gray-600 group-hover:text-gray-900 transition-colors">Embossing</span>
                                            </label>
                                            <label class="flex items-center space-x-2 cursor-pointer group">
                                                <input type="checkbox" name="is_packaged" x-model="is_packaged" class="w-4 h-4 text-brand-dark border-gray-300 rounded focus:ring-brand-dark">
                                                <span class="text-xs text-gray-600 group-hover:text-gray-900 transition-colors">Premium Packaging</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- STEP 2: UPLOADS & ASSURANCES -->
                    <div id="step-container-2" x-show="step === 2" x-transition.opacity.duration.300ms
                        class="step-container space-y-8" style="display: none;">
                        <section class="bg-white p-6 md:p-8 shadow-sm border border-gray-100">
                            <div class="flex items-center space-x-3 mb-2">
                                <h2 class="text-xl font-serif text-gray-900 uppercase tracking-wide">Uploads</h2>
                            </div>
                            <p class="text-sm font-medium italic text-gray-700 mb-6">Please submit the materials
                                relevant to your publishing package.</p>

                            <div class="space-y-6">
                                <div class="border border-gray-200 bg-gray-50 p-6 rounded-md">
                                    <label class="block text-sm text-gray-900 font-medium mb-2">Manuscript / Excerpt
                                        (required) <span class="text-red-500">*</span></label>
                                    <p class="text-xs text-gray-600 mb-4">Acceptable formats: .doc, .docx, .pdf.</p>
                                    <input type="file" name="manuscript_file" accept=".doc,.docx,.pdf" required
                                        class="block w-full max-w-sm text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-sm file:border file:border-brand-dark file:text-sm file:font-medium file:bg-white file:text-brand-dark hover:file:bg-gray-50 cursor-pointer">
                                </div>

                                <div class="border border-gray-200 bg-gray-50 p-6 rounded-md">
                                    <label class="block text-sm text-gray-900 font-medium mb-2">Cover Design Reference
                                        (Optional)</label>
                                    <p class="text-xs text-gray-600 mb-4">Have a concept? Upload accepted formats: .jpg,
                                        .jpeg, .png, .pdf.</p>
                                    <input type="file" name="cover_design_file" accept=".pdf,.jpg,.jpeg,.png"
                                        class="block w-full max-w-sm text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-sm file:border file:border-brand-dark file:text-sm file:font-medium file:bg-white file:text-brand-dark hover:file:bg-gray-50 cursor-pointer">
                                </div>
                            </div>
                        </section>

                        <section class="bg-white shadow-sm overflow-hidden border border-gray-200 border-l-4 border-l-brand-accent" x-data="{ open: false, signed: false, signName: '' }">
                            <div class="flex items-center space-x-3 bg-gray-50 px-6 py-4 md:px-8 border-b border-gray-200">
                                <span class="w-8 h-8 bg-brand-accent/10 text-brand-accent rounded-full flex items-center justify-center font-bold text-sm">3</span>
                                <h2 class="text-xl font-serif text-gray-900 uppercase tracking-wide">Submission Agreement</h2>
                            </div>
                            <div class="p-6 md:p-8 space-y-6">
                                <div class="flex items-center justify-between bg-gray-50 p-6 border border-dashed border-gray-300 rounded-sm">
                                    <div class="flex items-center space-x-4">
                                        <template x-if="signed">
                                            <div class="bg-green-100 p-2 rounded-full text-green-600">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        </template>
                                        <template x-if="!signed">
                                            <div class="bg-brand-accent/10 p-2 rounded-full text-brand-accent">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </div>
                                        </template>
                                        <div>
                                            <p class="font-bold text-gray-900" x-text="signed ? 'Agreement Signed Successfully' : 'Legal Signature Required'"></p>
                                            <p class="text-xs text-gray-500" x-text="signed ? 'Signed by ' + signName : 'Please review and sign the terms to proceed'"></p>
                                        </div>
                                    </div>
                                    <button type="button" @click="open = true" class="px-6 py-3 bg-brand-dark text-white text-xs font-bold tracking-widest uppercase hover:bg-gray-800 transition-colors">
                                        <span x-text="signed ? 'REVIEW SIGNATURE' : 'REVIEW & SIGN AGREEMENT'"></span>
                                    </button>
                                </div>

                                {{-- Hidden Fields for Submission --}}
                                <input type="hidden" name="agreement_terms" :value="signed ? '1' : ''" required>
                                <input type="hidden" name="agreement_name" x-model="signName" required>

                                {{-- Signing Modal --}}
                                <div x-show="open" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0"
                                     x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100"
                                     x-transition:leave-end="opacity-0"
                                     class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
                                     @keydown.escape.window="open = false"
                                     style="display: none;">
                                    
                                    <div class="bg-white max-w-2xl w-full shadow-2xl rounded-sm overflow-hidden" @click.away="open = false">
                                        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
                                            <h3 class="text-2xl font-serif font-bold">Submission Agreement</h3>
                                            <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-900">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                        
                                        <div class="p-8 max-h-[400px] overflow-y-auto bg-gray-50 text-sm text-gray-600 leading-relaxed space-y-4">
                                            <p class="font-bold text-gray-900">Please read the following terms carefully:</p>
                                            <p><strong>1. Confidentiality:</strong> The Curated Archive acknowledges that the manuscript submitted is the intellectual property of the Author and is confidential. We will not reproduce, distribute, or disclose the contents to third parties without explicit consent, except for internal evaluation.</p>
                                            <p><strong>2. Evaluation Purpose:</strong> The manuscript is submitted solely for the purpose of evaluation by our editorial team. This submission does not constitute a guarantee of publication. A separate Publishing Agreement will be issued upon acceptance.</p>
                                            <p><strong>3. Warranty:</strong> The Author warrants that they are the sole owner of the work and that it does not infringe upon any existing copyright or legal rights of others.</p>
                                            <p><strong>4. Data Usage:</strong> You consent to the storage of your contact information for the purpose of communicating about this submission.</p>
                                        </div>

                                        <div class="p-8 space-y-6">
                                            <div>
                                                <label class="flex items-center space-x-3 cursor-pointer">
                                                    <input type="checkbox" x-model="signed" class="w-5 h-5 text-brand-dark border-gray-300 rounded focus:ring-brand-dark cursor-pointer">
                                                    <span class="text-sm font-medium text-gray-700">I acknowledge and agree to the terms stated above.</span>
                                                </label>
                                            </div>
                                            
                                            <div x-show="signed" x-transition>
                                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Electronic Signature (Full Name)</label>
                                                <input type="text" x-model="signName" placeholder="Type your full legal name" 
                                                       class="w-full border-b-2 border-gray-200 focus:border-brand-accent py-3 font-serif text-xl outline-none transition-colors">
                                            </div>

                                            <div class="pt-4 flex items-center justify-end space-x-4">
                                                <button type="button" @click="open = false" class="text-sm font-bold text-gray-400 hover:text-gray-900 px-6">CANCEL</button>
                                                <button type="button" @click="if(signed && signName) { open = false } else { alert('Please sign and provide your name') }" 
                                                        class="bg-brand-accent text-white font-bold text-xs tracking-widest px-8 py-4 uppercase hover:bg-brand-accentHover transition-colors disabled:opacity-50">
                                                    CONFIRM SIGNATURE
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Navigation Controls -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <button type="button" x-show="step > 1" @click="window.scrollTo(0, 0); step--"
                            class="px-6 py-3 border border-gray-300 bg-white text-gray-700 font-medium rounded-sm hover:bg-gray-50 transition-colors"
                            style="display: none;">
                            &larr; Previous Step
                        </button>
                        <div x-show="step === 1" class="w-1"></div> <!-- Spacer -->

                        <!-- Client-side validation before moving next step -->
                        <button type="button" x-show="step < maxStep" @click="
                            let container = document.getElementById('step-container-' + step);
                            let inputs = container.querySelectorAll('input[required], select[required], textarea[required]');
                            let isValid = true;
                            for (let input of inputs) {
                                if (!input.checkValidity()) {
                                    input.reportValidity();
                                    isValid = false;
                                    break;
                                }
                            }
                            if (isValid) {
                                step++;
                                window.scrollTo(0, 0);
                            }
                        "
                            class="px-6 py-3 bg-brand-dark text-white font-medium rounded-sm hover:bg-gray-800 transition-colors shadow-sm ml-auto">
                            Next Step &rarr;
                        </button>
                    </div>

                </div>

                <!-- Right Column (Sidebar) -->
                <div class="w-full lg:w-1/3">
                    <div class="sticky top-8 space-y-6">

                        <!-- Investment Estimate Card -->
                        <div class="bg-brand-dark text-white p-8 shadow-sm rounded-sm">
                            <div class="flex items-center space-x-3 mb-8">
                                <svg class="w-5 h-5 text-brand-accent" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h2 class="text-xl font-serif text-white tracking-wide">Publishing Profile</h2>
                            </div>

                            <div class="mt-4 mb-6 p-4 bg-gray-800 rounded border border-gray-700 shadow-inner">
                                <div class="text-xs text-gray-400 uppercase tracking-widest mb-1">Estimated Investment
                                </div>
                                <div class="text-2xl font-serif text-brand-accent">
                                    <span class="text-lg opacity-70 mr-1">₦</span>
                                    <span x-text="Number(estimatedTotal).toLocaleString()">150,000</span>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2 italic">Includes base platform fee and
                                    word-count formatting rate.</p>
                            </div>

                            <div class="border-t border-gray-700 pt-6 mb-8">
                                <p class="text-sm leading-relaxed text-gray-300">
                                    Complete the <span x-text="maxStep"></span>-step process to finalize your publishing
                                    enquiry.
                                </p>
                                <div class="mt-4 flex flex-col space-y-2">
                                    <div class="flex items-center justify-between text-xs text-gray-400">
                                        <span>Progress:</span>
                                        <span x-text="Math.round((step/maxStep)*100) + '%'"></span>
                                    </div>
                                    <div class="w-full bg-gray-700 h-1 rounded-full overflow-hidden">
                                        <div class="bg-brand-accent h-1 transition-all duration-300"
                                            :style="'width: ' + ((step/maxStep)*100) + '%'"></div>
                                    </div>
                                </div>
                            </div>

                            <button x-show="step === maxStep" type="submit"
                                class="w-full bg-brand-accent text-white py-4 font-bold tracking-widest hover:bg-brand-accentHover transition-colors flex justify-center items-center group rounded-sm"
                                style="display: none;">
                                SUBMIT PROSPECT
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                            <button x-show="step < maxStep" type="button"
                                @click="$el.closest('form').querySelector('button[type=button][x-show=\'step < maxStep\']').click()"
                                class="w-full bg-gray-500 text-white py-4 font-bold tracking-widest hover:bg-gray-600 transition-colors flex justify-center items-center rounded-sm">
                                CONTINUE
                            </button>
                        </div>

                        <!-- Assistance Card -->
                        <div class="bg-gray-100 p-8 text-center border border-gray-200 rounded-sm">
                            <div
                                class="inline-flex justify-center items-center w-10 h-10 rounded bg-white text-gray-600 mb-4 shadow-sm">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-serif text-gray-900 mb-2">Need Assistance?</h3>
                            <p class="text-sm text-gray-500 mb-3">Our concierge team is available to help.</p>
                            <a href="mailto:concierge@curatedarchive.com"
                                class="text-sm text-gray-900 hover:text-brand-accent transition-colors font-medium">concierge@curatedarchive.com</a>
                        </div>

                    </div>
                </div>

            </div>
        </form>
    </main>

    @push('scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
             Alpine.data('enquiryForm', () => ({
                step: 1, 
                maxStep: 2,
                words: {{ (int)old('number_of_words', 0) }},
                services: @json(old('services', ['editing'])),
                allRates: @json($rates),
                
                // Production Details
                print_quantity: 600,
                estimated_pages: 120, // Default assumption
                interior_paper: '',
                cover_paper: '',
                is_hard_cover: false,
                is_embossed: false,
                is_packaged: false,

                get estimatedTotal() {
                    let r = this.allRates || {};
                    let setup = parseFloat(r.fixed_setup_fee);
                    let total = isNaN(setup) ? 150000 : setup;
                    
                    // Editorial costs
                    if (this.services && this.services.includes('editing')) {
                        let fixed = parseFloat(r.fixed_editing_fee) || 0;
                        let perWord = parseFloat(r.editing_per_word) || 5;
                        total += fixed + (this.words * perWord);
                    }
                    
                    if (this.services && this.services.includes('formatting')) {
                        let fixed = parseFloat(r.fixed_formatting_fee) || 0;
                        let perWord = parseFloat(r.formatting_per_word) || 2;
                        total += fixed + (this.words * perWord);
                    }
                    
                    if (this.services && this.services.includes('cover')) {
                        total += parseFloat(r.fixed_cover_design_fee) || 50000;
                    }
                    
                    // Advanced Printing costs
                    if (this.services && this.services.includes('printing')) {
                        // 1. Interior Paper
                        if (this.interior_paper && r[this.interior_paper]) {
                            // Rough calculation for rims: (qty * pages) / (pages per sheet * 500)
                            let rims = Math.ceil((this.print_quantity * this.estimated_pages) / (32 * 500));
                            total += rims * parseFloat(r[this.interior_paper]);
                        } else {
                            total += parseFloat(r.fixed_printing_fee) || 100000;
                        }

                        // 2. Cover Paper
                        if (this.cover_paper && r[this.cover_paper]) {
                            total += parseFloat(r[this.cover_paper]);
                        }

                        // 3. Finishing Operations (per book)
                        let finishing = (parseFloat(r.calc_folding) || 50) + 
                                        (parseFloat(r.calc_lamination) || 70) + 
                                        (parseFloat(r.calc_binding) || 50) + 
                                        (parseFloat(r.calc_cutting) || 20);
                        
                        total += (finishing * this.print_quantity);

                        // 4. Special Effects
                        if (this.is_hard_cover) total += (parseFloat(r.calc_hard_cover) || 3000) * this.print_quantity;
                        if (this.is_embossed) total += (parseFloat(r.calc_embossing) || 500) * this.print_quantity;
                        if (this.is_packaged) total += (parseFloat(r.calc_packaging) || 50) * this.print_quantity;
                        
                        // 5. Plating & Impression (Base setup for printing)
                        total += (parseFloat(r.calc_interior_plating) || 4500) * Math.ceil(this.estimated_pages / 16);
                        total += (parseFloat(r.calc_cover_plating) || 14000);
                    }
                    
                    return total;
                }
            }));
        });
    </script>
    @endpush
@endsection