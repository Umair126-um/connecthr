{{-- resources/views/dashboard/applications/index.blade.php --}}
<x-app-layout>
<x-slot name="title">My Applications</x-slot>
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">My Applications</h1>
    <div class="space-y-4">
        @forelse($applications as $app)
        <div class="card p-5 flex items-center gap-4 flex-wrap">
            <img src="{{ $app->job?->company?->logo_url }}" class="w-12 h-12 rounded-xl object-contain bg-gray-50 flex-shrink-0">
            <div class="flex-1 min-w-0">
                <a href="{{ route('jobs.show', $app->job?->slug) }}" class="font-semibold text-gray-800 hover:text-brand-600 transition">{{ $app->job?->title }}</a>
                <div class="flex flex-wrap gap-2 mt-1 text-xs text-gray-400">
                    <span>{{ $app->job?->company?->name }}</span>
                    <span>·</span>
                    <span>Applied {{ $app->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0">
                <span class="status-{{ $app->status }} badge">{{ ucfirst($app->status) }}</span>
                @if($app->status === 'applied')
                <form method="POST" action="{{ route('seeker.applications.withdraw', $app->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">Withdraw</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="card p-16 text-center">
            <div class="text-5xl mb-4">📝</div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No applications yet</h3>
            <a href="{{ route('jobs.index') }}" class="btn-primary mt-2">Browse Jobs</a>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $applications->links() }}</div>
</div>
</x-app-layout>
