{{-- resources/views/dashboard/index.blade.php --}}
<x-app-layout>
<x-slot name="title">Dashboard – ConnectHR</x-slot>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- Sidebar --}}
        <aside class="lg:w-60 flex-shrink-0">
            <div class="card p-4">
                <div class="text-center mb-4 pb-4 border-b border-gray-100">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                         class="w-16 h-16 rounded-full mx-auto mb-2 object-cover">
                    <p class="font-semibold text-gray-800 text-sm">{{ auth()->user()->name }}</p>
                    <div class="mt-2">
                        <div class="text-xs text-gray-400 mb-1">Profile {{ $completion }}% complete</div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                            <div class="bg-brand-600 h-1.5 rounded-full" style="width:{{ $completion }}%"></div>
                        </div>
                    </div>
                </div>
                <nav class="space-y-1">
                    <a href="{{ route('seeker.index') }}" class="sidebar-link {{ request()->routeIs('seeker.index') ? 'sidebar-link-active' : '' }}">📊 Dashboard</a>
                    <a href="{{ route('seeker.profile') }}" class="sidebar-link {{ request()->routeIs('seeker.profile') ? 'sidebar-link-active' : '' }}">👤 My Profile</a>
                    <a href="{{ route('seeker.applications') }}" class="sidebar-link {{ request()->routeIs('seeker.applications') ? 'sidebar-link-active' : '' }}">📝 Applications</a>
                    <a href="{{ route('seeker.saved') }}" class="sidebar-link {{ request()->routeIs('seeker.saved') ? 'sidebar-link-active' : '' }}">❤️ Saved Jobs</a>
                    <a href="{{ route('seeker.alerts') }}" class="sidebar-link {{ request()->routeIs('seeker.alerts') ? 'sidebar-link-active' : '' }}">🔔 Job Alerts</a>
                </nav>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">
                Welcome back, {{ Str::before(auth()->user()->name, ' ') }}! 👋
            </h1>

            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                @foreach([
                    ['icon'=>'📝','label'=>'Applied',     'value'=>$stats['applied'],     'color'=>'blue'],
                    ['icon'=>'❤️','label'=>'Saved',        'value'=>$stats['saved'],        'color'=>'red'],
                    ['icon'=>'⭐','label'=>'Shortlisted',  'value'=>$stats['shortlisted'],  'color'=>'yellow'],
                    ['icon'=>'🎯','label'=>'Interviews',   'value'=>$stats['interviews'],   'color'=>'green'],
                ] as $s)
                <div class="card p-4 text-center">
                    <div class="text-2xl mb-1">{{ $s['icon'] }}</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $s['value'] }}</div>
                    <div class="text-xs text-gray-500">{{ $s['label'] }}</div>
                </div>
                @endforeach
            </div>

            {{-- Profile incomplete alert --}}
            @if($completion < 70)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 flex items-center justify-between gap-4">
                <div>
                    <p class="font-semibold text-yellow-800 text-sm">Complete your profile to get noticed by employers</p>
                    <p class="text-xs text-yellow-600 mt-0.5">Your profile is {{ $completion }}% complete</p>
                </div>
                <a href="{{ route('seeker.profile') }}" class="btn-primary text-sm flex-shrink-0">Complete Profile</a>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Recent Applications --}}
                <div class="card p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Recent Applications</h3>
                        <a href="{{ route('seeker.applications') }}" class="text-xs text-brand-600">View all</a>
                    </div>
                    @forelse($recentApplications as $app)
                    <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                        <img src="{{ $app->job?->company?->logo_url }}" class="w-9 h-9 rounded-lg object-contain bg-gray-50">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $app->job?->title }}</p>
                            <p class="text-xs text-gray-400">{{ $app->job?->company?->name }}</p>
                        </div>
                        <span class="badge status-{{ $app->status }} flex-shrink-0">{{ $app->status_label }}</span>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-gray-400 text-sm">No applications yet</p>
                        <a href="{{ route('jobs.index') }}" class="btn-primary text-sm mt-3">Browse Jobs</a>
                    </div>
                    @endforelse
                </div>

                {{-- Saved Jobs --}}
                <div class="card p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-800">Saved Jobs</h3>
                        <a href="{{ route('seeker.saved') }}" class="text-xs text-brand-600">View all</a>
                    </div>
                    @forelse($savedJobs as $job)
                    <div class="flex items-center gap-3 py-2 border-b border-gray-50 last:border-0">
                        <img src="{{ $job->company?->logo_url }}" class="w-9 h-9 rounded-lg object-contain bg-gray-50">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('jobs.show', $job->slug) }}" class="text-sm font-medium text-gray-800 hover:text-brand-600 transition truncate block">{{ $job->title }}</a>
                            <p class="text-xs text-gray-400">{{ $job->company?->name }}</p>
                        </div>
                        <span class="badge-blue flex-shrink-0 text-xs">{{ $job->job_type_label }}</span>
                    </div>
                    @empty
                    <div class="text-center py-6">
                        <p class="text-gray-400 text-sm">No saved jobs yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
