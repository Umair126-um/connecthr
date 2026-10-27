<x-app-layout>
<x-slot name="title">Find Jobs – ConnectHR</x-slot>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- SIDEBAR FILTERS --}}
        <aside class="lg:w-72 flex-shrink-0">
            <form method="GET" action="{{ route('jobs.index') }}" id="filterForm"
                  class="card p-5 sticky top-20 space-y-5">
                <div class="flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Filters</h3>
                    <a href="{{ route('jobs.index') }}" class="text-xs text-brand-600 hover:underline">Clear all</a>
                </div>

                {{-- Keyword --}}
                <div>
                    <label class="label">Keywords</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Job title, skill..."
                           class="input" onchange="document.getElementById('filterForm').submit()">
                </div>

                {{-- Category --}}
                <div>
                    <label class="label">Category</label>
                    <select name="category" class="select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->jobs_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Location --}}
                <div>
                    <label class="label">Location</label>
                    <select name="location" class="select" onchange="this.form.submit()">
                        <option value="">All Locations</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location') == $loc->id ? 'selected' : '' }}>
                                {{ $loc->city }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Job Type --}}
                <div>
                    <label class="label">Job Type</label>
                    <div class="space-y-2">
                        @foreach(['full_time' => 'Full Time', 'part_time' => 'Part Time', 'contract' => 'Contract', 'freelance' => 'Freelance', 'internship' => 'Internship', 'remote' => 'Remote'] as $val => $lbl)
                        <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                            <input type="radio" name="job_type" value="{{ $val }}" {{ request('job_type') === $val ? 'checked' : '' }} onchange="this.form.submit()" class="accent-brand-600">
                            {{ $lbl }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Experience --}}
                <div>
                    <label class="label">Experience Level</label>
                    <select name="experience" class="select" onchange="this.form.submit()">
                        <option value="">Any Level</option>
                        @foreach(['entry' => 'Entry Level', 'junior' => 'Junior', 'mid' => 'Mid Level', 'senior' => 'Senior', 'executive' => 'Executive'] as $val => $lbl)
                            <option value="{{ $val }}" {{ request('experience') === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Toggles --}}
                <div class="space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                        <input type="checkbox" name="is_remote" value="1" {{ request('is_remote') ? 'checked' : '' }} onchange="this.form.submit()" class="accent-brand-600">
                        Remote Only
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                        <input type="checkbox" name="is_urgent" value="1" {{ request('is_urgent') ? 'checked' : '' }} onchange="this.form.submit()" class="accent-brand-600">
                        Urgent Jobs
                    </label>
                </div>
            </form>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1 min-w-0">
            {{-- Header --}}
            <div class="flex items-center justify-between mb-5 flex-wrap gap-3">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">
                        {{ $jobs->total() }} Jobs Found
                        @if(request('q'))<span class="text-brand-600"> for "{{ request('q') }}"</span>@endif
                    </h1>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-500">Sort by:</label>
                    <select class="select text-sm w-auto" onchange="window.location='{{ route('jobs.index') }}?' + new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)),...{sort:this.value}}).toString()">
                        <option value="latest" {{ request('sort','latest') === 'latest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="salary" {{ request('sort') === 'salary' ? 'selected' : '' }}>Highest Salary</option>
                        <option value="views"  {{ request('sort') === 'views' ? 'selected' : '' }}>Most Viewed</option>
                    </select>
                </div>
            </div>

            {{-- Jobs --}}
            @forelse($jobs as $job)
                @include('partials.job-card', ['job' => $job])
            @empty
                <div class="card p-16 text-center">
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No jobs found</h3>
                    <p class="text-gray-500 text-sm">Try different keywords or filters</p>
                    <a href="{{ route('jobs.index') }}" class="btn-primary mt-4">Clear Filters</a>
                </div>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-8">{{ $jobs->withQueryString()->links() }}</div>
        </div>
    </div>
</div>
</x-app-layout>
