<x-admin-layout>
<x-slot name="title">Edit Page: {{ $page->title }}</x-slot>

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.pages.index') }}" class="text-sm text-gray-400 hover:text-brand-600">← CMS Pages</a>
</div>

<form method="POST" action="{{ route('admin.pages.update', $page->id) }}" class="max-w-4xl space-y-5">
    @csrf @method('PATCH')
    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div><label class="label">Page Title *</label><input type="text" name="title" value="{{ $page->title }}" class="input" required></div>
            <div><label class="label">Slug</label><input type="text" name="slug" value="{{ $page->slug }}" class="input font-mono"></div>
        </div>
        <div><label class="label">Meta Title</label><input type="text" name="meta_title" value="{{ $page->meta_title }}" class="input"></div>
        <div><label class="label">Meta Description</label><textarea name="meta_description" rows="2" class="textarea">{{ $page->meta_description }}</textarea></div>
        <div>
            <label class="label">Content *</label>
            <textarea name="content" rows="20" class="textarea font-mono text-xs" id="pageContent">{{ $page->content }}</textarea>
            <p class="text-xs text-gray-400 mt-1">Supports HTML markup</p>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" id="pg_active" {{ $page->is_active ? 'checked' : '' }} class="accent-brand-600">
            <label for="pg_active" class="text-sm text-gray-600">Published (visible to public)</label>
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Save Page</button>
        <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn-outline">Preview ↗</a>
        <a href="{{ route('admin.pages.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>
</x-admin-layout>
