<x-admin-layout>
<x-slot name="title">CMS Pages</x-slot>

<div class="flex justify-between items-center mb-5">
    <p class="text-sm text-gray-500">Manage static pages like About, Privacy Policy, Terms & Conditions</p>
    <button onclick="document.getElementById('addPageModal').classList.remove('hidden')" class="btn-primary text-sm">+ New Page</button>
</div>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">Title</th>
                <th class="px-4 py-3 text-left">Slug</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Updated</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pages as $page)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $page->title }}</td>
                <td class="px-4 py-3 text-xs text-gray-400 font-mono">{{ $page->slug }}</td>
                <td class="px-4 py-3">
                    <span class="{{ $page->is_active ? 'badge-green' : 'badge-gray' }} badge text-xs">{{ $page->is_active ? 'Published' : 'Draft' }}</span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $page->updated_at->format('M d, Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="text-xs text-brand-600 hover:underline">Edit</a>
                        <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="text-xs text-gray-400 hover:text-gray-600">Preview</a>
                        <form method="POST" action="{{ route('admin.pages.destroy', $page->id) }}" onsubmit="return confirm('Delete page?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No pages yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Add modal --}}
<div id="addPageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="font-bold text-gray-900 mb-4">New Page</h3>
        <form method="POST" action="{{ route('admin.pages.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Title *</label><input type="text" name="title" class="input" required></div>
            <div><label class="label">Slug *</label><input type="text" name="slug" class="input font-mono" required placeholder="about-us"></div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="page_active" checked class="accent-brand-600">
                <label for="page_active" class="text-sm text-gray-600">Published</label>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="submit" class="btn-primary flex-1">Create Page</button>
                <button type="button" onclick="document.getElementById('addPageModal').classList.add('hidden')" class="btn-secondary flex-1">Cancel</button>
            </div>
        </form>
    </div>
</div>
</x-admin-layout>
