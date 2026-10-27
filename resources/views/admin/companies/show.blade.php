<x-admin-layout>
<x-slot name="title">Company: {{ $company->name }}</x-slot>

<div class="flex items-center gap-3 mb-6 flex-wrap">
    <a href="{{ route('admin.companies.index') }}" class="text-sm text-gray-400 hover:text-brand-600">← Companies</a>
    <div class="ml-auto flex gap-2">
        @if(!$company->is_verified)
        <form method="POST" action="{{ route('admin.companies.verify', $company->id) }}">@csrf @method('PATCH')
            <button class="btn-primary text-sm">✓ Verify Company</button>
        </form>
        @endif
        <form method="POST" action="{{ route('admin.companies.feature', $company->id) }}">@csrf @method('PATCH')
            <button class="btn-secondary text-sm">{{ $company->is_featured ? '★ Unfeature' : '☆ Feature' }}</button>
        </form>
        <form method="POST" action="{{ route('admin.companies.reject', $company->id) }}" class="flex gap-2">@csrf @method('PATCH')
            <input type="text" name="reason" placeholder="Rejection reason…" class="input text-sm w-48">
            <button class="text-sm text-red-500 border border-red-200 rounded-xl px-3 py-2 hover:bg-red-50 transition">Reject</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-center gap-4 mb-4">
                <img src="{{ $company->logo_url }}" class="w-16 h-16 rounded-2xl object-contain bg-gray-50 p-1">
                <div>
                    <h2 class="font-bold text-gray-900">{{ $company->name }}</h2>
                    <span class="badge text-xs {{ $company->is_verified ? 'badge-green' : 'badge-yellow' }}">{{ $company->is_verified ? '✓ Verified' : 'Pending' }}</span>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                @foreach([
                    ['Industry', $company->industry],
                    ['Size', $company->company_size],
                    ['Founded', $company->founded_year],
                    ['Email', $company->email],
                    ['Phone', $company->phone],
                    ['Website', $company->website],
                    ['City', $company->city],
                ] as [$label,$value])
                @if($value)
                <div class="flex justify-between">
                    <span class="text-gray-400">{{ $label }}</span>
                    <span class="font-medium text-gray-700 text-right max-w-32 truncate">{{ $value }}</span>
                </div>
                @endif
                @endforeach
                <div class="flex justify-between pt-1 border-t border-gray-100 mt-2">
                    <span class="text-gray-400">Owner</span>
                    <span class="font-medium text-gray-700">{{ $company->user?->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-5">
        @if($company->description)
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">About</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $company->description }}</p>
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Jobs ({{ $company->jobs->count() }})</h3>
            <div class="space-y-2">
                @forelse($company->jobs->take(10) as $job)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="text-sm font-medium text-gray-800 hover:text-brand-600 transition">{{ $job->title }}</a>
                        <p class="text-xs text-gray-400">{{ $job->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="badge text-xs {{ match($job->status) { 'approved'=>'badge-green','pending'=>'badge-yellow','rejected'=>'badge-red',default=>'badge-gray' } }}">{{ ucfirst($job->status) }}</span>
                </div>
                @empty
                <p class="text-sm text-gray-400">No jobs posted yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
