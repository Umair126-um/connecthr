{{-- resources/views/dashboard/saved.blade.php --}}
<x-app-layout>
<x-slot name="title">Saved Jobs</x-slot>
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Saved Jobs</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($savedJobs as $job)
        <div class="relative">
            @include('partials.job-card', ['job' => $job])
            <form method="POST" action="{{ route('seeker.saved.toggle', $job->id) }}"
                  class="absolute top-3 right-3">
                @csrf
                <button type="submit" class="text-red-400 hover:text-red-600 text-lg transition" title="Remove from saved">❤️</button>
            </form>
        </div>
        @empty
        <div class="col-span-2 card p-16 text-center">
            <div class="text-5xl mb-4">❤️</div>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">No saved jobs</h3>
            <a href="{{ route('jobs.index') }}" class="btn-primary mt-2">Browse Jobs</a>
        </div>
        @endforelse
    </div>
    <div class="mt-6">{{ $savedJobs->links() }}</div>
</div>
</x-app-layout>
