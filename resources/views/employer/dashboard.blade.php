{{-- resources/views/employer/dashboard.blade.php --}}
<x-app-layout>
<x-slot name="title">Employer Dashboard</x-slot>
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Employer Dashboard</h1>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['icon'=>'💼','label'=>'Total Jobs',    'value'=>$stats['total_jobs']],
            ['icon'=>'✅','label'=>'Active Jobs',   'value'=>$stats['active_jobs']],
            ['icon'=>'📝','label'=>'Applications',  'value'=>$stats['applications']],
            ['icon'=>'⭐','label'=>'Shortlisted',   'value'=>$stats['shortlisted']],
        ] as $s)
        <div class="card p-5 text-center">
            <div class="text-3xl mb-1">{{ $s['icon'] }}</div>
            <div class="text-2xl font-bold text-gray-900">{{ $s['value'] }}</div>
            <div class="text-xs text-gray-500 mt-0.5">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    @if(!$company)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
        <h3 class="font-semibold text-yellow-800 mb-1">Complete your company profile first</h3>
        <p class="text-sm text-yellow-600 mb-3">You need to set up your company before posting jobs.</p>
        <a href="{{ route('employer.company.edit') }}" class="btn-primary text-sm">Setup Company Profile</a>
    </div>
    @else
    <div class="flex gap-3 mb-6">
        <a href="{{ route('employer.jobs.create') }}" class="btn-primary">+ Post a Job</a>
        <a href="{{ route('employer.jobs') }}" class="btn-outline">My Jobs</a>
        <a href="{{ route('employer.applicants') }}" class="btn-outline">All Applicants</a>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Applicants --}}
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">Recent Applicants</h3>
                <a href="{{ route('employer.applicants') }}" class="text-xs text-brand-600">View all</a>
            </div>
            @forelse($recentApplicants as $app)
            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                <img src="{{ $app->user?->avatar_url }}" class="w-9 h-9 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $app->user?->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ $app->job?->title }}</p>
                </div>
                <span class="badge status-{{ $app->status }} flex-shrink-0 text-xs">{{ ucfirst($app->status) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No applications yet</p>
            @endforelse
        </div>

        {{-- My Jobs --}}
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-800">My Job Postings</h3>
                <a href="{{ route('employer.jobs') }}" class="text-xs text-brand-600">View all</a>
            </div>
            @forelse($recentJobs as $job)
            <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $job->title }}</p>
                    <p class="text-xs text-gray-400">{{ $job->applications_count }} applications</p>
                </div>
                <span class="badge {{ $job->status === 'approved' ? 'badge-green' : ($job->status === 'pending' ? 'badge-yellow' : 'badge-red') }}">{{ ucfirst($job->status) }}</span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No jobs posted yet</p>
            @endforelse
        </div>
    </div>
</div>
</x-app-layout>
