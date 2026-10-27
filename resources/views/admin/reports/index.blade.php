<x-admin-layout>
<x-slot name="title">Reports</x-slot>

{{-- Overview cards --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
    @foreach([
        ['label'=>'Jobs This Month',  'value'=>$data['jobs_this_month'],  'icon'=>'💼'],
        ['label'=>'New Users',        'value'=>$data['users_this_month'], 'icon'=>'👥'],
        ['label'=>'Applications',     'value'=>$data['apps_this_month'],  'icon'=>'📝'],
    ] as $s)
    <div class="bg-white rounded-2xl border border-gray-200 p-5 text-center">
        <div class="text-3xl mb-1">{{ $s['icon'] }}</div>
        <div class="text-2xl font-bold text-gray-900">{{ number_format($s['value']) }}</div>
        <div class="text-xs text-gray-500 mt-0.5">{{ $s['label'] }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Top categories --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Top Categories</h3>
            <a href="{{ route('admin.reports.jobs') }}" class="text-xs text-brand-600">Detailed →</a>
        </div>
        <div class="space-y-3">
            @foreach($data['top_categories'] as $cat)
            <div class="flex items-center gap-3">
                <span class="text-lg">{{ $cat->icon }}</span>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <p class="text-sm font-medium text-gray-700 truncate">{{ $cat->name }}</p>
                        <span class="text-xs text-gray-400 flex-shrink-0 ml-2">{{ $cat->jobs_count }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1">
                        <div class="bg-brand-600 h-1 rounded-full" style="width:{{ $data['top_categories']->max('jobs_count') > 0 ? ($cat->jobs_count / $data['top_categories']->max('jobs_count') * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Top companies --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Most Applications Received</h3>
        <div class="space-y-3">
            @foreach($data['top_companies'] as $co)
            <div class="flex items-center gap-3">
                <img src="{{ $co->logo_url }}" class="w-8 h-8 rounded-lg object-contain bg-gray-50">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ $co->name }}</p>
                </div>
                <span class="text-xs font-semibold text-brand-600">{{ $co->applications_count }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Top jobs by views --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Most Viewed Jobs</h3>
        <div class="space-y-3">
            @foreach($data['top_jobs'] as $job)
            <div class="flex items-center gap-3">
                <div class="text-lg font-bold text-gray-200 font-display w-5 text-center">{{ $loop->iteration }}</div>
                <div class="flex-1 min-w-0">
                    <a href="{{ route('admin.jobs.show', $job->id) }}" class="text-sm font-medium text-gray-700 hover:text-brand-600 truncate block">{{ $job->title }}</a>
                </div>
                <span class="text-xs text-gray-400">{{ number_format($job->views) }} views</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Export links --}}
<div class="flex gap-3 flex-wrap">
    <a href="{{ route('admin.reports.jobs') }}" class="btn-outline text-sm">📊 Jobs Report</a>
    <a href="{{ route('admin.reports.users') }}" class="btn-outline text-sm">👥 Users Report</a>
    <a href="{{ route('admin.reports.applications') }}" class="btn-outline text-sm">📝 Applications Report</a>
    <a href="{{ route('admin.reports.export', 'jobs') }}" class="btn-secondary text-sm">⬇ Export Jobs CSV</a>
    <a href="{{ route('admin.reports.export', 'users') }}" class="btn-secondary text-sm">⬇ Export Users CSV</a>
</div>
</x-admin-layout>
