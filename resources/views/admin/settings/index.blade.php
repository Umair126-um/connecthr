<x-admin-layout>
<x-slot name="title">Site Settings</x-slot>

<div class="max-w-3xl">
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-5">✓ {{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PATCH')

        {{-- General --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">General Settings</h2>
            @foreach([
                ['key'=>'site_name','label'=>'Site Name','placeholder'=>'ConnectHR'],
                ['key'=>'site_tagline','label'=>'Site Tagline','placeholder'=>'Find Your Dream Job in Pakistan'],
                ['key'=>'site_email','label'=>'Admin Email','placeholder'=>'admin@connecthr.pk'],
                ['key'=>'site_phone','label'=>'Phone','placeholder'=>'+92 300 0000000'],
                ['key'=>'site_address','label'=>'Address','placeholder'=>'Lahore, Pakistan'],
            ] as $s)
            <div>
                <label class="label">{{ $s['label'] }}</label>
                <input type="{{ str_contains($s['key'],'email') ? 'email' : 'text' }}"
                       name="{{ $s['key'] }}"
                       value="{{ setting($s['key'], '') }}"
                       class="input"
                       placeholder="{{ $s['placeholder'] }}">
            </div>
            @endforeach
            <div><label class="label">Jobs Per Page</label>
                <select name="jobs_per_page" class="select w-32">
                    @foreach([10,12,15,20,25,30] as $n)
                    <option value="{{ $n }}" {{ setting('jobs_per_page',12) == $n ? 'selected' : '' }}>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Social --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Social Media</h2>
            @foreach([
                ['key'=>'facebook_url','label'=>'Facebook'],
                ['key'=>'linkedin_url','label'=>'LinkedIn'],
                ['key'=>'twitter_url','label'=>'Twitter / X'],
                ['key'=>'instagram_url','label'=>'Instagram'],
            ] as $s)
            <div>
                <label class="label">{{ $s['label'] }}</label>
                <input type="url" name="{{ $s['key'] }}" value="{{ setting($s['key'], '') }}" class="input" placeholder="https://...">
            </div>
            @endforeach
        </div>

        {{-- Features --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Feature Toggles</h2>
            @foreach([
                ['key'=>'google_auth_enabled','label'=>'Enable Google OAuth Sign-in'],
                ['key'=>'job_alerts_enabled','label'=>'Enable Job Alerts'],
                ['key'=>'registration_open','label'=>'Allow New Registrations'],
                ['key'=>'maintenance_mode','label'=>'Maintenance Mode'],
            ] as $toggle)
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="{{ $toggle['key'] }}" value="1"
                       {{ setting($toggle['key']) ? 'checked' : '' }} class="accent-brand-600 w-4 h-4">
                <span class="text-sm text-gray-700">{{ $toggle['label'] }}</span>
            </label>
            @endforeach
        </div>

        <button type="submit" class="btn-primary px-8">Save Settings</button>
    </form>
</div>
</x-admin-layout>
