<x-admin-layout>
<x-slot name="title">Jobs</x-slot>

<form method="GET" class="flex flex-wrap gap-2 mb-5">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search jobs…" class="input w-52 text-sm">
    <select name="status" class="select w-36 text-sm" onchange="this.form.submit()">
        <option value="">All Status</option>
        @foreach(['pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected','closed'=>'Closed'] as $v=>$l)
        <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>
        @endforeach
    </select>
    <select name="category" class="select w-40 text-sm" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary text-sm">Filter</button>
    <a href="{{ route('admin.jobs.index') }}" class="btn-secondary text-sm">Clear</a>
    <div class="ml-auto text-sm text-gray-400 self-center">{{ $jobs->total() }} jobs</div>
</form>

{{-- Bulk form --}}
<form id="bulkForm" method="POST" action="{{ route('admin.jobs.bulk') }}">
    @csrf
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <div class="px-4 py-2 border-b border-gray-100 flex items-center gap-3 bg-gray-50">
        <input type="checkbox" id="selectAll" class="accent-brand-600" onchange="document.querySelectorAll('.row-check').forEach(c=>c.checked=this.checked)">
        <label for="selectAll" class="text-xs text-gray-500">Select all</label>
        <select name="action" class="select text-xs w-36 py-1">
            <option value="approve">Approve</option>
            <option value="reject">Reject</option>
            <option value="feature">Mark Featured</option>
            <option value="delete">Delete</option>
        </select>
        <button type="submit" class="btn-primary text-xs py-1 px-3" onclick="return confirm('Apply bulk action?')">Apply</button>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 w-8"></th>
                <th class="px-4 py-3 text-left">Job</th>
                <th class="px-4 py-3 text-left">Company</th>
                <th class="px-4 py-3 text-left">Type</th>
                <th class="px-4 py-3 text-left">Apps</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Posted</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($jobs as $job)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3"><input type="checkbox" name="ids[]" value="{{ $job->id }}" class="row-check accent-brand-600"></td>
                <td class="px-4 py-3 max-w-xs">
                    <a href="{{ route('admin.jobs.show', $job->id) }}" class="font-medium text-gray-800 hover:text-brand-600 transition truncate block">{{ $job->title }}</a>
                    <p class="text-xs text-gray-400">{{ $job->location?->city }} @if($job->is_featured)<span class="text-yellow-500 ml-1">⭐</span>@endif</p>
                </td>
                <td class="px-4 py-3 text-gray-500">
                    <div class="flex items-center gap-2">
                        <img src="{{ $job->company?->logo_url }}" class="w-6 h-6 rounded object-contain bg-gray-50">
                        <span class="truncate text-xs">{{ $job->company?->name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3"><span class="badge-blue badge text-xs">{{ $job->job_type_label }}</span></td>
                <td class="px-4 py-3 text-gray-500">{{ $job->applications_count }}</td>
                <td class="px-4 py-3">
                    <span class="badge text-xs {{ match($job->status) { 'approved'=>'badge-green','pending'=>'badge-yellow','rejected'=>'badge-red',default=>'badge-gray' } }}">
                        {{ ucfirst($job->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $job->created_at->format('M d, Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.jobs.show', $job->id) }}" class="text-xs text-brand-600 hover:underline">View</a>
                        @if($job->status === 'pending')
                        <form method="POST" action="{{ route('admin.jobs.approve', $job->id) }}">@csrf @method('PATCH')
                            <button type="submit" class="text-xs text-green-600 hover:underline">Approve</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.jobs.feature', $job->id) }}">@csrf @method('PATCH')
                            <button type="submit" class="text-xs text-yellow-500 hover:underline">{{ $job->is_featured ? 'Unfeature' : 'Feature' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.jobs.destroy', $job->id) }}" onsubmit="return confirm('Delete job?')">@csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400">No jobs found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</form>
<div class="mt-4">{{ $jobs->withQueryString()->links() }}</div>
</x-admin-layout>
