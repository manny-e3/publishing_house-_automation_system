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
<body class="bg-[#F9FAFB] text-gray-900 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 sidebar flex flex-col">
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
                    <a href="{{ route('author.invoices') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->routeIs('author.invoices') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span>INVOICES</span>
                    </a>
                    <a href="{{ route('author.transactions') }}" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm {{ request()->routeIs('author.transactions') ? 'sidebar-item-active font-bold' : 'font-medium text-gray-500 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>TRANSACTIONS</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-md text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>PROFILE</span>
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
            <header class="bg-transparent h-20 flex items-center justify-between px-12">
                <div class="flex items-center space-x-4">
                </div>
                
                <div class="flex items-center space-x-6 text-gray-500">
                    <button class="hover:text-gray-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </button>
                    <button class="hover:text-gray-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </button>
                    <div class="h-8 w-[1px] bg-gray-200"></div>
                    <a href="{{ route('author.enquiries.create') }}" class="px-4 py-2 border border-gray-200 rounded-sm text-[10px] font-bold tracking-widest uppercase hover:bg-gray-50 transition-colors">New Submission</a>
                    <button class="w-10 h-10 bg-navy-950 rounded-full flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto px-12 py-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
