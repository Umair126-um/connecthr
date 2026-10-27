<x-app-layout>
<x-slot name="title">{{ $job->title }} – {{ $job->company?->name }}</x-slot>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- MAIN CONTENT --}}
        <div class="flex-1 min-w-0">
            {{-- Header Card --}}
            <div class="card p-6 mb-6">
                <div class="flex items-start gap-5">
                    <img src="{{ $job->company?->logo_url }}" alt="{{ $job->company?->name }}"
                         class="w-20 h-20 rounded-2xl object-contain bg-gray-50 p-2 flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4 flex-wrap">
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $job->title }}</h1>
                                <a href="{{ route('companies.show', $job->company?->slug) }}"
                                   class="text-brand-600 hover:underline font-medium">
                                    {{ $job->company?->name }}
                                </a>
                            </div>
                            <div class="flex gap-2">
                                @if($job->is_urgent)  <span class="badge-urgent">Urgent</span>   @endif
                                @if($job->is_featured)<span class="badge-featured">Featured</span>@endif
                                @if($job->is_remote)  <span class="badge-blue">Remote</span>     @endif
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-3 mt-3 text-sm text-gray-600">
                            @if($job->location)
                            <span class="flex items-center gap-1">📍 {{ $job->location->city }}</span>
                            @endif
                            <span class="flex items-center gap-1">💼 {{ $job->job_type_label }}</span>
                            <span class="flex items-center gap-1">⏱ {{ $job->experience_years ?? ucfirst($job->experience_level) }}</span>
                            @if(!$job->hide_salary)
                            <span class="flex items-center gap-1 font-semibold text-brand-600">
                                💰 {{ $job->salary_range }}
                            </span>
                            @endif
                            <span class="flex items-center gap-1">📅 Posted {{ timeAgo($job->created_at) }}</span>
                            @if($job->deadline)
                            <span class="flex items-center gap-1">⏰ Deadline: {{ $job->deadline->format('M d, Y') }}</span>
                            @endif
                            <span class="flex items-center gap-1">👁 {{ number_format($job->views) }} views</span>
                        </div>

                        {{-- Skills --}}
                        @if($job->skills->count())
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach($job->skills as $skill)
                                <span class="badge-gray">{{ $skill->name }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Apply Buttons --}}
                <div class="flex gap-3 mt-6 pt-5 border-t border-gray-100">
                    @auth
                        @if(auth()->user()->isJobSeeker())
                            @if($hasApplied)
                                <span class="btn-secondary cursor-not-allowed opacity-75">✓ Applied</span>
                            @else
                                <button onclick="document.getElementById('applyModal').classList.remove('hidden')"
                                        class="btn-primary">
                                    Apply Now
                                </button>
                            @endif
                            <form action="{{ route('seeker.saved.toggle', $job->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-outline">
                                    {{ $hasSaved ? '❤️ Saved' : '🤍 Save Job' }}
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-primary">Login to Apply</a>
                        <a href="{{ route('register') }}" class="btn-outline">Create Account</a>
                    @endauth
                </div>
            </div>

            {{-- Description --}}
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Job Description</h2>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($job->description)) !!}
                </div>
            </div>

            @if($job->requirements)
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Requirements</h2>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($job->requirements)) !!}
                </div>
            </div>
            @endif

            @if($job->responsibilities)
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Responsibilities</h2>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($job->responsibilities)) !!}
                </div>
            </div>
            @endif

            @if($job->benefits)
            <div class="card p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Benefits</h2>
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! nl2br(e($job->benefits)) !!}
                </div>
            </div>
            @endif

            {{-- Similar Jobs --}}
            @if($similarJobs->count())
            <div class="card p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Similar Jobs</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($similarJobs as $sJob)
                        @include('partials.job-card', ['job' => $sJob])
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- SIDEBAR --}}
        <div class="lg:w-80 flex-shrink-0 space-y-4">

            {{-- Job Overview --}}
            <div class="card p-5">
                <h3 class="font-bold text-gray-900 mb-4">Job Overview</h3>
                <div class="space-y-3">
                    @foreach([
                        ['icon' => '📅', 'label' => 'Posted', 'value' => $job->created_at->format('M d, Y')],
                        ['icon' => '📂', 'label' => 'Category', 'value' => $job->category?->name ?? 'N/A'],
                        ['icon' => '💼', 'label' => 'Job Type', 'value' => $job->job_type_label],
                        ['icon' => '📍', 'label' => 'Location', 'value' => $job->location?->city ?? 'N/A'],
                        ['icon' => '🎓', 'label' => 'Education', 'value' => $job->education_level ?? 'Not specified'],
                        ['icon' => '⏱', 'label' => 'Experience', 'value' => $job->experience_years ?? ucfirst($job->experience_level)],
                        ['icon' => '👥', 'label' => 'Vacancies', 'value' => $job->vacancies],
                        ['icon' => '⚧', 'label' => 'Gender', 'value' => ucfirst($job->gender_preference)],
                    ] as $row)
                    <div class="flex items-center gap-3">
                        <span class="text-lg flex-shrink-0">{{ $row['icon'] }}</span>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide">{{ $row['label'] }}</p>
                            <p class="text-sm font-medium text-gray-800">{{ $row['value'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Company Card --}}
            @if($job->company)
            <div class="card p-5">
                <h3 class="font-bold text-gray-900 mb-4">About the Company</h3>
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $job->company->logo_url }}" alt="{{ $job->company->name }}"
                         class="w-12 h-12 rounded-xl object-contain bg-gray-50 p-1">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $job->company->name }}</p>
                        @if($job->company->is_verified)<span class="badge-green text-xs">✓ Verified</span>@endif
                    </div>
                </div>
                @if($job->company->description)
                <p class="text-sm text-gray-600 line-clamp-3">{{ $job->company->description }}</p>
                @endif
                <a href="{{ route('companies.show', $job->company->slug) }}"
                   class="btn-outline text-xs mt-3 w-full text-center">View Company</a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- APPLY MODAL --}}
@auth @if(auth()->user()->isJobSeeker())
<div id="applyModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg">
        <div class="flex items-center justify-between p-5 border-b border-gray-200">
            <h3 class="font-bold text-gray-900">Apply for {{ $job->title }}</h3>
            <button onclick="document.getElementById('applyModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <form action="{{ route('seeker.apply', $job->id) }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="label">Cover Letter <span class="text-gray-400 font-normal">(Optional)</span></label>
                <textarea name="cover_letter" rows="5" class="textarea"
                    placeholder="Tell the employer why you're a great fit for this role..."></textarea>
            </div>
            <div>
                <label class="label">
                    Upload CV
                    @if(auth()->user()->profile?->cv_file)
                        <span class="text-brand-500 font-normal ml-1">(or use your profile CV)</span>
                    @endif
                </label>
                <input type="file" name="cv" accept=".pdf,.doc,.docx" class="input text-sm">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary flex-1">Submit Application</button>
                <button type="button" onclick="document.getElementById('applyModal').classList.add('hidden')" class="btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endif @endauth
</x-app-layout>
