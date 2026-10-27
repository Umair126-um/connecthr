<x-admin-layout>
<x-slot name="title">Jobs Report</x-slot>

<div class="flex items-center justify-between mb-5">
    <a href="{{ route('admin.reports') }}" class="text-sm text-gray-400 hover:text-brand-600">← Reports</a>
    <a href="{{ route('admin.reports.export', 'jobs') }}" class="btn-secondary text-sm">⬇ Export CSV</a>
</div>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">Job</th>
                <th class="px-4 py-3 text-left">Company</th>
                <th class="px-4 py-3 text-left">Category</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Views</th>
                <th class="px-4 py-3 text-left">Posted</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($jobs as $job)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    <a href="{{ route('admin.jobs.show', $job->id) }}" class="font-medium text-gray-800 hover:text-brand-600 transition">{{ $job->title }}</a>
                </td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $job->company?->name }}</td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $job->category?->name }}</td>
                <td class="px-4 py-3">
                    <span class="badge text-xs {{ match($job->status) { 'approved'=>'badge-green','pending'=>'badge-yellow','rejected'=>'badge-red',default=>'badge-gray' } }}">{{ ucfirst($job->status) }}</span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ number_format($job->views) }}</td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $job->created_at->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">No jobs.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $jobs->links() }}</div>
</x-admin-layout>
