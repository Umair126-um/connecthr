{{-- resources/views/partials/job-card.blade.php --}}
<a href="{{ route('jobs.show', $job->slug) }}"
   class="job-card group">
    <div class="flex items-start gap-4">
        <img src="{{ $job->company?->logo_url }}" alt="{{ $job->company?->name }}"
             class="w-12 h-12 rounded-xl object-contain bg-gray-50 p-1 flex-shrink-0">
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
                <h3 class="font-semibold text-gray-900 group-hover:text-brand-600 transition truncate">
                    {{ $job->title }}
                </h3>
                <div class="flex gap-1 flex-shrink-0">
                    @if($job->is_urgent)  <span class="badge-urgent">Urgent</span> @endif
                    @if($job->is_featured)<span class="badge-featured">⭐</span> @endif
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-0.5">{{ $job->company?->name }}</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-2 text-xs">
        @if($job->location)
        <span class="flex items-center gap-1 text-gray-500">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            {{ $job->location->city }}
        </span>
        @endif
        <span class="badge-blue">{{ $job->job_type_label }}</span>
        @if($job->category)
            <span class="badge-gray">{{ $job->category->name }}</span>
        @endif
    </div>

    <div class="flex items-center justify-between mt-1">
        <span class="text-sm font-semibold text-brand-600">
            {{ $job->salary_range }}
        </span>
        <span class="text-xs text-gray-400">{{ timeAgo($job->created_at) }}</span>
    </div>
</a>
