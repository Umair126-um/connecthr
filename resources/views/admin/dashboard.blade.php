<x-admin-layout>
<x-slot name="title">Dashboard</x-slot>

{{-- Stats Grid --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label'=>'Total Jobs',         'value'=>$stats['total_jobs'],       'icon'=>'💼', 'color'=>'blue',   'sub'=>$stats['pending_jobs'].' pending approval'],
        ['label'=>'Total Users',        'value'=>$stats['total_users'],      'icon'=>'👥', 'color'=>'green',  'sub'=>$stats['total_seekers'].' seekers, '.$stats['total_employers'].' employers'],
        ['label'=>'Companies',          'value'=>$stats['total_companies'],  'icon'=>'🏢', 'color'=>'purple', 'sub'=>$stats['pending_companies'].' pending verification'],
        ['label'=>'Applications',       'value'=>$stats['total_applications'],'icon'=>'📝','color'=>'orange', 'sub'=>'Total received'],
    ] as $s)
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-2xl">{{ $s['icon'] }}</span>
        </div>
        <p class="text-2xl font-bold font-display text-gray-900">{{ number_format($s['value']) }}</p>
        <p class="text-sm font-medium text-gray-700">{{ $s['label'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $s['sub'] }}</p>
    </div>
    @endforeach
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Jobs Posted ({{ date('Y') }})</h3>
        <canvas id="jobsChart" height="200"></canvas>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Applications ({{ date('Y') }})</h3>
        <canvas id="appsChart" height="200"></canvas>
    </div>
</div>

{{-- Tables Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Pending Jobs --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Recent Jobs Pending Approval</h3>
            <a href="{{ route('admin.jobs.index', ['status'=>'pending']) }}" class="text-xs text-brand-600">View all</a>
        </div>
        <div class="space-y-3">
            @forelse($recentJobs as $job)
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $job->title }}</p>
                    <p class="text-xs text-gray-400">{{ $job->company?->name }}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="badge {{ $job->status === 'pending' ? 'badge-yellow' : ($job->status === 'approved' ? 'badge-green' : 'badge-red') }}">
                        {{ ucfirst($job->status) }}
                    </span>
                    @if($job->status === 'pending')
                    <form method="POST" action="{{ route('admin.jobs.approve', $job->id) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs bg-brand-600 text-white px-2 py-1 rounded hover:bg-brand-700 transition">Approve</button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400">No pending jobs.</p>
            @endforelse
        </div>
    </div>

    {{-- Pending Companies --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Companies Pending Verification</h3>
            <a href="{{ route('admin.companies.index', ['status'=>'pending']) }}" class="text-xs text-brand-600">View all</a>
        </div>
        <div class="space-y-3">
            @forelse($pendingCompanies as $company)
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <img src="{{ $company->logo_url }}" class="w-8 h-8 rounded-lg object-contain bg-gray-50">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $company->name }}</p>
                        <p class="text-xs text-gray-400">{{ $company->user?->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.companies.verify', $company->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 transition">Verify</button>
                </form>
            </div>
            @empty
            <p class="text-sm text-gray-400">No pending verifications.</p>
            @endforelse
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Recent Signups</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-brand-600">View all</a>
        </div>
        <div class="space-y-3">
            @foreach($recentUsers as $user)
            <div class="flex items-center gap-3">
                <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                </div>
                <span class="badge {{ $user->role === 'employer' ? 'badge-purple' : 'badge-blue' }}">{{ ucfirst($user->role) }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Applications --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Recent Applications</h3>
            <a href="{{ route('admin.applications') }}" class="text-xs text-brand-600">View all</a>
        </div>
        <div class="space-y-3">
            @foreach($recentApplications as $app)
            <div class="flex items-center gap-3">
                <img src="{{ $app->user?->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $app->user?->name }}</p>
                    <p class="text-xs text-gray-400 truncate">Applied for {{ $app->job?->title }}</p>
                </div>
                <span class="badge status-{{ $app->status }}">{{ ucfirst($app->status) }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
const jobsData  = @json(array_map(fn($m) => $jobsChart[$m] ?? 0, range(1,12)));
const appsData  = @json(array_map(fn($m) => $applicationsChart[$m] ?? 0, range(1,12)));
const cfg = (data, color) => ({
    type: 'bar',
    data: { labels: months, datasets: [{ data, backgroundColor: color + '33', borderColor: color, borderWidth: 2, borderRadius: 4 }] },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
});
new Chart(document.getElementById('jobsChart'), cfg(jobsData, '#16a34a'));
new Chart(document.getElementById('appsChart'), cfg(appsData, '#2563eb'));
</script>
@endpush
</x-admin-layout>
