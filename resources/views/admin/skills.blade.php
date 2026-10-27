<x-admin-layout>
<x-slot name="title">Skills</x-slot>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl border border-gray-200 p-5 h-fit">
        <h3 class="font-semibold text-gray-800 mb-4">Add Skill</h3>
        <form method="POST" action="{{ route('admin.skills.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Skill Name *</label><input type="text" name="name" class="input" required placeholder="e.g. PHP, Laravel, React"></div>
            <div><label class="label">Category</label><input type="text" name="category" class="input" placeholder="Programming, Design…"></div>
            <button type="submit" class="btn-primary w-full">Add Skill</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">All Skills ({{ $skills->count() }})</h3>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($skills as $skill)
            <div class="group flex items-center gap-1 bg-gray-50 border border-gray-200 rounded-lg px-3 py-1.5">
                <span class="text-sm text-gray-700">{{ $skill->name }}</span>
                <form method="POST" action="{{ route('admin.skills.destroy', $skill->id) }}" onsubmit="return confirm('Delete {{ $skill->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-gray-300 hover:text-red-500 ml-1 text-xs opacity-0 group-hover:opacity-100 transition">✕</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
</x-admin-layout>
