<x-admin-layout>
<x-slot name="title">Blog</x-slot>

<div class="flex justify-between items-center mb-5">
    <p class="text-sm text-gray-400">{{ $blogs->total() }} posts</p>
    <a href="{{ route('admin.blogs.create') }}" class="btn-primary text-sm">+ New Post</a>
</div>

<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">Post</th>
                <th class="px-4 py-3 text-left">Author</th>
                <th class="px-4 py-3 text-left">Status</th>
                <th class="px-4 py-3 text-left">Views</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($blogs as $blog)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 max-w-xs">
                    <div class="flex items-center gap-3">
                        @if($blog->thumbnail)
                        <img src="{{ asset('storage/'.$blog->thumbnail) }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                        @else
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white flex-shrink-0">📝</div>
                        @endif
                        <div class="min-w-0">
                            <p class="font-medium text-gray-800 truncate">{{ $blog->title }}</p>
                            <p class="text-xs text-gray-400">{{ $blog->published_at?->format('M d, Y') ?? 'Draft' }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $blog->user?->name }}</td>
                <td class="px-4 py-3">
                    <span class="{{ $blog->status === 'published' ? 'badge-green' : 'badge-gray' }} badge text-xs">
                        {{ ucfirst($blog->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ number_format($blog->views) }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="text-xs text-brand-600 hover:underline">Edit</a>
                        <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="text-xs text-gray-400 hover:text-gray-600">View</a>
                        <form method="POST" action="{{ route('admin.blogs.destroy', $blog->id) }}" onsubmit="return confirm('Delete post?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">No blog posts yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $blogs->links() }}</div>
</x-admin-layout>
