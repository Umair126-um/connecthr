<x-app-layout>
<x-slot name="title">{{ $category->name }} Jobs – ConnectHR</x-slot>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center gap-2 text-sm text-gray-400 mb-3">
            <a href="{{ route('home') }}" class="hover:text-brand-600">Home</a>
            <span>›</span>
            <a href="{{ route('jobs.index') }}" class="hover:text-brand-600">Jobs</a>
            <span>›</span>
            <span class="text-gray-600">{{ $category->name }}</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-4xl">{{ $category->icon }}</span>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }} Jobs</h1>
                <p class="text-gray-500 text-sm mt-0.5">{{ $jobs->total() }} positions available</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($jobs as $job)
            @include('partials.job-card', ['job' => $job])
        @empty
        <div class="col-span-2 card p-16 text-center">
            <div class="text-5xl mb-4">🔍</div>
            <h3 class="text-lg font-semibold text-gray-700">No jobs in this category yet</h3>
            <a href="{{ route('jobs.index') }}" class="btn-primary mt-4">Browse All Jobs</a>
        </div>
        @endforelse
    </div>
    <div class="mt-8">{{ $jobs->links() }}</div>
</div>
</x-app-layout>
