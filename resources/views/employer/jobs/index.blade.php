<x-app-layout>
<x-slot name="title">My Jobs – ConnectHR</x-slot>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Jobs</h1>
        <a href="{{ route('employer.jobs.create') }}" class="btn-primary">+ Post a Job</a>
    </div>

    <div class="space-y-3">
        @forelse($jobs as $job)
        <div class="card p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-semibold text-gray-800">{{ $job->title }}</h3>
                    <span class="badge text-xs {{ match($job->status) { 'approved'=>'badge-green','pending'=>'badge-yellow','rejected'=>'badge-red','closed'=>'badge-gray',default=>'badge-gray' } }}">{{ ucfirst($job->status) }}</span>
                    @if($job->is_featured)<span class="badge-yellow badge text-xs">⭐ Featured</span>@endif
                </div>
                <div class="flex flex-wrap gap-3 mt-1.5 text-xs text-gray-400">
                    <span>{{ $job->job_type_label }}</span>
                    <span>·</span>
                    <span>{{ $job->applications_count }} applications</span>
                    <span>·</span>
                    <span>{{ $job->views }} views</span>
                    @if($job->deadline)
                    <span>· Deadline: {{ $job->deadline->format('M d, Y') }}</span>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
                <a href="{{ route('employer.applicants', ['job_id' => $job->id]) }}" class="btn-outline text-xs py-1.5">
                    👥 Applicants ({{ $job->applications_count }})
                </a>
                <a href="{{ route('employer.jobs.edit', $job->id) }}" class="btn-secondary text-xs py-1.5">Edit</a>
                @if($job->status === 'approved')
                <form method="POST" action="{{ route('employer.jobs.close', $job->id) }}">@csrf @method('PATCH')
                    <button class="text-xs text-red-400 hover:text-red-600 border border-red-200 rounded-lg px-3 py-1.5 transition">Close</button>
                </form>
                @elseif($job->status === 'closed')
                <form method="POST" action="{{ route('employer.jobs.reopen', $job->id) }}">@csrf @method('PATCH')
                    <button class="text-xs text-green-600 hover:text-green-800 border border-green-200 rounded-lg px-3 py-1.5 transition">Reopen</button>
                </form>
                @endif
                <form method="POST" action="{{ route('employer.jobs.destroy', $job->id) }}" onsubmit="return confirm('Delete this job?')">@csrf @method('DELETE')
                    <button class="text-xs text-red-400 hover:text-red-600">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="card p-16 text-center">
            <div class="text-5xl mb-4">💼</div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No jobs posted yet</h3>
            <a href="{{ route('employer.jobs.create') }}" class="btn-primary mt-2">Post Your First Job</a>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $jobs->links() }}</div>
</div>
</x-app-layout>
