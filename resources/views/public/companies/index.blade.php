{{-- resources/views/public/companies/index.blade.php --}}
<x-app-layout>
<x-slot name="title">Browse Companies – ConnectHR</x-slot>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Browse Companies</h1>
        <p class="text-gray-500">Discover {{ $companies->total() }} companies hiring across Pakistan</p>
    </div>

    <form method="GET" class="flex gap-3 max-w-2xl mx-auto mb-10">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search companies..." class="input flex-1">
        <select name="category" class="select w-48"><option value="">All Industries</option>@foreach($categories as $c)<option value="{{ $c->id }}" {{ request('category') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>@endforeach</select>
        <button type="submit" class="btn-primary">Search</button>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($companies as $company)
        <a href="{{ route('companies.show', $company->slug) }}" class="card card-hover p-5 group">
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ $company->logo_url }}" alt="{{ $company->name }}"
                     class="w-12 h-12 rounded-xl object-contain bg-gray-50 p-1 flex-shrink-0">
                <div class="min-w-0">
                    <h3 class="font-semibold text-gray-800 text-sm group-hover:text-brand-600 transition truncate">{{ $company->name }}</h3>
                    @if($company->is_verified)<span class="badge-green text-xs">✓ Verified</span>@endif
                </div>
            </div>
            @if($company->description)
            <p class="text-xs text-gray-500 line-clamp-2 mb-3">{{ $company->description }}</p>
            @endif
            <div class="flex items-center justify-between text-xs text-gray-400">
                <span>{{ $company->company_size }}</span>
                <span class="text-brand-600 font-medium">{{ $company->jobs_count }} jobs</span>
            </div>
        </a>
        @empty
        <div class="col-span-4 text-center py-16">
            <div class="text-5xl mb-4">🏢</div>
            <h3 class="font-semibold text-gray-700">No companies found</h3>
        </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $companies->withQueryString()->links() }}</div>
</div>
</x-app-layout>
