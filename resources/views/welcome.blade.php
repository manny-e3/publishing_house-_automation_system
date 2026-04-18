<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Archive - Premium Publishing</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
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
    <style>
        .hero-gradient {
            background: linear-gradient(to right, rgba(255,255,255,1) 0%, rgba(255,255,255,0.8) 40%, rgba(255,255,255,0) 100%);
        }
    </style>
</head>
<body class="bg-brand-bg font-sans text-gray-800 antialiased selection:bg-brand-accent selection:text-white flex flex-col min-h-screen">

    <!-- Header -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <a href="#" class="font-serif italic font-bold text-xl text-gray-900 tracking-tighter uppercase">The Archive</a>
                </div>
                
                <div class="hidden md:flex items-center space-x-10 text-[11px] uppercase tracking-[0.2em] font-medium text-gray-500">
                    <a href="#" class="hover:text-brand-accent transition-colors">Editions</a>
                    <a href="#" class="hover:text-brand-accent transition-colors">Library</a>
                    <a href="#" class="hover:text-brand-accent transition-colors">Services</a>
                    <a href="#" class="hover:text-brand-accent transition-colors">Authors</a>
                </div>

                <div class="flex items-center space-x-6">
                  
                    <a href="{{ route('enquiry.index') }}" class="bg-brand-dark text-white px-6 py-2.5 text-[10px] uppercase tracking-widest font-bold hover:bg-gray-800 transition-all">Start Enquiry</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative h-[80vh] flex items-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('assets/landing/hero.png') }}" alt="Hero" class="w-full h-full object-cover">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-xl bg-white/95 p-12 md:p-16 shadow-2xl backdrop-blur-sm">
                <span class="text-[10px] uppercase tracking-[0.3em] text-brand-accent font-bold mb-6 block">Est. 1924</span>
                <h1 class="text-5xl md:text-6xl font-serif text-gray-900 mb-8 leading-[1.1]">The Future of the Written Word</h1>
                <p class="text-gray-500 text-sm leading-relaxed mb-10 max-w-sm">
                    Preserving the tactile elegance of physical books while pioneering the next generation of digital literary experiences. A sanctuary for thought, beautifully bound.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#enquiry-form" class="bg-brand-dark text-white px-8 py-4 text-[10px] uppercase tracking-widest font-bold hover:bg-gray-800 transition-colors">Explore the Archive</a>
                    <a href="#" class="border border-gray-200 px-8 py-4 text-[10px] uppercase tracking-widest font-bold text-gray-400 hover:text-gray-900 transition-colors">Latest Publications &rarr;</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Curated Collections -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-16">
                <div>
                    <h2 class="text-3xl font-serif text-gray-900 mb-4">Curated Collections</h2>
                    <p class="text-sm text-gray-400 max-w-md">Discover our limited-run monographs and thematic anthologies, crafted for the discerning reader.</p>
                </div>
                <a href="#" class="text-[10px] uppercase tracking-widest font-bold text-gray-400 hover:text-brand-accent transition-colors">View All Collections &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Large Feature -->
                <div class="md:col-span-8 group relative overflow-hidden aspect-[16/10]">
                    <img src="{{ asset('assets/landing/collections.png') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Collection">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-90"></div>
                    <div class="absolute bottom-10 left-10 text-white">
                        <span class="text-[9px] uppercase tracking-widest font-bold bg-white/20 backdrop-blur-md px-3 py-1 rounded-full mb-4 inline-block">The Modernist Era</span>
                        <h3 class="text-3xl font-serif mb-2">Architects of Thought</h3>
                        <p class="text-sm text-gray-300 max-w-md">A twelve-volume exploration of 20th-century philosophical shifts, beautiful in its minimalist skin.</p>
                    </div>
                </div>

                <!-- Side Cards -->
                <div class="md:col-span-4 flex flex-col gap-8">
                    <div class="bg-brand-bg p-8 border border-gray-100 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="font-serif text-2xl text-gray-200 block mb-4">I</span>
                            <h4 class="text-xl font-serif text-gray-900 mb-2">The Botanist's Journal</h4>
                            <p class="text-xs text-gray-400">Rare flora illustrations from 1890.</p>
                        </div>
                        <a href="#" class="text-[9px] uppercase tracking-widest font-bold text-gray-900 mt-6 border-b border-gray-900 inline-block w-fit">View Edition &rarr;</a>
                    </div>
                    <div class="bg-brand-bg p-8 border border-gray-100 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="font-serif text-2xl text-gray-200 block mb-4">II</span>
                            <h4 class="text-xl font-serif text-gray-900 mb-2">Urban Monoliths</h4>
                            <p class="text-xs text-gray-400">Brutalist architecture in photography.</p>
                        </div>
                        <a href="#" class="text-[9px] uppercase tracking-widest font-bold text-gray-900 mt-6 border-b border-gray-900 inline-block w-fit">View Edition &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Publishing Services -->
    <section class="py-24 bg-brand-bg relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-serif text-gray-900 mb-6">Publishing Services</h2>
                <p class="text-gray-500 max-w-2xl mx-auto text-sm leading-relaxed">We partner with authors, estates, and institutions to bring significant works to the world with unparalleled craftsmanship.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
                <!-- Service 1 -->
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="w-full md:w-1/2 rounded-sm overflow-hidden shadow-xl aspect-square bg-brand-dark flex items-center justify-center p-12">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-brand-accent mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            <span class="text-[10px] uppercase tracking-[0.4em] text-white/40 block">Editorial Process</span>
                        </div>
                    </div>
                    <div class="w-full md:w-1/2">
                        <div class="inline-flex items-center justify-center w-8 h-8 bg-white shadow-sm border border-gray-100 mb-6">
                            <span class="font-serif italic text-brand-accent">e</span>
                        </div>
                        <h3 class="text-2xl font-serif text-gray-900 mb-4">Developmental Editing</h3>
                        <p class="text-sm text-gray-500 leading-relaxed mb-6">Our seasoned editors provide rigorous, thoughtful critique to shape narratives, clarify arguments, and elevate prose while fiercely preserving the author's voice.</p>
                        <a href="#" class="text-[10px] uppercase tracking-widest font-bold text-gray-400 hover:text-gray-900 transition-colors">Learn About Our Process &plus;</a>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="flex flex-col md:flex-row-reverse gap-8 items-center">
                    <div class="w-full md:w-1/2 rounded-sm overflow-hidden shadow-xl aspect-square">
                        <img src="{{ asset('assets/landing/service_design.png') }}" alt="Design" class="w-full h-full object-cover">
                    </div>
                    <div class="w-full md:w-1/2">
                        <div class="inline-flex items-center justify-center w-8 h-8 bg-white shadow-sm border border-gray-100 mb-6">
                            <span class="font-serif italic text-brand-accent">d</span>
                        </div>
                        <h3 class="text-2xl font-serif text-gray-900 mb-4">Bespoke Cover Design</h3>
                        <p class="text-sm text-gray-500 leading-relaxed mb-6">We believe a book's exterior should be an artwork itself. Our design studio creates iconic, tactile covers using custom typography, illustration, and specialized materials.</p>
                        <a href="#" class="text-[10px] uppercase tracking-widest font-bold text-gray-400 hover:text-gray-900 transition-colors">View Design Portfolio &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-white border-t border-gray-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="font-serif italic text-2xl text-gray-900 tracking-tighter uppercase mb-2 block">The Archive</span>
                <p class="text-[10px] uppercase tracking-[0.4em] text-gray-400 font-bold">Custodians of Culture</p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-10 text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-12">
                <a href="#" class="hover:text-gray-900 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-gray-900 transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-gray-900 transition-colors">Editorial Guidelines</a>
                <a href="#" class="hover:text-gray-900 transition-colors">Contact</a>
            </div>

            <div class="text-center text-[9px] text-gray-400 tracking-widest opacity-50">
                &copy; {{ date('Y') }} THE CURATED ARCHIVE. ALL RIGHTS RESERVED.
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll for anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>