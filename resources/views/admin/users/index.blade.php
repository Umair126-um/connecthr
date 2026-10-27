<x-admin-layout>
<x-slot name="title">Users</x-slot>

{{-- Filters --}}
<div class="flex flex-wrap gap-3 mb-5">
    <form method="GET" class="flex gap-2 flex-wrap flex-1">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name or email…" class="input w-56 text-sm">
        <select name="role" class="select w-36 text-sm" onchange="this.form.submit()">
            <option value="">All Roles</option>
            @foreach(['job_seeker'=>'Job Seeker','employer'=>'Employer','admin'=>'Admin'] as $v=>$l)
            <option value="{{ $v }}" {{ request('role') === $v ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
        </select>
        <select name="status" class="select w-36 text-sm" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Suspended</option>
        </select>
        <button type="submit" class="btn-primary text-sm">Search</button>
        <a href="{{ route('admin.users.index') }}" class="btn-secondary text-sm">Clear</a>
    </form>
    <div class="text-sm text-gray-500 self-center">{{ $users->total() }} users</div>
</div>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">User</th>
                <th class="px-4 py-3 text-left">Role</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Joined</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                        <div>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="font-medium text-gray-800 hover:text-brand-600 transition">{{ $user->name }}</a>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="{{ $user->role === 'admin' ? 'badge-red' : ($user->role === 'employer' ? 'badge-purple' : 'badge-blue') }} badge">
                        {{ ucfirst(str_replace('_',' ',$user->role)) }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="{{ $user->is_active ? 'badge-green' : 'badge-gray' }} badge">
                        {{ $user->is_active ? 'Active' : 'Suspended' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-xs text-brand-600 hover:underline">View</a>
                        @if($user->role !== 'admin')
                        <form method="POST" action="{{ route('admin.users.status', $user->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs {{ $user->is_active ? 'text-yellow-600' : 'text-green-600' }} hover:underline">
                                {{ $user->is_active ? 'Suspend' : 'Activate' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.impersonate', $user->id) }}">
                            @csrf
                            <button type="submit" class="text-xs text-gray-400 hover:text-brand-600 hover:underline">Login As</button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->withQueryString()->links() }}</div>
</x-admin-layout>
