{{-- resources/views/partials/navbar.blade.php --}}
<header class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-16 gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 flex-shrink-0">
                <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-xl font-bold font-display text-gray-900">Connect<span class="text-brand-600">HR</span></span>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ route('jobs.index') }}" class="nav-link {{ request()->routeIs('jobs.*') ? 'nav-link-active' : '' }}">Jobs</a>
                <a href="{{ route('companies.index') }}" class="nav-link {{ request()->routeIs('companies.*') ? 'nav-link-active' : '' }}">Companies</a>
                <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'nav-link-active' : '' }}">Blog</a>
            </nav>

            {{-- Right side --}}
            <div class="flex items-center gap-2">
                {{-- Dark mode toggle --}}
                <button onclick="toggleTheme()" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition">
                    <svg id="sun-icon" class="{{ session('theme','light') === 'light' ? '' : 'hidden' }} w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                    <svg id="moon-icon" class="{{ session('theme','light') === 'dark' ? '' : 'hidden' }} w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>

                {{-- Locale toggle --}}
                <a href="{{ route('locale', app()->getLocale() === 'ur' ? 'en' : 'ur') }}"
                   class="p-2 rounded-lg text-xs font-bold text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition">
                    {{ app()->getLocale() === 'ur' ? 'EN' : 'اردو' }}
                </a>

                @guest
                <a href="{{ route('login') }}" class="btn-outline text-sm py-2">Login</a>
                <a href="{{ route('register') }}" class="btn-primary text-sm py-2">Post a Job</a>
                @else
                {{-- User menu --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-gray-100 transition">
                        <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false"
                         class="absolute right-0 top-full mt-2 w-52 bg-white border border-gray-200 rounded-xl shadow-xl py-1 z-50">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        @if(auth()->user()->isJobSeeker())
                        <a href="{{ route('seeker.index') }}" class="dropdown-item">📊 Dashboard</a>
                        <a href="{{ route('seeker.profile') }}" class="dropdown-item">👤 My Profile</a>
                        <a href="{{ route('seeker.applications') }}" class="dropdown-item">📝 Applications</a>
                        @elseif(auth()->user()->isEmployer())
                        <a href="{{ route('employer.dashboard') }}" class="dropdown-item">📊 Dashboard</a>
                        <a href="{{ route('employer.jobs.create') }}" class="dropdown-item">+ Post a Job</a>
                        @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">⚙️ Admin Panel</a>
                        @endif
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item w-full text-left text-red-500">🚪 Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endguest
            </div>
        </div>
    </div>
</header>

<style>
.nav-link { @apply px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition; }
.nav-link-active { @apply text-brand-600 bg-brand-50 }
.dropdown-item { @apply flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition; }
</style>

<script>
function toggleTheme() {
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    html.classList.toggle('dark', !isDark);
    document.getElementById('sun-icon').classList.toggle('hidden', isDark);
    document.getElementById('moon-icon').classList.toggle('hidden', !isDark);
    fetch('/theme/' + (isDark ? 'light' : 'dark'), {method:'POST', headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}});
}
</script>
