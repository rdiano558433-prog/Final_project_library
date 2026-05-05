<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LibraMS') — Library Management System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Inter', sans-serif; }
        .sidebar-link.active { background: #1e40af; color: white; }
        .sidebar-link:hover:not(.active) { background: #1e3a8a; color: white; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-100 min-h-screen" x-data="{ sidebarOpen: true }">

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
           class="fixed left-0 top-0 h-full bg-blue-900 text-white transition-all duration-300 z-50 flex flex-col">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-4 py-5 border-b border-blue-800">
            <div class="w-9 h-9 bg-blue-400 rounded-lg flex items-center justify-center text-white font-bold text-lg flex-shrink-0">📚</div>
            <span x-show="sidebarOpen" class="font-bold text-lg tracking-tight">LibraMS</span>
        </div>

        {{-- Role Badge --}}
        <div class="px-4 py-3 border-b border-blue-800" x-show="sidebarOpen">
            <span class="text-xs bg-blue-700 text-blue-200 rounded-full px-3 py-1 uppercase tracking-widest font-semibold">
                {{ ucfirst(auth()->user()->role) }}
            </span>
            <p class="text-sm mt-1 text-blue-200 truncate">{{ auth()->user()->name }}</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 py-4 overflow-y-auto">
            @php $role = auth()->user()->role; @endphp

            @if($role === 'admin')
                @include('layouts.partials.sidebar-admin')
            @elseif($role === 'staff')
                @include('layouts.partials.sidebar-staff')
            @else
                @include('layouts.partials.sidebar-user')
            @endif
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-blue-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-blue-200 hover:bg-blue-800 hover:text-white transition-colors text-sm">
                    <span class="text-lg flex-shrink-0">🚪</span>
                    <span x-show="sidebarOpen">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div :class="sidebarOpen ? 'ml-64' : 'ml-16'" class="transition-all duration-300 min-h-screen flex flex-col">

        {{-- TOP BAR --}}
        <header class="bg-white shadow-sm sticky top-0 z-40 flex items-center justify-between px-6 py-3">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-gray-800 font-semibold text-lg">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-3 text-sm text-gray-600">
                <span>{{ now()->format('D, M d Y') }}</span>
            </div>
        </header>

        {{-- FLASH MESSAGES --}}
        <div class="px-6 pt-4">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                     class="bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 flex items-center justify-between mb-4">
                    <span>✅ {{ session('success') }}</span>
                    <button @click="show = false" class="text-green-600 hover:text-green-800">✕</button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 flex items-center justify-between mb-4">
                    <span>❌ {{ session('error') }}</span>
                    <button @click="show = false" class="text-red-600 hover:text-red-800">✕</button>
                </div>
            @endif
        </div>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 px-6 pb-8">
            @yield('content')
        </main>

        <footer class="text-center text-xs text-gray-400 py-4 border-t">
            © {{ date('Y') }} LibraMS — Library Management System
        </footer>
    </div>

    @stack('scripts')
</body>
</html>