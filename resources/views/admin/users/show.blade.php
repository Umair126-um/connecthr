<x-admin-layout>
<x-slot name="title">User: {{ $user->name }}</x-slot>

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-400 hover:text-brand-600">← Users</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Profile card --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-6 text-center">
            <img src="{{ $user->avatar_url }}" class="w-20 h-20 rounded-full mx-auto object-cover mb-3">
            <h2 class="font-bold text-gray-900 text-lg">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
            <div class="flex justify-center gap-2 mt-3">
                <span class="{{ $user->role === 'employer' ? 'badge-purple' : 'badge-blue' }} badge">{{ ucfirst(str_replace('_',' ',$user->role)) }}</span>
                <span class="{{ $user->is_active ? 'badge-green' : 'badge-gray' }} badge">{{ $user->is_active ? 'Active' : 'Suspended' }}</span>
            </div>
            <div class="mt-4 space-y-2 text-left text-sm">
                <div class="flex justify-between"><span class="text-gray-400">Joined</span><span class="font-medium text-gray-700">{{ $user->created_at->format('M d, Y') }}</span></div>
                @if($user->phone)<div class="flex justify-between"><span class="text-gray-400">Phone</span><span class="font-medium text-gray-700">{{ $user->phone }}</span></div>@endif
            </div>
            @if($user->role !== 'admin')
            <div class="flex gap-2 mt-5 flex-col">
                <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-secondary w-full text-sm">
                        {{ $user->is_active ? 'Suspend Account' : 'Activate Account' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.users.impersonate', $user->id) }}">
                    @csrf
                    <button type="submit" class="btn-outline w-full text-sm">Login As This User</button>
                </form>
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Permanently delete this user?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-sm text-red-500 hover:text-red-700 border border-red-200 rounded-xl py-2.5 transition">Delete User</button>
                </form>
            </div>
            @endif
        </div>

        {{-- Profile completion (seekers) --}}
        @if($user->profile)
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3 text-sm">Profile</h3>
            <div class="space-y-2 text-sm">
                @if($user->profile->title)<div class="text-gray-600">{{ $user->profile->title }}</div>@endif
                @if($user->profile->city)<div class="text-gray-400 flex items-center gap-1">📍 {{ $user->profile->city }}</div>@endif
                @if($user->profile->cv_file)
                <a href="{{ asset('storage/'.$user->profile->cv_file) }}" target="_blank" class="flex items-center gap-1 text-brand-600 hover:underline">📄 Download CV</a>
                @endif
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex-1 bg-gray-200 rounded-full h-1.5">
                        <div class="bg-brand-600 h-1.5 rounded-full" style="width:{{ $user->profile->profile_completion ?? 0 }}%"></div>
                    </div>
                    <span class="text-xs text-gray-400">{{ $user->profile->profile_completion ?? 0 }}%</span>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Right column --}}
    <div class="lg:col-span-2 space-y-5">
        {{-- Applications --}}
        @if($user->applications->count())
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Applications ({{ $user->applications->count() }})</h3>
            <div class="space-y-2">
                @foreach($user->applications->take(10) as $app)
                <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $app->job?->title }}</p>
                        <p class="text-xs text-gray-400">{{ $app->job?->company?->name }} · {{ $app->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="status-{{ $app->status }} badge text-xs">{{ ucfirst($app->status) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Company (employers) --}}
        @if($user->company)
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Company</h3>
            <div class="flex items-center gap-3">
                <img src="{{ $user->company->logo_url }}" class="w-12 h-12 rounded-xl object-contain bg-gray-50">
                <div>
                    <p class="font-semibold text-gray-800">{{ $user->company->name }}</p>
                    <p class="text-sm text-gray-400">{{ $user->company->industry }} · {{ $user->company->company_size }}</p>
                    <span class="{{ $user->company->is_verified ? 'badge-green' : 'badge-yellow' }} badge text-xs mt-1">
                        {{ $user->company->is_verified ? '✓ Verified' : 'Pending Verification' }}
                    </span>
                </div>
            </div>
        </div>
        @endif

        {{-- Skills --}}
        @if($user->skills?->count())
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Skills</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($user->skills as $skill)
                <span class="badge-gray badge">{{ $skill->name }}</span>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
</x-admin-layout>
