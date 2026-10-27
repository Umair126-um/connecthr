<x-app-layout>
<x-slot name="title">Applicant: {{ $application->user?->name }}</x-slot>
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <a href="{{ route('employer.applicants') }}" class="text-sm text-gray-400 hover:text-brand-600">← Applicants</a>
        <div class="ml-auto flex gap-2">
            @if($application->cv_file)
            <a href="{{ route('employer.applicants.cv', $application->id) }}" class="btn-outline text-sm">📄 Download CV</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left sidebar --}}
        <div class="space-y-4">
            <div class="card p-5 text-center">
                <img src="{{ $application->user?->avatar_url }}" class="w-20 h-20 rounded-full mx-auto object-cover mb-3">
                <h2 class="font-bold text-gray-900 text-lg">{{ $application->user?->name }}</h2>
                @if($application->user?->profile?->title)
                <p class="text-sm text-gray-500 mt-0.5">{{ $application->user->profile->title }}</p>
                @endif
                @if($application->user?->profile?->city)
                <p class="text-xs text-gray-400 mt-1">📍 {{ $application->user->profile->city }}</p>
                @endif

                {{-- Status update --}}
                <div class="mt-4">
                    <form action="{{ route('employer.applicants.status', $application->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" class="select text-sm mb-2" onchange="this.form.submit()">
                            @foreach(['applied'=>'Applied','viewed'=>'Viewed','shortlisted'=>'Shortlisted','interview'=>'Invite to Interview','hired'=>'Hire','rejected'=>'Reject'] as $v=>$l)
                            <option value="{{ $v }}" {{ $application->status === $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </form>
                    <div class="mt-2"><span class="status-{{ $application->status }} badge">{{ ucfirst($application->status) }}</span></div>
                </div>

                {{-- Links --}}
                <div class="flex flex-col gap-2 mt-4">
                    @if($application->user?->profile?->linkedin_url)
                    <a href="{{ $application->user->profile->linkedin_url }}" target="_blank" class="btn-outline text-xs">🔗 LinkedIn</a>
                    @endif
                    @if($application->user?->profile?->portfolio_url)
                    <a href="{{ $application->user->profile->portfolio_url }}" target="_blank" class="btn-outline text-xs">🌐 Portfolio</a>
                    @endif
                </div>
            </div>

            {{-- Skills --}}
            @if($application->user?->skills?->count())
            <div class="card p-4">
                <h3 class="font-semibold text-gray-800 text-sm mb-3">Skills</h3>
                <div class="flex flex-wrap gap-1.5">
                    @foreach($application->user->skills as $skill)
                    <span class="badge-gray badge text-xs">{{ $skill->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Main --}}
        <div class="lg:col-span-2 space-y-5">
            {{-- Job they applied for --}}
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-1 text-sm uppercase tracking-wide text-gray-400">Applied for</h3>
                <p class="font-bold text-gray-900">{{ $application->job?->title }}</p>
                <p class="text-sm text-gray-400">{{ $application->created_at->format('M d, Y') }}</p>
            </div>

            {{-- Cover letter --}}
            @if($application->cover_letter)
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-3">Cover Letter</h3>
                <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $application->cover_letter }}</p>
            </div>
            @endif

            {{-- Experience --}}
            @if($application->user?->experiences?->count())
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Work Experience</h3>
                <div class="space-y-4">
                    @foreach($application->user->experiences as $exp)
                    <div class="border-l-2 border-brand-200 pl-4">
                        <p class="font-semibold text-gray-800 text-sm">{{ $exp->job_title }}</p>
                        <p class="text-sm text-gray-500">{{ $exp->company_name }}</p>
                        <p class="text-xs text-gray-400">{{ $exp->start_date?->format('M Y') }} – {{ $exp->is_current ? 'Present' : $exp->end_date?->format('M Y') }}</p>
                        @if($exp->description)<p class="text-xs text-gray-500 mt-1">{{ $exp->description }}</p>@endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Education --}}
            @if($application->user?->educations?->count())
            <div class="card p-5">
                <h3 class="font-semibold text-gray-800 mb-4">Education</h3>
                <div class="space-y-3">
                    @foreach($application->user->educations as $edu)
                    <div class="border-l-2 border-brand-200 pl-4">
                        <p class="font-semibold text-gray-800 text-sm">{{ $edu->degree }} @if($edu->field_of_study)in {{ $edu->field_of_study }}@endif</p>
                        <p class="text-sm text-gray-500">{{ $edu->institution }}</p>
                        <p class="text-xs text-gray-400">{{ $edu->start_year }} – {{ $edu->is_current ? 'Present' : $edu->end_year }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
</x-app-layout>
