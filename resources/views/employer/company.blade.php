<x-app-layout>
<x-slot name="title">Company Profile</x-slot>
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Company Profile</h1>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-5">✓ {{ session('success') }}</div>
    @endif

    {{-- Logo upload --}}
    <div class="card p-6 mb-6">
        <h2 class="font-semibold text-gray-800 mb-4">Company Logo</h2>
        <div class="flex items-center gap-5">
            <img src="{{ $company?->logo_url ?? asset('images/placeholder-company.png') }}" class="w-20 h-20 rounded-2xl object-contain bg-gray-50 p-2">
            <form action="{{ route('employer.company.logo') }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                @csrf @method('PATCH')
                <label class="btn-outline text-sm cursor-pointer">
                    Upload Logo
                    <input type="file" name="logo" accept="image/*" class="hidden" onchange="this.form.submit()">
                </label>
            </form>
        </div>
        <p class="text-xs text-gray-400 mt-2">PNG, JPG · Recommended 200x200px · Max 2MB</p>
    </div>

    {{-- Company details form --}}
    <form action="{{ route('employer.company.update') }}" method="POST" class="space-y-5">
        @csrf

        <div class="card p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Basic Information</h2>
            <div><label class="label">Company Name *</label><input type="text" name="name" value="{{ old('name', $company?->name) }}" class="input" required></div>
            <div><label class="label">Tagline</label><input type="text" name="tagline" value="{{ old('tagline', $company?->tagline) }}" class="input" placeholder="What you do in one sentence"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="label">Industry</label><input type="text" name="industry" value="{{ old('industry', $company?->industry) }}" class="input" placeholder="Technology, Finance…"></div>
                <div><label class="label">Company Size</label>
                    <select name="company_size" class="select">
                        @foreach(['1-10'=>'1-10 employees','11-50'=>'11-50','51-200'=>'51-200','201-500'=>'201-500','501-1000'=>'501-1000','1000+'=>'1000+'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('company_size', $company?->company_size) === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="label">Founded Year</label><input type="number" name="founded_year" value="{{ old('founded_year', $company?->founded_year) }}" class="input" placeholder="2010"></div>
                <div><label class="label">Website</label><input type="url" name="website" value="{{ old('website', $company?->website) }}" class="input" placeholder="https://…"></div>
                <div><label class="label">Contact Email</label><input type="email" name="email" value="{{ old('email', $company?->email) }}" class="input"></div>
                <div><label class="label">Contact Phone</label><input type="text" name="phone" value="{{ old('phone', $company?->phone) }}" class="input"></div>
                <div><label class="label">City</label><input type="text" name="city" value="{{ old('city', $company?->city) }}" class="input"></div>
                <div><label class="label">Country</label><input type="text" name="country" value="{{ old('country', $company?->country ?? 'Pakistan') }}" class="input"></div>
            </div>
            <div><label class="label">About the Company</label><textarea name="description" rows="5" class="textarea" placeholder="Tell job seekers what makes your company a great place to work…">{{ old('description', $company?->description) }}</textarea></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="label">LinkedIn</label><input type="url" name="linkedin_url" value="{{ old('linkedin_url', $company?->linkedin_url) }}" class="input" placeholder="https://linkedin.com/company/…"></div>
                <div><label class="label">Twitter/X</label><input type="url" name="twitter_url" value="{{ old('twitter_url', $company?->twitter_url) }}" class="input" placeholder="https://twitter.com/…"></div>
            </div>
        </div>

        <button type="submit" class="btn-primary px-8">Save Company Profile</button>
        @if(!$company?->is_verified)
        <p class="text-xs text-yellow-600 mt-2">⚠ Your company is pending verification. Jobs will be visible after our team verifies your account.</p>
        @endif
    </form>
</div>
</x-app-layout>
