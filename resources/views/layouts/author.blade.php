<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'My Publishing Enquiries') | The Archive</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                    },
                    colors: {
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        },
                        gold: '#A67C00',
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar { background-color: #ffffff; border-right: 1px solid #f3f4f6; }
        .sidebar-item-active { background-color: #f3f4f6; color: #111827; }
        .status-badge { @apply px-3 py-1 rounded-full text-xs font-semibold; }
        .status-under-review { background-color: #fef3c7; color: #92400e; }
        .status-action-required { background-color: #f3f4f6; color: #374151; }
        .status-draft { background-color: #f3f4f6; color: #6b7280; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#F9FAFB] text-gray-900 font-sans" x-data="{ mobileNav: false }">
    <!-- Mobile Drawer Overlay -->
    <div x-show="mobileNav" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-gray-900/50 md:hidden"
         @click="mobileNav = false"
         style="display: none;"></div>

    <!-- Mobile Drawer Sidebar -->
    <div x-show="mobileNav"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-72 bg-white flex flex-col md:hidden shadow-xl"
         style="display: none;">
        <div class="p-6">
            <div class="flex items-center justify-between mb-8">
                 <span class="font-serif italic text-2xl font-bold">The Archive</span>
                 <button @click="mobileNav = false" class="p-1 rounded-md text-gray-500 hover:text-gray-900">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                 </button>
            </div>
            
            <div class="mb-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 font-serif">EDITORIAL</h2>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest -mt-4 mb-6">PREMIUM ACCESS</p>
            </div>

            <nav class="space-y-1">
                <a href="{{ route('author.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->routeIs('author.dashboard') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    <span>OVERVIEW</span>
                </a>
                <a href="{{ route('author.enquiries.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->routeIs('author.enquiries.*') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>ENQUIRIES</span>
                </a>
                <a href="{{ route('portal.author.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->is('portal/author*') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>DIGITAL BOOKSHELF</span>
                </a>
                <a href="{{ route('author.invoices') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->routeIs('author.invoices') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>INVOICES</span>
                </a>
                <a href="{{ route('author.transactions') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->routeIs('author.transactions') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>TRANSACTIONS</span>
                </a>
            </nav>
        </div>
        
        <div class="mt-auto p-6">
            <a href="{{ route('author.enquiries.create') }}" class="flex items-center justify-between w-full px-4 py-3 bg-navy-950 text-white rounded-sm text-xs font-bold tracking-widest hover:bg-navy-900 transition-colors">
                <span>SUBMIT MANUSCRIPT</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Desktop Sidebar -->
        <div class="hidden md:flex w-64 sidebar flex-col flex-shrink-0">
            <div class="p-8">
                <div class="flex items-center space-x-2 mb-12">
                     <span class="font-serif italic text-2xl font-bold">The Archive</span>
                </div>
                
                <div class="mb-4">
                    <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 font-serif">EDITORIAL</h2>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest -mt-4 mb-8">PREMIUM ACCESS</p>
                </div>

                <nav class="space-y-1">
                    <a href="{{ route('author.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->routeIs('author.dashboard') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        <span>OVERVIEW</span>
                    </a>
                    <a href="{{ route('author.enquiries.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->routeIs('author.enquiries.*') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>ENQUIRIES</span>
                    </a>
                    <a href="{{ route('portal.author.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->is('portal/author*') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span>DIGITAL BOOKSHELF</span>
                    </a>
                    <a href="{{ route('author.invoices') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->routeIs('author.invoices') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>INVOICES</span>
                    </a>
                    <a href="{{ route('author.transactions') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->routeIs('author.transactions') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>TRANSACTIONS</span>
                    </a>
                </nav>
            </div>
            
            <div class="mt-auto p-8">
                <a href="{{ route('author.enquiries.create') }}" class="flex items-center justify-between w-full px-4 py-3 bg-navy-950 text-white rounded-sm text-xs font-bold tracking-widest hover:bg-navy-900 transition-colors">
                    <span>SUBMIT MANUSCRIPT</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-transparent h-20 flex items-center justify-between px-4 sm:px-6 md:px-12 flex-shrink-0">
                <div class="flex items-center space-x-3">
                    <button @click="mobileNav = true" class="md:hidden p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none" aria-label="Open Navigation Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <span class="md:hidden font-serif italic text-xl font-bold">The Archive</span>
                </div>
                
                <div class="flex items-center space-x-4 md:space-x-6 text-gray-500">
                    <a href="{{ route('author.enquiries.create') }}" class="px-3 md:px-4 py-2 border border-gray-200 rounded-sm text-[10px] font-bold tracking-widest uppercase hover:bg-gray-50 transition-colors">New Submission</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" title="Logout" class="w-9 h-9 md:w-10 md:h-10 bg-navy-950 rounded-full flex items-center justify-center text-white hover:bg-navy-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto px-4 sm:px-6 md:px-12 py-6 md:py-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
