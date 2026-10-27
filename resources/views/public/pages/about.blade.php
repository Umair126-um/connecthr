<x-app-layout>
<x-slot name="title">About Us – ConnectHR</x-slot>
<div class="max-w-4xl mx-auto px-4 py-12">
    {{-- Hero --}}
    <div class="text-center mb-16">
        <h1 class="text-4xl font-bold font-display text-gray-900 mb-4">Pakistan's Most Trusted Job Portal</h1>
        <p class="text-gray-500 text-lg max-w-2xl mx-auto">ConnectHR bridges the gap between Pakistan's best employers and top talent — built for the way people hire and get hired today.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
        @foreach([
            ['value'=>'10,000+','label'=>'Active Jobs'],
            ['value'=>'5,000+','label'=>'Companies'],
            ['value'=>'200,000+','label'=>'Job Seekers'],
            ['value'=>'25,000+','label'=>'Successful Placements'],
        ] as $s)
        <div class="text-center">
            <div class="text-3xl font-bold font-display text-brand-600">{{ $s['value'] }}</div>
            <div class="text-sm text-gray-500 mt-1">{{ $s['label'] }}</div>
        </div>
        @endforeach
    </div>

    @if($page?->content)
    <div class="prose max-w-none text-gray-700 leading-relaxed mb-16">
        {!! $page->content !!}
    </div>
    @else
    {{-- Default content --}}
    <div class="space-y-8 mb-16">
        @foreach([
            ['icon'=>'🎯','title'=>'Our Mission','body'=>'To make employment accessible to every Pakistani by creating a seamless, trustworthy platform where talent meets opportunity — regardless of geography or background.'],
            ['icon'=>'👁','title'=>'Our Vision','body'=>'A Pakistan where no qualified professional goes unemployed and no employer struggles to find the right person. A marketplace built on merit, transparency, and speed.'],
            ['icon'=>'💎','title'=>'Why ConnectHR?','body'=>'We are built specifically for Pakistan — understanding the nuances of local industries, salary expectations, and hiring practices. No generic global template. Real local insight.'],
        ] as $item)
        <div class="flex gap-5">
            <div class="text-4xl flex-shrink-0">{{ $item['icon'] }}</div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ $item['title'] }}</h2>
                <p class="text-gray-600 leading-relaxed">{{ $item['body'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- CTA --}}
    <div class="bg-brand-600 rounded-2xl p-8 text-center text-white">
        <h2 class="text-2xl font-bold font-display mb-2">Ready to get started?</h2>
        <p class="text-brand-100 mb-6">Join thousands of professionals who found their next opportunity on ConnectHR</p>
        <div class="flex gap-3 justify-center">
            <a href="{{ route('jobs.index') }}" class="bg-white text-brand-700 font-semibold px-6 py-3 rounded-xl hover:bg-brand-50 transition text-sm">Browse Jobs</a>
            <a href="{{ route('register.employer') }}" class="bg-white/10 border border-white/30 text-white font-semibold px-6 py-3 rounded-xl hover:bg-white/20 transition text-sm">Post a Job</a>
        </div>
    </div>
</div>
</x-app-layout>
