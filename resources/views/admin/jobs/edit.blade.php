<x-admin-layout>
<x-slot name="title">Edit Job</x-slot>

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.jobs.show', $job->id) }}" class="text-sm text-gray-400 hover:text-brand-600">← Back to Job</a>
</div>

<form method="POST" action="{{ route('admin.jobs.update', $job->id) }}" class="max-w-3xl space-y-5">
    @csrf @method('PATCH')
    <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
        <h2 class="font-semibold text-gray-800">Job Information</h2>
        <div><label class="label">Title</label><input type="text" name="title" value="{{ old('title', $job->title) }}" class="input" required></div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="label">Category</label>
                <select name="category_id" class="select">
                    <option value="">None</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $job->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="label">Location</label>
                <select name="location_id" class="select">
                    <option value="">None</option>
                    @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ $job->location_id == $loc->id ? 'selected' : '' }}>{{ $loc->city }}</option>
                    @endforeach
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
            <div><label class="label">Status</label>
                <select name="status" class="select">
                    @foreach(['pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected','closed'=>'Closed'] as $v=>$l)
                    <option value="{{ $v }}" {{ $job->status === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div><label class="label">Vacancies</label><input type="number" name="vacancies" value="{{ $job->vacancies }}" class="input" min="1"></div>
            <div><label class="label">Salary Min</label><input type="number" name="salary_min" value="{{ $job->salary_min }}" class="input"></div>
            <div><label class="label">Salary Max</label><input type="number" name="salary_max" value="{{ $job->salary_max }}" class="input"></div>
        </div>
        <div class="flex gap-5">
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="is_featured" value="1" {{ $job->is_featured ? 'checked' : '' }} class="accent-brand-600"> Featured
            </label>
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="is_urgent" value="1" {{ $job->is_urgent ? 'checked' : '' }} class="accent-brand-600"> Urgent
            </label>
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="is_remote" value="1" {{ $job->is_remote ? 'checked' : '' }} class="accent-brand-600"> Remote
            </label>
        </div>
        <div><label class="label">Description</label><textarea name="description" rows="7" class="textarea">{{ $job->description }}</textarea></div>
        <div><label class="label">Requirements</label><textarea name="requirements" rows="5" class="textarea">{{ $job->requirements }}</textarea></div>
        <div><label class="label">Benefits</label><textarea name="benefits" rows="3" class="textarea">{{ $job->benefits }}</textarea></div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Save Changes</button>
        <a href="{{ route('admin.jobs.show', $job->id) }}" class="btn-secondary">Cancel</a>
    </div>
</form>
</x-admin-layout>
