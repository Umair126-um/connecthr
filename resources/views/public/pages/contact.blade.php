{{-- resources/views/public/pages/contact.blade.php --}}
<x-app-layout>
<x-slot name="title">Contact Us – ConnectHR</x-slot>
<div class="max-w-5xl mx-auto px-4 py-12">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Contact Us</h1>
        <p class="text-gray-500">We'd love to hear from you</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Contact form --}}
        <div class="md:col-span-2 card p-6">
            @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl mb-5 text-sm">✓ {{ session('success') }}</div>
            @endif
            <form action="{{ route('contact.send') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="label">Full Name *</label><input type="text" name="name" value="{{ old('name') }}" class="input" required></div>
                    <div><label class="label">Email *</label><input type="email" name="email" value="{{ old('email') }}" class="input" required></div>
                </div>
                <div><label class="label">Subject *</label><input type="text" name="subject" value="{{ old('subject') }}" class="input" required></div>
                <div><label class="label">Message *</label><textarea name="message" rows="6" class="textarea" required placeholder="Tell us how we can help...">{{ old('message') }}</textarea></div>
                @error('message')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
                <button type="submit" class="btn-primary">Send Message</button>
            </form>
        </div>

        {{-- Contact info --}}
        <div class="space-y-4">
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Get in Touch</h3>
                <div class="space-y-3">
                    @foreach([
                        ['icon'=>'📧','label'=>'Email','value'=>setting('site_email','info@connecthr.pk')],
                        ['icon'=>'📞','label'=>'Phone','value'=>setting('site_phone','+92 300 0000000')],
                        ['icon'=>'📍','label'=>'Address','value'=>setting('site_address','Lahore, Pakistan')],
                    ] as $item)
                    <div class="flex items-start gap-3">
                        <span class="text-xl flex-shrink-0 mt-0.5">{{ $item['icon'] }}</span>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">{{ $item['label'] }}</p>
                            <p class="text-sm text-gray-700">{{ $item['value'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-3 text-sm">Office Hours</h3>
                <p class="text-sm text-gray-500">Mon–Fri: 9am – 6pm PKT</p>
                <p class="text-sm text-gray-500">Sat: 10am – 2pm PKT</p>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
