<x-admin-layout>
<x-slot name="title">Applications</x-slot>

<div class="flex items-center justify-between mb-5 flex-wrap gap-3">
    <form method="GET" class="flex gap-2 flex-wrap">
        <select name="status" class="select w-36 text-sm" onchange="this.form.submit()">
            <option value="">All Status</option>
            @foreach(['applied','viewed','shortlisted','interview','hired','rejected'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <a href="{{ route('admin.applications.export') }}" class="btn-secondary text-sm">⬇ Export CSV</a>
    </form>
    <div class="text-sm text-gray-400">{{ $applications->total() }} total</div>
</div>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">Applicant</th>
                <th class="px-4 py-3 text-left">Job</th>
                <th class="px-4 py-3 text-left">Company</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Applied</th>
                <th class="px-4 py-3 text-right">CV</th>
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
                <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $app->job?->title }}</td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $app->job?->company?->name }}</td>
                <td class="px-4 py-3"><span class="status-{{ $app->status }} badge text-xs">{{ ucfirst($app->status) }}</span></td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $app->created_at->format('M d, Y') }}</td>
                <td class="px-4 py-3 text-right">
                    @if($app->cv_file)
                    <a href="{{ asset('storage/'.$app->cv_file) }}" target="_blank" class="text-xs text-brand-600 hover:underline">📄 CV</a>
                    @else
                    <span class="text-xs text-gray-300">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">No applications.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $applications->withQueryString()->links() }}</div>
</x-admin-layout>
