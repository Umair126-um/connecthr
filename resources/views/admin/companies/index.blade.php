<x-admin-layout>
<x-slot name="title">Companies</x-slot>

<form method="GET" class="flex flex-wrap gap-2 mb-5">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search companies…" class="input w-52 text-sm">
    <select name="status" class="select w-44 text-sm" onchange="this.form.submit()">
        <option value="">All Verification Status</option>
        @foreach(['pending'=>'Pending','verified'=>'Verified','rejected'=>'Rejected'] as $v=>$l)
        <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-primary text-sm">Filter</button>
    <a href="{{ route('admin.companies.index') }}" class="btn-secondary text-sm">Clear</a>
    <div class="ml-auto text-sm text-gray-400 self-center">{{ $companies->total() }} companies</div>
</form>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">Company</th>
                <th class="px-4 py-3 text-left">Owner</th>
                <th class="px-4 py-3 text-left">Size</th>
                <th class="px-4 py-3 text-left">Verification</th>
                <th class="px-4 py-3 text-left">Featured</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($companies as $company)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ $company->logo_url }}" class="w-9 h-9 rounded-xl object-contain bg-gray-50 flex-shrink-0">
                        <div>
                            <a href="{{ route('admin.companies.show', $company->id) }}" class="font-medium text-gray-800 hover:text-brand-600 transition">{{ $company->name }}</a>
                            <p class="text-xs text-gray-400">{{ $company->industry }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">{{ $company->user?->email }}</td>
                <td class="px-4 py-3 text-xs text-gray-500">{{ $company->company_size }}</td>
                <td class="px-4 py-3">
                    <span class="badge text-xs {{ match($company->verification_status ?? 'pending') { 'verified'=>'badge-green','pending'=>'badge-yellow','rejected'=>'badge-red',default=>'badge-gray' } }}">
                        {{ ucfirst($company->verification_status ?? 'pending') }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="{{ $company->is_featured ? 'badge-yellow' : 'badge-gray' }} badge text-xs">{{ $company->is_featured ? '⭐ Yes' : 'No' }}</span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.companies.show', $company->id) }}" class="text-xs text-brand-600 hover:underline">View</a>
                        @if(!$company->is_verified)
                        <form method="POST" action="{{ route('admin.companies.verify', $company->id) }}">@csrf @method('PATCH')
                            <button class="text-xs text-green-600 hover:underline">Verify</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.companies.feature', $company->id) }}">@csrf @method('PATCH')
                            <button class="text-xs text-yellow-500 hover:underline">{{ $company->is_featured ? 'Unfeature' : 'Feature' }}</button>
                        </form>
                        <form method="POST" action="{{ route('admin.companies.destroy', $company->id) }}" onsubmit="return confirm('Delete company?')">@csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">No companies found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $companies->withQueryString()->links() }}</div>
</x-admin-layout>
