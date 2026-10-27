{{-- resources/views/dashboard/profile/edit.blade.php --}}
<x-app-layout>
<x-slot name="title">Edit Profile</x-slot>
<div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
    <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-green-700 text-sm">
        ✓ {{ session('success') }}
    </div>
    @endif

    {{-- Avatar + CV --}}
    <div class="card p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Profile Picture & CV</h2>
        <div class="flex flex-col sm:flex-row gap-6">
            <div class="text-center">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                     class="w-24 h-24 rounded-full object-cover mx-auto mb-3">
                <form action="{{ route('seeker.profile.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PATCH')
                    <label class="btn-outline text-xs cursor-pointer">
                        Change Photo
                        <input type="file" name="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>
            </div>
            <div class="flex-1">
                <label class="label">CV / Resume</label>
                @if(auth()->user()->profile?->cv_file)
                <div class="flex items-center gap-3 mb-3">
                    <a href="{{ asset('storage/'.auth()->user()->profile->cv_file) }}" target="_blank"
                       class="text-sm text-brand-600 hover:underline flex items-center gap-1">
                        📄 View Current CV
                    </a>
                    <form action="{{ route('seeker.profile.cv.delete') }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 hover:text-red-700">✕ Delete</button>
                    </form>
                </div>
                @endif
                <form action="{{ route('seeker.profile.cv') }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                    @csrf @method('PATCH')
                    <input type="file" name="cv" accept=".pdf,.doc,.docx" class="input flex-1 text-sm">
                    <button type="submit" class="btn-primary text-sm">Upload</button>
                </form>
                <p class="text-xs text-gray-400 mt-1">PDF, DOC or DOCX · Max 5MB</p>
            </div>
        </div>
    </div>

    {{-- Basic Info --}}
    <div class="card p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Personal Information</h2>
        <form action="{{ route('seeker.profile.update') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @csrf @method('PATCH')
            <div><label class="label">Full Name *</label><input type="text" name="name" value="{{ auth()->user()->name }}" class="input" required></div>
            <div><label class="label">Professional Title</label><input type="text" name="title" value="{{ auth()->user()->profile?->title }}" class="input" placeholder="e.g. Senior Software Engineer"></div>
            <div><label class="label">Phone</label><input type="text" name="phone" value="{{ auth()->user()->phone }}" class="input" placeholder="+92..."></div>
            <div><label class="label">Date of Birth</label><input type="date" name="date_of_birth" value="{{ auth()->user()->profile?->date_of_birth?->format('Y-m-d') }}" class="input"></div>
            <div><label class="label">Gender</label>
                <select name="gender" class="select">
                    @foreach(['prefer_not_to_say'=>'Prefer not to say','male'=>'Male','female'=>'Female','other'=>'Other'] as $v=>$l)
                    <option value="{{ $v }}" {{ auth()->user()->profile?->gender === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="label">Nationality</label><input type="text" name="nationality" value="{{ auth()->user()->profile?->nationality }}" class="input"></div>
            <div><label class="label">City</label><input type="text" name="city" value="{{ auth()->user()->profile?->city }}" class="input"></div>
            <div><label class="label">Country</label><input type="text" name="country" value="{{ auth()->user()->profile?->country ?? 'Pakistan' }}" class="input"></div>
            <div class="sm:col-span-2"><label class="label">Bio / Summary</label><textarea name="bio" rows="4" class="textarea" placeholder="Write a brief summary about yourself...">{{ auth()->user()->profile?->bio }}</textarea></div>
            <div><label class="label">Expected Salary (PKR)</label><input type="text" name="expected_salary" value="{{ auth()->user()->profile?->expected_salary }}" class="input" placeholder="e.g. 80,000 - 120,000"></div>
            <div><label class="label">Availability</label>
                <select name="availability" class="select">
                    @foreach(['immediately'=>'Available Immediately','two_weeks'=>'2 Weeks Notice','one_month'=>'1 Month Notice','actively_looking'=>'Actively Looking','open_to_offers'=>'Open to Offers'] as $v=>$l)
                    <option value="{{ $v }}" {{ auth()->user()->profile?->availability === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="label">LinkedIn URL</label><input type="url" name="linkedin_url" value="{{ auth()->user()->profile?->linkedin_url }}" class="input" placeholder="https://linkedin.com/in/..."></div>
            <div><label class="label">Portfolio / Website</label><input type="url" name="portfolio_url" value="{{ auth()->user()->profile?->portfolio_url }}" class="input" placeholder="https://..."></div>
            <div class="sm:col-span-2 pt-2">
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>

    {{-- Skills --}}
    <div class="card p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Skills</h2>
        <form action="{{ route('seeker.profile.skills') }}" method="POST">
            @csrf @method('PUT')
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($skills as $skill)
                <label class="flex items-center gap-1.5 cursor-pointer text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 hover:border-brand-400 transition has-[:checked]:bg-brand-50 has-[:checked]:border-brand-500 has-[:checked]:text-brand-700">
                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                           {{ $user->skills->contains($skill->id) ? 'checked' : '' }} class="accent-brand-600">
                    {{ $skill->name }}
                </label>
                @endforeach
            </div>
            <button type="submit" class="btn-primary text-sm">Update Skills</button>
        </form>
    </div>

    {{-- Education --}}
    <div class="card p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Education</h2>
        @foreach($user->educations as $edu)
        <div class="border border-gray-100 rounded-xl p-4 mb-3">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="font-semibold text-gray-800 text-sm">{{ $edu->degree }} in {{ $edu->field_of_study }}</p>
                    <p class="text-sm text-gray-500">{{ $edu->institution }}</p>
                    <p class="text-xs text-gray-400">{{ $edu->start_year }} – {{ $edu->is_current ? 'Present' : $edu->end_year }}</p>
                </div>
                <form method="POST" action="{{ route('seeker.profile.education.delete', $edu->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-600 text-sm">✕</button>
                </form>
            </div>
        </div>
        @endforeach
        <details class="mt-4"><summary class="cursor-pointer text-brand-600 text-sm font-medium">+ Add Education</summary>
        <form action="{{ route('seeker.profile.education') }}" method="POST" class="grid grid-cols-2 gap-3 mt-3">
            @csrf
            <div><label class="label">Degree *</label><input type="text" name="degree" class="input" required placeholder="Bachelor's"></div>
            <div><label class="label">Field of Study</label><input type="text" name="field_of_study" class="input" placeholder="Computer Science"></div>
            <div class="col-span-2"><label class="label">Institution *</label><input type="text" name="institution" class="input" required placeholder="University name"></div>
            <div><label class="label">Start Year *</label><input type="number" name="start_year" class="input" required min="1950" max="{{ date('Y') + 4 }}"></div>
            <div><label class="label">End Year</label><input type="number" name="end_year" class="input" min="1950" max="{{ date('Y') + 4 }}"></div>
            <div class="col-span-2"><button type="submit" class="btn-primary text-sm">Add Education</button></div>
        </form></details>
    </div>

    {{-- Experience --}}
    <div class="card p-6">
        <h2 class="font-semibold text-gray-800 mb-4">Work Experience</h2>
        @foreach($user->experiences as $exp)
        <div class="border border-gray-100 rounded-xl p-4 mb-3">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="font-semibold text-gray-800 text-sm">{{ $exp->job_title }}</p>
                    <p class="text-sm text-gray-500">{{ $exp->company_name }}</p>
                    <p class="text-xs text-gray-400">{{ $exp->start_date?->format('M Y') }} – {{ $exp->is_current ? 'Present' : $exp->end_date?->format('M Y') }}</p>
                    @if($exp->description)<p class="text-xs text-gray-500 mt-1">{{ Str::limit($exp->description, 100) }}</p>@endif
                </div>
                <form method="POST" action="{{ route('seeker.profile.experience.delete', $exp->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-400 hover:text-red-600 text-sm">✕</button>
                </form>
            </div>
        </div>
        @endforeach
        <details class="mt-4"><summary class="cursor-pointer text-brand-600 text-sm font-medium">+ Add Experience</summary>
        <form action="{{ route('seeker.profile.experience') }}" method="POST" class="grid grid-cols-2 gap-3 mt-3">
            @csrf
            <div><label class="label">Job Title *</label><input type="text" name="job_title" class="input" required></div>
            <div><label class="label">Company *</label><input type="text" name="company_name" class="input" required></div>
            <div><label class="label">Start Date *</label><input type="date" name="start_date" class="input" required></div>
            <div><label class="label">End Date</label><input type="date" name="end_date" class="input"></div>
            <div class="col-span-2 flex items-center gap-2">
                <input type="checkbox" name="is_current" value="1" id="curr_exp" class="accent-brand-600">
                <label for="curr_exp" class="text-sm text-gray-600">Currently working here</label>
            </div>
            <div class="col-span-2"><label class="label">Description</label><textarea name="description" rows="3" class="textarea"></textarea></div>
            <div class="col-span-2"><button type="submit" class="btn-primary text-sm">Add Experience</button></div>
        </form></details>
    </div>
</div>
</x-app-layout>
