<x-app-layout>
    <x-slot name="title">{{ setting('meta_title', 'ConnectHR – Find Jobs in Pakistan') }}</x-slot>

    {{-- HERO --}}
    <section class="relative bg-gradient-to-br from-brand-700 via-brand-600 to-brand-800 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full translate-x-1/2 translate-y-1/2"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 py-20 text-center">
            <h1 class="text-4xl md:text-6xl font-bold font-display mb-4 leading-tight">
                {{ __('jobs.title') }}
            </h1>
            <p class="text-brand-100 text-lg md:text-xl mb-10 max-w-2xl mx-auto">
                {{ $stats['jobs'] }} {{ app()->getLocale() === 'ur' ? 'نوکریاں دستیاب' : 'jobs available across Pakistan' }}
            </p>

            {{-- Search Box --}}
            <form action="{{ route('jobs.index') }}" method="GET"
                  class="bg-white rounded-2xl shadow-2xl p-3 max-w-4xl mx-auto">
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 flex items-center gap-2 px-4 border-b md:border-b-0 md:border-r border-gray-200">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" placeholder="{{ __('jobs.search') }}"
                               class="w-full py-2 text-gray-800 bg-transparent outline-none text-sm"
                               value="{{ request('q') }}">
                    </div>
                    <div class="flex-1 flex items-center gap-2 px-4 border-b md:border-b-0 md:border-r border-gray-200">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        <select name="location" class="w-full py-2 text-gray-800 bg-transparent outline-none text-sm">
                            <option value="">{{ __('jobs.location') }}</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ request('location') == $loc->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ur' ? ($loc->city_ur ?? $loc->city) : $loc->city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 flex items-center gap-2 px-4">
                        <select name="category" class="w-full py-2 text-gray-800 bg-transparent outline-none text-sm">
                            <option value="">{{ __('jobs.category') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ur' ? ($cat->name_ur ?? $cat->name) : $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-primary px-8 py-3 rounded-xl text-sm font-semibold whitespace-nowrap">
                        {{ __('common.search') }}
                    </button>
                </div>
            </form>

            {{-- Quick links --}}
            <div class="mt-6 flex flex-wrap justify-center gap-2 text-sm">
                <span class="text-brand-200">Popular:</span>
                @foreach(['PHP Developer', 'Marketing Manager', 'Accountant', 'Software Engineer'] as $kw)
                    <a href="{{ route('jobs.index', ['q' => $kw]) }}"
                       class="bg-white/10 hover:bg-white/20 text-white px-3 py-1 rounded-full transition text-xs">
                        {{ $kw }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- STATS BAR --}}
    <section class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            @foreach([
                ['label' => 'Live Jobs', 'value' => number_format($stats['jobs']), 'icon' => '💼'],
                ['label' => 'Companies', 'value' => number_format($stats['companies']), 'icon' => '🏢'],
                ['label' => 'Job Seekers', 'value' => number_format($stats['seekers']), 'icon' => '👥'],
                ['label' => 'Placed', 'value' => number_format($stats['placed']), 'icon' => '✅'],
            ] as $stat)
            <div class="flex items-center justify-center gap-3">
                <span class="text-2xl">{{ $stat['icon'] }}</span>
                <div class="text-left">
                    <div class="text-xl font-bold font-display text-gray-900">{{ $stat['value'] }}+</div>
                    <div class="text-xs text-gray-500">{{ $stat['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- FEATURED JOBS --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="section-heading">Featured Jobs</h2>
                    <p class="section-sub">Top opportunities hand-picked for you</p>
                </div>
                <a href="{{ route('jobs.index', ['is_featured' => 1]) }}" class="btn-outline text-sm">View All</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($featuredJobs as $job)
                    @include('partials.job-card', ['job' => $job])
                @empty
                    <div class="col-span-3 text-center py-12 text-gray-400">No featured jobs yet.</div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- CATEGORIES --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="section-heading">Browse by Category</h2>
                <p class="section-sub">Explore jobs across different industries</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($categories as $cat)
                <a href="{{ route('jobs.category', $cat->slug) }}"
                   class="card card-hover p-4 text-center group">
                    <div class="text-3xl mb-2">{{ $cat->icon }}</div>
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-brand-600 transition">
                        {{ app()->getLocale() === 'ur' ? ($cat->name_ur ?? $cat->name) : $cat->name }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">{{ $cat->jobs_count }} jobs</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- RECENT JOBS --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="section-heading">Recent Jobs</h2>
                    <p class="section-sub">Latest opportunities just posted</p>
                </div>
                <a href="{{ route('jobs.index') }}" class="btn-outline text-sm">See All Jobs</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($recentJobs as $job)
                    @include('partials.job-card', ['job' => $job])
                @endforeach
            </div>
        </div>
    </section>

    {{-- TOP COMPANIES --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="section-heading">Top Companies Hiring</h2>
                <p class="section-sub">Work with Pakistan's leading employers</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($featuredCompanies as $company)
                <a href="{{ route('companies.show', $company->slug) }}"
                   class="card card-hover p-5 flex flex-col items-center text-center gap-3">
                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }}"
                         class="w-14 h-14 rounded-xl object-contain bg-gray-50 p-1">
                    <div>
                        <p class="font-semibold text-sm text-gray-800">{{ $company->name }}</p>
                        <p class="text-xs text-brand-600 mt-0.5">{{ $company->jobs_count }} open positions</p>
                    </div>
                    @if($company->is_verified)
                        <span class="badge-green text-xs">✓ Verified</span>
                    @endif
                </a>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('companies.index') }}" class="btn-outline">View All Companies</a>
            </div>
        </div>
    </section>

    {{-- JOB ALERT SUBSCRIPTION --}}
    <section class="py-16 bg-brand-600">
        <div class="max-w-3xl mx-auto px-4 text-center text-white">
            <h2 class="text-3xl font-bold font-display mb-3">Never Miss a Job</h2>
            <p class="text-brand-100 mb-8">Get the latest jobs delivered to your inbox</p>
            <form action="{{ route('alerts.subscribe') }}" method="POST" class="flex gap-3 max-w-lg mx-auto">
                @csrf
                <input type="email" name="email" placeholder="Enter your email address" required
                       class="flex-1 px-4 py-3 rounded-xl text-gray-900 outline-none text-sm">
                <button type="submit" class="bg-white text-brand-700 font-semibold px-6 py-3 rounded-xl hover:bg-brand-50 transition text-sm">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

    {{-- BLOG --}}
    @if($recentBlogs->count())
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="section-heading">Career Resources</h2>
                    <p class="section-sub">Tips, guides and industry insights</p>
                </div>
                <a href="{{ route('blog.index') }}" class="btn-outline text-sm">View All</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($recentBlogs as $blog)
                <a href="{{ route('blog.show', $blog->slug) }}" class="card card-hover group overflow-hidden">
                    @if($blog->thumbnail)
                        <img src="{{ asset('storage/'.$blog->thumbnail) }}" alt="{{ $blog->title }}"
                             class="w-full h-44 object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-44 bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-4xl">📝</div>
                    @endif
                    <div class="p-5">
                        <p class="text-xs text-gray-400 mb-2">{{ $blog->published_at?->format('M d, Y') }}</p>
                        <h3 class="font-semibold text-gray-800 group-hover:text-brand-600 transition line-clamp-2">
                            {{ $blog->title }}
                        </h3>
                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $blog->excerpt }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</x-app-layout>
