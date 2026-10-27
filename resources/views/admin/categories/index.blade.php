<x-admin-layout>
<x-slot name="title">Categories</x-slot>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Add form --}}
    <div class="bg-white rounded-2xl border border-gray-200 p-5 h-fit">
        <h3 class="font-semibold text-gray-800 mb-4">Add Category</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Name *</label><input type="text" name="name" class="input" required placeholder="e.g. Information Technology"></div>
            <div><label class="label">Urdu Name</label><input type="text" name="name_ur" class="input" placeholder="اردو میں نام" dir="rtl"></div>
            <div><label class="label">Icon (emoji)</label><input type="text" name="icon" class="input" placeholder="💻"></div>
            <div><label class="label">Display Order</label><input type="number" name="order" class="input" value="0"></div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="cat_active" checked class="accent-brand-600">
                <label for="cat_active" class="text-sm text-gray-600">Active</label>
            </div>
            <button type="submit" class="btn-primary w-full">Add Category</button>
        </form>
    </div>

    {{-- List --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">All Categories</h3>
            <span class="text-xs text-gray-400">{{ $categories->count() }} total</span>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-2.5 text-left">Name</th>
                    <th class="px-4 py-2.5 text-left">Jobs</th>
                    <th class="px-4 py-2.5 text-left">Status</th>
                    <th class="px-4 py-2.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($categories as $cat)
                <tr class="hover:bg-gray-50 transition" x-data="{ editing: false }">
                    <td class="px-4 py-2.5">
                        <div x-show="!editing" class="flex items-center gap-2">
                            <span class="text-xl">{{ $cat->icon }}</span>
                            <div>
                                <p class="font-medium text-gray-800">{{ $cat->name }}</p>
                                @if($cat->name_ur)<p class="text-xs text-gray-400" dir="rtl">{{ $cat->name_ur }}</p>@endif
                            </div>
                        </div>
                        <form x-show="editing" method="POST" action="{{ route('admin.categories.update', $cat->id) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="name" value="{{ $cat->name }}" class="input text-sm flex-1">
                            <input type="text" name="icon" value="{{ $cat->icon }}" class="input text-sm w-16 text-center">
                            <button type="submit" class="btn-primary text-xs py-1.5 px-3">Save</button>
                        </form>
                    </td>
                    <td class="px-4 py-2.5 text-gray-500">{{ $cat->jobs_count }}</td>
                    <td class="px-4 py-2.5">
                        <span class="{{ $cat->is_active ? 'badge-green' : 'badge-gray' }} badge text-xs">{{ $cat->is_active ? 'Active' : 'Inactive' }}</span>
                    </td>
                    <td class="px-4 py-2.5">
                        <div class="flex items-center justify-end gap-2">
                            <button @click="editing = !editing" class="text-xs text-brand-600 hover:underline">Edit</button>
                            <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" onsubmit="return confirm('Delete category?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-400 hover:text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-admin-layout>
