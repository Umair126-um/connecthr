{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="{{ session('theme','light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin – {{ $title ?? 'ConnectHR' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-100 text-gray-900">

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-gray-900 flex-shrink-0 flex flex-col overflow-y-auto">
        <div class="p-5 border-b border-gray-800">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-white font-bold font-display">Connect<span class="text-brand-400">HR</span> <span class="text-xs text-gray-500">Admin</span></span>
            </div>
        </div>

        <nav class="flex-1 p-4 space-y-1">
            @foreach([
                ['route' => 'admin.dashboard', 'icon' => '📊', 'label' => 'Dashboard'],
                ['route' => 'admin.users.index', 'icon' => '👥', 'label' => 'Users'],
                ['route' => 'admin.jobs.index',  'icon' => '💼', 'label' => 'Jobs'],
                ['route' => 'admin.companies.index', 'icon' => '🏢', 'label' => 'Companies'],
                ['route' => 'admin.applications', 'icon' => '📝', 'label' => 'Applications'],
                ['route' => 'admin.cvs',         'icon' => '📄', 'label' => 'CV Manager'],
                ['route' => 'admin.categories.index','icon' => '📂', 'label' => 'Categories'],
                ['route' => 'admin.locations.index', 'icon' => '📍', 'label' => 'Locations'],
                ['route' => 'admin.skills.index',    'icon' => '🎯', 'label' => 'Skills'],
                ['route' => 'admin.pages.index',     'icon' => '📃', 'label' => 'CMS Pages'],
                ['route' => 'admin.blogs.index',     'icon' => '✍️',  'label' => 'Blog'],
                ['route' => 'admin.faqs.index',      'icon' => '❓', 'label' => 'FAQ'],
                ['route' => 'admin.reports',         'icon' => '📈', 'label' => 'Reports'],
                ['route' => 'admin.settings',        'icon' => '⚙️',  'label' => 'Settings'],
                ['route' => 'admin.settings.seo',    'icon' => '🔍', 'label' => 'SEO Settings'],
            ] as $item)
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:text-white hover:bg-gray-800 transition {{ request()->routeIs($item['route']) ? 'bg-gray-800 text-white' : '' }}">
                <span>{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>

        <div class="p-4 border-t border-gray-800">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xs text-gray-500 hover:text-gray-300 transition">
                ← View Site
            </a>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="text-xs text-red-400 hover:text-red-300 transition">🚪 Logout</button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
            <h1 class="font-bold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
            <div class="flex items-center gap-4">
                @if(session('impersonating'))
                <a href="{{ route('home') }}" onclick="event.preventDefault(); fetch('/admin/users/stop-impersonate', {method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>window.location='/')"
                   class="text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full">
                    ⚠️ Impersonating — Click to stop
                </a>
                @endif
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <img src="{{ auth()->user()->avatar_url }}" class="w-7 h-7 rounded-full">
                    {{ auth()->user()->name }}
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success') || session('error'))
        <div class="px-6 pt-4">
            @if(session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                ✓ {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">
                ✕ {{ session('error') }}
            </div>
            @endif
        </div>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
