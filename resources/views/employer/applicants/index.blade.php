<x-app-layout>
<x-slot name="title">Applicants</x-slot>
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Applicants</h1>

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-2 mb-5">
        <select name="job_id" class="select w-52 text-sm" onchange="this.form.submit()">
            <option value="">All Jobs</option>
            @foreach($jobs as $j)<option value="{{ $j->id }}" {{ request('job_id') == $j->id ? 'selected' : '' }}>{{ $j->title }}</option>@endforeach
        </select>
        <select name="status" class="select w-36 text-sm" onchange="this.form.submit()">
            <option value="">All Status</option>
            @foreach(['applied','viewed','shortlisted','interview','hired','rejected'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <a href="{{ route('employer.applicants') }}" class="btn-secondary text-sm">Clear</a>
        <span class="self-center text-sm text-gray-400 ml-auto">{{ $applications->total() }} applicants</span>
    </form>

    <div class="space-y-3">
        @forelse($applications as $app)
        <div class="card p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <img src="{{ $app->user?->avatar_url }}" class="w-12 h-12 rounded-full object-cover flex-shrink-0">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-semibold text-gray-800">{{ $app->user?->name }}</h3>
                    <span class="status-{{ $app->status }} badge text-xs">{{ ucfirst($app->status) }}</span>
                    @if(!$app->viewed_at)<span class="badge-blue badge text-xs">New</span>@endif
                </div>
                <div class="flex flex-wrap gap-3 mt-1 text-xs text-gray-400">
                    @if($app->user?->profile?->title)<span>{{ $app->user->profile->title }}</span><span>·</span>@endif
                    <span>Applied for <strong class="text-gray-600">{{ $app->job?->title }}</strong></span>
                    <span>·</span>
                    <span>{{ $app->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0 flex-wrap">
                <a href="{{ route('employer.applicants.show', $app->id) }}" class="btn-primary text-xs py-1.5">View Profile</a>
                @if($app->cv_file)
                <a href="{{ route('employer.applicants.cv', $app->id) }}" class="btn-outline text-xs py-1.5">📄 CV</a>
                @endif
                <select onchange="updateStatus({{ $app->id }}, this.value)"
                        class="select text-xs py-1.5 w-36 {{ 'status-'.$app->status }}">
                    @foreach(['viewed'=>'Viewed','shortlisted'=>'Shortlist','interview'=>'Interview','hired'=>'Hired','rejected'=>'Reject'] as $v=>$l)
                    <option value="{{ $v }}" {{ $app->status === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @empty
        <div class="card p-16 text-center">
            <div class="text-5xl mb-4">👥</div>
            <h3 class="text-lg font-semibold text-gray-700">No applicants yet</h3>
            <p class="text-sm text-gray-400 mt-1">Applications will appear here once candidates apply to your jobs</p>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $applications->withQueryString()->links() }}</div>
</div>

<script>
function updateStatus(id, status) {
    fetch(`/employer/applicants/${id}/status`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
        body: JSON.stringify({ status })
    }).then(() => location.reload());
}
</script>
</x-app-layout>
