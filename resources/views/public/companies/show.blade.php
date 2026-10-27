{{-- resources/views/public/companies/show.blade.php --}}
<x-app-layout>
<x-slot name="title">{{ $company->name }} Jobs – ConnectHR</x-slot>
<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Cover & Header --}}
    <div class="card overflow-hidden mb-6">
        <div class="h-40 bg-gradient-to-br from-brand-500 to-brand-700 relative">
            @if($company->cover_image)
            <img src="{{ asset('storage/'.$company->cover_image) }}" class="w-full h-full object-cover">
            @endif
        </div>
        <div class="px-6 pb-6">
            <div class="flex items-end justify-between gap-4 -mt-8 mb-4 flex-wrap">
                <img src="{{ $company->logo_url }}" alt="{{ $company->name }}"
                     class="w-20 h-20 rounded-2xl border-4 border-white object-contain bg-white p-2">
                <div class="flex gap-2 pb-1">
                    @if($company->website)<a href="{{ $company->website }}" target="_blank" class="btn-outline text-sm">🌐 Website</a>@endif
                    @if($company->is_verified)<span class="badge-green">✓ Verified</span>@endif
                </div>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $company->name }}</h1>
            @if($company->tagline)<p class="text-gray-500 mt-1">{{ $company->tagline }}</p>@endif
            <div class="flex flex-wrap gap-4 mt-3 text-sm text-gray-500">
                @if($company->industry)<span>🏭 {{ $company->industry }}</span>@endif
                @if($company->company_size)<span>👥 {{ $company->company_size }}</span>@endif
                @if($company->founded_year)<span>📅 Founded {{ $company->founded_year }}</span>@endif
                @if($company->city)<span>📍 {{ $company->city }}</span>@endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            @if($company->description)
            <div class="card p-6 mb-6">
                <h2 class="font-bold text-gray-900 mb-3">About {{ $company->name }}</h2>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $company->description }}</p>
            </div>
            @endif
            <div class="card p-6">
                <h2 class="font-bold text-gray-900 mb-4">Open Positions ({{ $jobs->total() }})</h2>
                @forelse($jobs as $job)
                    @include('partials.job-card', ['job' => $job])
                @empty
                <p class="text-gray-400 text-center py-6">No open positions right now.</p>
                @endforelse
                <div class="mt-4">{{ $jobs->links() }}</div>
            </div>
        </div>
        <div class="space-y-4">
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-3">Company Info</h3>
                <div class="space-y-3 text-sm">
                    @foreach([
                        ['label'=>'Industry','value'=>$company->industry],
                        ['label'=>'Size','value'=>$company->company_size],
                        ['label'=>'Founded','value'=>$company->founded_year],
                        ['label'=>'Email','value'=>$company->email],
                        ['label'=>'Phone','value'=>$company->phone],
                    ] as $row)
                    @if($row['value'])
                    <div class="flex justify-between">
                        <span class="text-gray-400">{{ $row['label'] }}</span>
                        <span class="font-medium text-gray-700">{{ $row['value'] }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
