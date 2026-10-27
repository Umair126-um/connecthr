<!DOCTYPE html>
<html lang="en" class="{{ session('theme','light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register as Employer – ConnectHR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-gray-50 min-h-screen flex items-center justify-center p-4">
<div class="w-full max-w-lg">

    <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
            <div class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
            </div>
            <span class="text-2xl font-bold font-display text-gray-900">Connect<span class="text-brand-600">HR</span></span>
        </a>
        <p class="text-gray-500 text-sm mt-2">Post jobs and hire top talent in Pakistan</p>
    </div>

    {{-- Role toggle --}}
    <div class="flex bg-white border border-gray-200 rounded-xl p-1 mb-6">
        <a href="{{ route('register') }}"
           class="flex-1 text-center py-2.5 rounded-lg text-sm font-medium text-gray-500 hover:text-gray-700 transition">
            👤 Job Seeker
        </a>
        <a href="{{ route('register.employer') }}"
           class="flex-1 text-center py-2.5 rounded-lg text-sm font-semibold bg-brand-600 text-white">
            🏢 Employer
        </a>
    </div>

    <div class="card p-8">
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
            @foreach($errors->all() as $e)<p class="text-sm text-red-600">✕ {{ $e }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('register.employer') }}" class="space-y-5">
            @csrf

            <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wide border-b border-gray-100 pb-2">Your Account</h3>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Your Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="input" required>
                </div>
                <div>
                    <label class="label">Work Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input" required>
                </div>
                <div>
                    <label class="label">Password *</label>
                    <input type="password" name="password" class="input" required>
                </div>
                <div>
                    <label class="label">Confirm Password *</label>
                    <input type="password" name="password_confirmation" class="input" required>
                </div>
            </div>

            <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wide border-b border-gray-100 pb-2 pt-2">Company Details</h3>

            <div>
                <label class="label">Company Name *</label>
                <input type="text" name="company_name" value="{{ old('company_name') }}" class="input" required placeholder="Acme Corp Pvt Ltd">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Industry</label>
                    <input type="text" name="industry" value="{{ old('industry') }}" class="input" placeholder="Technology, Finance...">
                </div>
                <div>
                    <label class="label">Company Size</label>
                    <select name="company_size" class="select">
                        @foreach(['1-10'=>'1-10 employees','11-50'=>'11-50','51-200'=>'51-200','201-500'=>'201-500','501-1000'=>'501-1000','1000+'=>'1000+'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('company_size') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Website</label>
                    <input type="url" name="website" value="{{ old('website') }}" class="input" placeholder="https://...">
                </div>
                <div>
                    <label class="label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="input" placeholder="+92...">
                </div>
            </div>
            <div>
                <label class="label">City / Location</label>
                <input type="text" name="city" value="{{ old('city') }}" class="input" placeholder="Lahore, Karachi...">
            </div>

            <div class="flex items-start gap-2">
                <input type="checkbox" name="terms" id="terms" class="accent-brand-600 mt-0.5" required>
                <label for="terms" class="text-sm text-gray-600">
                    I agree to the <a href="{{ route('page.show','terms-conditions') }}" class="text-brand-600 hover:underline">Terms</a> and <a href="{{ route('page.show','privacy-policy') }}" class="text-brand-600 hover:underline">Privacy Policy</a>
                </label>
            </div>

            <button type="submit" class="btn-primary w-full py-3 text-base">Create Employer Account</button>
        </form>
    </div>

    <p class="text-center text-sm text-gray-500 mt-6">
        Already have an account? <a href="{{ route('login') }}" class="text-brand-600 font-medium hover:underline">Sign in</a>
    </p>
</div>
</body>
</html>
