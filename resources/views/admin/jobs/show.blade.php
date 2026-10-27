<x-admin-layout>
<x-slot name="title">Job: {{ $job->title }}</x-slot>

<div class="flex items-center gap-3 mb-6 flex-wrap">
    <a href="{{ route('admin.jobs.index') }}" class="text-sm text-gray-400 hover:text-brand-600">← Jobs</a>
    <div class="ml-auto flex gap-2">
        <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn-outline text-sm">Edit</a>
        @if($job->status === 'pending')
        <form method="POST" action="{{ route('admin.jobs.approve', $job->id) }}">@csrf @method('PATCH')
            <button class="btn-primary text-sm">✓ Approve</button>
        </form>
        @endif
        @if(in_array($job->status,['pending','approved']))
        <form method="POST" action="{{ route('admin.jobs.reject', $job->id) }}" class="flex gap-2">@csrf @method('PATCH')
            <input type="text" name="reason" placeholder="Rejection reason…" class="input text-sm w-48">
            <button class="text-sm text-red-500 border border-red-200 rounded-xl px-3 py-2 hover:bg-red-50 transition">Reject</button>
        </form>
        @endif
        <form method="POST" action="{{ route('admin.jobs.feature', $job->id) }}">@csrf @method('PATCH')
            <button class="btn-secondary text-sm">{{ $job->is_featured ? '★ Unfeature' : '☆ Feature' }}</button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-start gap-4 mb-5">
                <img src="{{ $job->company?->logo_url }}" class="w-14 h-14 rounded-xl object-contain bg-gray-50 p-1">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $job->title }}</h1>
                    <p class="text-brand-600">{{ $job->company?->name }}</p>
                    <div class="flex gap-2 mt-1">
                        <span class="badge {{ match($job->status) { 'approved'=>'badge-green','pending'=>'badge-yellow','rejected'=>'badge-red',default=>'badge-gray' } }}">{{ ucfirst($job->status) }}</span>
                        @if($job->is_featured)<span class="badge-yellow badge">⭐ Featured</span>@endif
                    </div>
                </div>
            </div>
            @if($job->description)
            <h3 class="font-semibold text-gray-700 mb-2 text-sm">Description</h3>
            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $job->description }}</p>
            @endif
            @if($job->requirements)
            <h3 class="font-semibold text-gray-700 mb-2 text-sm mt-4">Requirements</h3>
            <p class="text-sm text-gray-600 whitespace-pre-wrap">{{ $job->requirements }}</p>
            @endif
        </div>

        {{-- Applications --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Applications ({{ $job->applications->count() }})</h3>
            @foreach($job->applications->take(10) as $app)
            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                <img src="{{ $app->user?->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $app->user?->name }}</p>
                    <p class="text-xs text-gray-400">{{ $app->created_at->format('M d, Y') }}</p>
                </div>
                <span class="status-{{ $app->status }} badge text-xs">{{ ucfirst($app->status) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3 text-sm">Job Details</h3>
            <div class="space-y-2 text-sm">
                @foreach([
                    ['Category', $job->category?->name],
                    ['Location', $job->location?->city],
                    ['Type', $job->job_type_label],
                    ['Experience', ucfirst($job->experience_level)],
                    ['Salary', $job->salary_range],
                    ['Vacancies', $job->vacancies],
                    ['Deadline', $job->deadline?->format('M d, Y')],
                    ['Views', number_format($job->views)],
                ] as [$label,$value])
                @if($value)
                <div class="flex justify-between">
                    <span class="text-gray-400">{{ $label }}</span>
                    <span class="font-medium text-gray-700">{{ $value }}</span>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        @if($job->skills->count())
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3 text-sm">Required Skills</h3>
            <div class="flex flex-wrap gap-1.5">
                @foreach($job->skills as $s)<span class="badge-gray badge text-xs">{{ $s->name }}</span>@endforeach
            </div>
        </div>
        @endif
    </div>
</div>
</x-admin-layout>
