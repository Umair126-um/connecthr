{{-- resources/views/public/blog/index.blade.php --}}
<x-app-layout>
<x-slot name="title">Blog – Career Resources – ConnectHR</x-slot>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Career Resources</h1>
        <p class="text-gray-500">Tips, guides and industry insights</p>
    </div>
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="flex-1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($blogs as $blog)
                <a href="{{ route('blog.show', $blog->slug) }}" class="card card-hover group overflow-hidden">
                    @if($blog->thumbnail)
                        <img src="{{ asset('storage/'.$blog->thumbnail) }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-4xl">📝</div>
                    @endif
                    <div class="p-5">
                        <p class="text-xs text-gray-400 mb-2">{{ $blog->published_at?->format('M d, Y') }}</p>
                        <h3 class="font-bold text-gray-800 group-hover:text-brand-600 transition mb-2 line-clamp-2">{{ $blog->title }}</h3>
                        <p class="text-sm text-gray-500 line-clamp-2">{{ $blog->excerpt }}</p>
                        <div class="flex items-center gap-2 mt-3 text-xs text-gray-400">
                            <img src="{{ $blog->user?->avatar_url }}" class="w-5 h-5 rounded-full">
                            {{ $blog->user?->name }}
                            <span>·</span>
                            <span>{{ $blog->views }} views</span>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-2 text-center py-12">
                    <p class="text-gray-400">No posts yet.</p>
                </div>
                @endforelse
            </div>
            <div class="mt-8">{{ $blogs->links() }}</div>
        </div>

        {{-- Sidebar --}}
        <aside class="lg:w-72">
            <div class="card p-5 sticky top-20">
                <h3 class="font-semibold text-gray-800 mb-4">Popular Posts</h3>
                <div class="space-y-4">
                    @foreach($popular as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="flex gap-3 group">
                        <div class="text-2xl font-bold text-gray-200 font-display">{{ $loop->iteration }}</div>
                        <p class="text-sm text-gray-700 group-hover:text-brand-600 transition line-clamp-2">{{ $post->title }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
</div>
</x-app-layout>
