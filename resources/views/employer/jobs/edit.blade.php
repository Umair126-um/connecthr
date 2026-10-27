<x-app-layout>
<x-slot name="title">Edit Job</x-slot>
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('employer.jobs') }}" class="text-sm text-gray-400 hover:text-brand-600">← My Jobs</a>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Job</h1>

    <form action="{{ route('employer.jobs.update', $job->id) }}" method="POST" class="space-y-6">
        @csrf @method('PATCH')

        <div class="card p-6 space-y-4">
            <div><label class="label">Job Title *</label><input type="text" name="title" value="{{ old('title', $job->title) }}" class="input" required></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="label">Category</label>
                    <select name="category_id" class="select">
                        <option value="">None</option>
                        @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ $job->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
                    </select>
                </div>
                <div><label class="label">Location</label>
                    <select name="location_id" class="select">
                        <option value="">None</option>
                        @foreach($locations as $loc)<option value="{{ $loc->id }}" {{ $job->location_id == $loc->id ? 'selected' : '' }}>{{ $loc->city }}</option>@endforeach
                    </select>
                </div>
                <div><label class="label">Job Type</label>
                    <select name="job_type" class="select">
                        @foreach(['full_time'=>'Full Time','part_time'=>'Part Time','contract'=>'Contract','freelance'=>'Freelance','internship'=>'Internship','remote'=>'Remote'] as $v=>$l)
                        <option value="{{ $v }}" {{ $job->job_type === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="label">Experience Level</label>
                    <select name="experience_level" class="select">
                        @foreach(['entry'=>'Entry','junior'=>'Junior','mid'=>'Mid','senior'=>'Senior','executive'=>'Executive'] as $v=>$l)
                        <option value="{{ $v }}" {{ $job->experience_level === $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="label">Salary Min</label><input type="number" name="salary_min" value="{{ $job->salary_min }}" class="input"></div>
                <div><label class="label">Salary Max</label><input type="number" name="salary_max" value="{{ $job->salary_max }}" class="input"></div>
                <div><label class="label">Vacancies</label><input type="number" name="vacancies" value="{{ $job->vacancies }}" class="input" min="1"></div>
                <div><label class="label">Deadline</label><input type="date" name="deadline" value="{{ $job->deadline?->format('Y-m-d') }}" class="input"></div>
            </div>
            <div class="flex gap-5">
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700"><input type="checkbox" name="is_remote" value="1" {{ $job->is_remote ? 'checked' : '' }} class="accent-brand-600"> Remote</label>
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700"><input type="checkbox" name="is_urgent" value="1" {{ $job->is_urgent ? 'checked' : '' }} class="accent-brand-600"> Urgent</label>
                <label class="flex items-center gap-2 cursor-pointer text-sm text-gray-700"><input type="checkbox" name="hide_salary" value="1" {{ $job->hide_salary ? 'checked' : '' }} class="accent-brand-600"> Hide Salary</label>
            </div>
            <div><label class="label">Description *</label><textarea name="description" rows="7" class="textarea" required>{{ $job->description }}</textarea></div>
            <div><label class="label">Requirements</label><textarea name="requirements" rows="5" class="textarea">{{ $job->requirements }}</textarea></div>
            <div><label class="label">Responsibilities</label><textarea name="responsibilities" rows="5" class="textarea">{{ $job->responsibilities }}</textarea></div>
            <div><label class="label">Benefits</label><textarea name="benefits" rows="3" class="textarea">{{ $job->benefits }}</textarea></div>
        </div>

        <div class="card p-5">
            <h2 class="font-semibold text-gray-800 mb-3">Required Skills</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($skills as $skill)
                <label class="flex items-center gap-1.5 cursor-pointer text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5 has-[:checked]:bg-brand-50 has-[:checked]:border-brand-500 transition">
                    <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                           {{ $job->skills->contains($skill->id) ? 'checked' : '' }} class="accent-brand-600">
                    {{ $skill->name }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Update Job</button>
            <a href="{{ route('employer.jobs') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</x-app-layout>
