<x-app-layout>
<x-slot name="title">FAQ – ConnectHR</x-slot>
<div class="max-w-3xl mx-auto px-4 py-12">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Frequently Asked Questions</h1>
        <p class="text-gray-500">Everything you need to know about ConnectHR</p>
    </div>

    @foreach($faqs as $category => $items)
    <div class="mb-8">
        @if($category)
        <h2 class="text-sm font-bold uppercase tracking-wider text-brand-600 mb-4">{{ $category }}</h2>
        @endif
        <div class="space-y-3">
            @foreach($items as $faq)
            <div class="card" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between p-5 text-left">
                    <span class="font-semibold text-gray-800 text-sm">{{ $faq->question }}</span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 flex-shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="px-5 pb-5">
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $faq->answer }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="card p-6 text-center mt-8">
        <p class="text-gray-600 mb-3">Still have questions?</p>
        <a href="{{ route('contact') }}" class="btn-primary">Contact Us</a>
    </div>
</div>
</x-app-layout>
