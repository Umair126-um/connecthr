<x-admin-layout>
<x-slot name="title">Applications Report</x-slot>
<div class="flex items-center justify-between mb-5">
    <a href="{{ route('admin.reports') }}" class="text-sm text-gray-400 hover:text-brand-600">← Reports</a>
    <a href="{{ route('admin.applications.export') }}" class="btn-secondary text-sm">⬇ Export CSV</a>
</div>
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">Applicant</th>
                <th class="px-4 py-3 text-left">Job</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Applied</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($applications as $app)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <img src="{{ $app->user?->avatar_url }}" class="w-7 h-7 rounded-full object-cover">
                        <div>
                            <p class="font-medium text-gray-800">{{ $app->user?->name }}</p>
                            <p class="text-xs text-gray-400">{{ $app->user?->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <a href="{{ route('admin.jobs.show', $app->job_id) }}" class="text-sm text-gray-700 hover:text-brand-600 transition">{{ $app->job?->title }}</a>
                    <p class="text-xs text-gray-400">{{ $app->job?->company?->name }}</p>
                </td>
                <td class="px-4 py-3"><span class="status-{{ $app->status }} badge text-xs">{{ ucfirst($app->status) }}</span></td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $app->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400">No applications.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $applications->links() }}</div>
</x-admin-layout>
