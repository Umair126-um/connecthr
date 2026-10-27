<x-app-layout>
<x-slot name="title">{{ $page->title }} – ConnectHR</x-slot>
<x-slot name="meta_description">{{ $page->meta_description }}</x-slot>
<div class="max-w-4xl mx-auto px-4 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ $page->title }}</h1>
    <div class="prose max-w-none text-gray-700 leading-relaxed">
        {!! $page->content !!}
    </div>
</div>
</x-app-layout>
