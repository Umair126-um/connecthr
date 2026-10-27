{{-- resources/views/employer/jobs/create.blade.php --}}
<x-app-layout>
<x-slot name="title">Post a Job</x-slot>
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Post a New Job</h1>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
        @foreach($errors->all() as $e)<p class="text-sm text-red-600">✕ {{ $e }}</p>@endforeach
    </div>
    @endif

    <form action="{{ route('employer.jobs.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="card p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Job Information</h2>
            <div><label class="label">Job Title *</label><input type="text" name="title" value="{{ old('title') }}" class="input" required placeholder="e.g. Senior PHP Developer"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="label">Category</label>
                    <select name="category_id" class="select">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                    </select>
                </div>
                <div><label class="label">Location</label>
                    <select name="location_id" class="select">
                        <option value="">Select Location</option>
                        @foreach($locations as $loc)<option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->city }}</option>@endforeach
                    </select>
                </div>
                <div><label class="label">Job Type *</label>
                    <select name="job_type" class="select" required>
                        @foreach(['full_time'=>'Full Time','part_time'=>'Part Time','contract'=>'Contract','freelance'=>'Freelance','internship'=>'Internship','remote'=>'Remote'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('job_type') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="label">Experience Level *</label>
                    <select name="experience_level" class="select" required>
                        @foreach(['entry'=>'Entry Level','junior'=>'Junior','mid'=>'Mid Level','senior'=>'Senior','executive'=>'Executive'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('experience_level') === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="label">Salary Min (PKR)</label><input type="number" name="salary_min" value="{{ old('salary_min') }}" class="input" placeholder="50000"></div>
                <div><label class="label">Salary Max (PKR)</label><input type="number" name="salary_max" value="{{ old('salary_max') }}" class="input" placeholder="100000"></div>
                <div><label class="label">Vacancies</label><input type="number" name="vacancies" value="{{ old('vacancies', 1) }}" class="input" min="1"></div>
                <div><label class="label">Application Deadline</label><input type="date" name="deadline" value="{{ old('deadline') }}" class="input" min="{{ date('Y-m-d') }}"></div>
            </div>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox" name="is_remote" value="1" {{ old('is_remote') ? 'checked' : '' }} class="accent-brand-600"> Remote
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox" name="is_urgent" value="1" {{ old('is_urgent') ? 'checked' : '' }} class="accent-brand-600"> Urgent Hiring
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                    <input type="checkbox" name="hide_salary" value="1" {{ old('hide_salary') ? 'checked' : '' }} class="accent-brand-600"> Hide Salary
                </label>
            </div>
        </div>

        <div class="card p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Job Details</h2>
            <div><label class="label">Job Description *</label><textarea name="description" rows="8" class="textarea" required placeholder="Describe the role, what you're building, the team...">{{ old('description') }}</textarea></div>
            <div><label class="label">Requirements</label><textarea name="requirements" rows="5" class="textarea" placeholder="Education, certifications, years of experience...">{{ old('requirements') }}</textarea></div>
            <div><label class="label">Responsibilities</label><textarea name="responsibilities" rows="5" class="textarea" placeholder="Day-to-day duties and tasks...">{{ old('responsibilities') }}</textarea></div>
            <div><label class="label">Benefits & Perks</label><textarea name="benefits" rows="3" class="textarea" placeholder="Medical, provident fund, leaves, bonuses...">{{ old('benefits') }}</textarea></div>
        </div>

        <div class="card p-6">
            <h2 class="font-semibold text-gray-800 mb-4">Required Skills</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($skills as $skill)
                <label class="flex items-center gap-1.5 cursor-pointer text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 hover:border-brand-400 transition has-[:checked]:bg-brand-50 has-[:checked]:border-brand-500">
                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}" {{ in_array($skill->id, old('skills', [])) ? 'checked' : '' }} class="accent-brand-600">
                    {{ $skill->name }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Submit for Review</button>
            <a href="{{ route('employer.jobs') }}" class="btn-outline">Cancel</a>
        </div>
        <p class="text-xs text-gray-400">Your job will be reviewed by our team and typically approved within a few hours.</p>
    </form>
</div>
</x-app-layout>
