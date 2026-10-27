<x-app-layout>
<x-slot name="title">Application Details</x-slot>
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('seeker.applications') }}" class="text-sm text-gray-400 hover:text-brand-600">← My Applications</a>
    </div>

    <div class="card p-6 mb-5">
        <div class="flex items-start gap-4 mb-5">
            <img src="{{ $application->job?->company?->logo_url }}" class="w-14 h-14 rounded-2xl object-contain bg-gray-50 p-1">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $application->job?->title }}</h1>
                <p class="text-brand-600">{{ $application->job?->company?->name }}</p>
                <div class="flex items-center gap-3 mt-2 flex-wrap text-xs text-gray-400">
                    <span>📍 {{ $application->job?->location?->city }}</span>
                    <span>💼 {{ $application->job?->job_type_label }}</span>
                    <span>Applied {{ $application->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Status timeline --}}
        <div class="bg-gray-50 rounded-xl p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-3">Application Status</p>
            <div class="flex items-center gap-1 flex-wrap">
                @foreach(['applied', 'viewed', 'shortlisted', 'interview', 'hired'] as $step)
                @php $active = $application->status === $step; $passed = in_array($application->status, array_slice(['applied','viewed','shortlisted','interview','hired'], array_search($step, ['applied','viewed','shortlisted','interview','hired']) + 1)); @endphp
                <div class="flex items-center gap-1">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $active ? 'bg-brand-600 text-white ring-2 ring-brand-200' : ($passed ? 'bg-brand-100 text-brand-600' : 'bg-gray-200 text-gray-400') }}">
                            {{ $passed ? '✓' : ($loop->iteration) }}
                        </div>
                        <p class="text-xs text-gray-400 mt-1 whitespace-nowrap">{{ ucfirst($step) }}</p>
                    </div>
                    @if(!$loop->last)<div class="w-6 h-0.5 mt-3 {{ $passed ? 'bg-brand-300' : 'bg-gray-200' }}"></div>@endif
                </div>
                @endforeach
                @if($application->status === 'rejected')
                <div class="ml-2"><span class="badge-red badge">Rejected</span></div>
                @endif
            </div>
        </div>
    </div>

    @if($application->cover_letter)
    <div class="card p-5 mb-4">
        <h3 class="font-semibold text-gray-800 mb-3">Your Cover Letter</h3>
        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $application->cover_letter }}</p>
    </div>
    @endif

    @if($application->cv_file)
    <div class="card p-5">
        <h3 class="font-semibold text-gray-800 mb-3">CV Submitted</h3>
        <a href="{{ asset('storage/'.$application->cv_file) }}" target="_blank" class="btn-outline text-sm">📄 View CV</a>
    </div>
    @endif

    @if($application->status === 'applied')
    <div class="mt-5">
        <form method="POST" action="{{ route('seeker.applications.withdraw', $application->id) }}" onsubmit="return confirm('Withdraw this application?')">
            @csrf @method('DELETE')
            <button type="submit" class="text-sm text-red-400 hover:text-red-600 border border-red-200 rounded-xl px-4 py-2 transition">Withdraw Application</button>
        </form>
    </div>
    @endif
</div>
</x-app-layout>
