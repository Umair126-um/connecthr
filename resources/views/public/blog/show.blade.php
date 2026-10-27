<x-app-layout>
<x-slot name="title">{{ $blog->title }} – ConnectHR Blog</x-slot>
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('blog.index') }}" class="hover:text-brand-600">Blog</a>
        <span>›</span>
        <span class="truncate text-gray-600">{{ Str::limit($blog->title, 40) }}</span>
    </div>

    <article class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            {{-- Header --}}
            <div class="mb-6">
                @if($blog->thumbnail)
                <img src="{{ asset('storage/'.$blog->thumbnail) }}" alt="{{ $blog->title }}"
                     class="w-full rounded-2xl object-cover max-h-72 mb-6">
                @endif
                <h1 class="text-3xl font-bold font-display text-gray-900 leading-tight mb-4">{{ $blog->title }}</h1>
                <div class="flex items-center gap-3 text-sm text-gray-400">
                    <img src="{{ $blog->user?->avatar_url }}" class="w-8 h-8 rounded-full object-cover">
                    <span>{{ $blog->user?->name }}</span>
                    <span>·</span>
                    <span>{{ $blog->published_at?->format('M d, Y') }}</span>
                    <span>·</span>
                    <span>{{ $blog->views }} views</span>
                </div>
            </div>

            {{-- Content --}}
            <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                {!! $blog->content !!}
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-5">
            <div class="card p-5 sticky top-20">
                <h3 class="font-semibold text-gray-800 mb-4 text-sm">Related Posts</h3>
                <div class="space-y-4">
                    @foreach($related as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="flex gap-3 group">
                        @if($post->thumbnail)
                        <img src="{{ asset('storage/'.$post->thumbnail) }}" class="w-14 h-14 rounded-lg object-cover flex-shrink-0">
                        @else
                        <div class="w-14 h-14 rounded-lg bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center text-white flex-shrink-0">📝</div>
                        @endif
                        <p class="text-sm text-gray-700 group-hover:text-brand-600 transition line-clamp-2">{{ $post->title }}</p>
                    </a>
                    @endforeach
                </div>

                <div class="mt-5 pt-5 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 mb-3">READY TO FIND YOUR NEXT JOB?</p>
                    <a href="{{ route('jobs.index') }}" class="btn-primary w-full text-sm">Browse Jobs</a>
                </div>
            </div>
        </aside>
    </article>
</div>
</x-app-layout>
