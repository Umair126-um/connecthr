<x-admin-layout>
<x-slot name="title">{{ isset($blog->id) ? 'Edit Post' : 'New Post' }}</x-slot>

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.blogs.index') }}" class="text-sm text-gray-400 hover:text-brand-600">← Blog</a>
</div>

<form method="POST"
      action="{{ isset($blog->id) ? route('admin.blogs.update', $blog->id) : route('admin.blogs.store') }}"
      enctype="multipart/form-data"
      class="max-w-4xl space-y-5">
    @csrf
    @if(isset($blog->id)) @method('PATCH') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
                <div><label class="label">Post Title *</label><input type="text" name="title" value="{{ old('title', $blog->title ?? '') }}" class="input" required placeholder="Write a compelling title…"></div>
                <div><label class="label">Excerpt</label><textarea name="excerpt" rows="2" class="textarea" placeholder="Short description shown on listing pages…">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea></div>
                <div>
                    <label class="label">Content *</label>
                    <textarea name="content" rows="18" class="textarea font-mono text-sm" required>{{ old('content', $blog->content ?? '') }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Supports HTML markup</p>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-3">
                <h3 class="font-semibold text-gray-800 text-sm">Publish Settings</h3>
                <div><label class="label">Status</label>
                    <select name="status" class="select text-sm">
                        <option value="draft" {{ (old('status', $blog->status ?? 'draft') === 'draft') ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ (old('status', $blog->status ?? '') === 'published') ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div><label class="label">Thumbnail</label>
                    @if(isset($blog->thumbnail) && $blog->thumbnail)
                    <img src="{{ asset('storage/'.$blog->thumbnail) }}" class="w-full h-32 object-cover rounded-xl mb-2">
                    @endif
                    <input type="file" name="thumbnail" accept="image/*" class="input text-sm">
                </div>
                <div><label class="label">Tags (comma-separated)</label><input type="text" name="tags" value="{{ old('tags', $blog->tags ?? '') }}" class="input text-sm" placeholder="career, tips, resume"></div>
                <div class="pt-2">
                    <button type="submit" class="btn-primary w-full">{{ isset($blog->id) ? 'Update Post' : 'Publish Post' }}</button>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-3">
                <h3 class="font-semibold text-gray-800 text-sm">SEO</h3>
                <div><label class="label">Meta Title</label><input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title ?? '') }}" class="input text-sm"></div>
                <div><label class="label">Meta Description</label><textarea name="meta_description" rows="2" class="textarea text-sm">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea></div>
            </div>
        </div>
    </div>
</form>
</x-admin-layout>
