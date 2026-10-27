<x-admin-layout>
<x-slot name="title">Users Report</x-slot>
<div class="flex items-center justify-between mb-5">
    <a href="{{ route('admin.reports') }}" class="text-sm text-gray-400 hover:text-brand-600">← Reports</a>
    <a href="{{ route('admin.reports.export', 'users') }}" class="btn-secondary text-sm">⬇ Export CSV</a>
</div>
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">User</th>
                <th class="px-4 py-3 text-left">Role</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Joined</th>
                <th class="px-4 py-3 text-left">Last Login</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <img src="{{ $user->avatar_url }}" class="w-7 h-7 rounded-full object-cover">
                        <div>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="font-medium text-gray-800 hover:text-brand-600 transition">{{ $user->name }}</a>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3"><span class="{{ $user->role === 'employer' ? 'badge-purple' : 'badge-blue' }} badge text-xs">{{ ucfirst(str_replace('_',' ',$user->role)) }}</span></td>
                <td class="px-4 py-3"><span class="{{ $user->is_active ? 'badge-green' : 'badge-gray' }} badge text-xs">{{ $user->is_active ? 'Active' : 'Suspended' }}</span></td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $user->last_login_at?->format('M d, Y') ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No users.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
</x-admin-layout>
