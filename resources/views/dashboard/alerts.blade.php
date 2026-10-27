<x-app-layout>
<x-slot name="title">Job Alerts</x-slot>
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Job Alerts</h1>

    {{-- Create new alert --}}
    <div class="card p-6 mb-6">
        <h2 class="font-semibold text-gray-800 mb-4">Create New Alert</h2>
        <form action="{{ route('seeker.alerts.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div><label class="label">Keywords</label><input type="text" name="keyword" class="input" placeholder="PHP Developer, Marketing Manager…"></div>
                <div><label class="label">Category</label>
                    <select name="category_id" class="select">
                        <option value="">Any Category</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                    </select>
                </div>
                <div><label class="label">Location</label>
                    <select name="location_id" class="select">
                        <option value="">Any Location</option>
                        @foreach($locations as $loc)<option value="{{ $loc->id }}">{{ $loc->city }}</option>@endforeach
                    </select>
                </div>
                <div><label class="label">Job Type</label>
                    <select name="job_type" class="select">
                        <option value="">Any Type</option>
                        @foreach(['full_time'=>'Full Time','part_time'=>'Part Time','contract'=>'Contract','remote'=>'Remote'] as $v=>$l)
                        <option value="{{ $v }}">{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="label">Frequency</label>
                    <select name="frequency" class="select">
                        <option value="daily">Daily Digest</option>
                        <option value="weekly">Weekly Digest</option>
                        <option value="instant">Instant (every new job)</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-primary">Create Alert</button>
        </form>
    </div>

    {{-- Existing alerts --}}
    <div class="space-y-3">
        <h2 class="font-semibold text-gray-800">Your Alerts ({{ $alerts->count() }})</h2>
        @forelse($alerts as $alert)
        <div class="card p-4 flex items-center gap-4">
            <div class="text-2xl flex-shrink-0">🔔</div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-gray-800 text-sm">
                    {{ $alert->keyword ?: 'All Jobs' }}
                    @if($alert->category)<span class="text-gray-400">in {{ $alert->category->name }}</span>@endif
                    @if($alert->location)<span class="text-gray-400">· {{ $alert->location->city }}</span>@endif
                </p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ ucfirst($alert->frequency) }} digest
                    @if($alert->last_sent_at)· Last sent {{ $alert->last_sent_at->diffForHumans() }}@endif
                </p>
            </div>
            <form method="POST" action="{{ route('seeker.alerts.destroy', $alert->id) }}">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-400 hover:text-red-600 transition">Delete</button>
            </form>
        </div>
        @empty
        <div class="card p-12 text-center">
            <div class="text-4xl mb-3">🔔</div>
            <p class="text-gray-500 text-sm">No alerts set up yet. Create one above to get notified of new matching jobs.</p>
        </div>
        @endforelse
    </div>
</div>
</x-app-layout>
