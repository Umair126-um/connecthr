<x-admin-layout>
<x-slot name="title">CV Manager</x-slot>

<div class="flex items-center justify-between mb-5">
    <p class="text-sm text-gray-500">{{ $profiles->total() }} CVs uploaded</p>
    <a href="{{ route('admin.cvs.bulk') }}" class="btn-secondary text-sm">⬇ Download All (ZIP)</a>
</div>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">Job Seeker</th>
                <th class="px-4 py-3 text-left">Title</th>
                <th class="px-4 py-3 text-left">Location</th>
                <th class="px-4 py-3 text-left">Completion</th>
                <th class="px-4 py-3 text-right">CV</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($profiles as $profile)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <img src="{{ $profile->user?->avatar_url }}" class="w-7 h-7 rounded-full object-cover">
                        <div>
                            <p class="font-medium text-gray-800">{{ $profile->user?->name }}</p>
                            <p class="text-xs text-gray-400">{{ $profile->user?->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $profile->title ?? '—' }}</td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $profile->city ?? '—' }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <div class="w-20 bg-gray-200 rounded-full h-1.5">
                            <div class="bg-brand-600 h-1.5 rounded-full" style="width:{{ $profile->profile_completion ?? 0 }}%"></div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $profile->profile_completion ?? 0 }}%</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.cvs.download', $profile->id) }}" class="text-xs text-brand-600 hover:underline">⬇ Download</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No CVs uploaded yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $profiles->links() }}</div>
</x-admin-layout>
