<x-admin-layout>
<x-slot name="title">FAQ</x-slot>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl border border-gray-200 p-5 h-fit">
        <h3 class="font-semibold text-gray-800 mb-4">Add FAQ</h3>
        <form method="POST" action="{{ route('admin.faqs.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">Category</label><input type="text" name="category" class="input" placeholder="General, Employers, Job Seekers…"></div>
            <div><label class="label">Question *</label><input type="text" name="question" class="input" required></div>
            <div><label class="label">Answer *</label><textarea name="answer" rows="4" class="textarea" required></textarea></div>
            <div><label class="label">Display Order</label><input type="number" name="order" class="input" value="0"></div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="faq_active" checked class="accent-brand-600">
                <label for="faq_active" class="text-sm text-gray-600">Active</label>
            </div>
            <button type="submit" class="btn-primary w-full">Add FAQ</button>
        </form>
    </div>

    <div class="lg:col-span-2 space-y-3">
        @foreach($faqs->groupBy('category') as $catName => $items)
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 bg-gray-50 font-semibold text-gray-700 text-sm">
                {{ $catName ?: 'General' }}
            </div>
            @foreach($items as $faq)
            <div class="px-5 py-4 border-b border-gray-100 last:border-0" x-data="{editing:false}">
                <div x-show="!editing" class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $faq->question }}</p>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $faq->answer }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="{{ $faq->is_active ? 'badge-green' : 'badge-gray' }} badge text-xs">{{ $faq->is_active ? 'Active' : 'Hidden' }}</span>
                        <button @click="editing=true" class="text-xs text-brand-600 hover:underline">Edit</button>
                        <form method="POST" action="{{ route('admin.faqs.destroy', $faq->id) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-600">Delete</button>
                        </form>
                    </div>
                </div>
                <form x-show="editing" method="POST" action="{{ route('admin.faqs.update', $faq->id) }}" class="space-y-2">
                    @csrf @method('PATCH')
                    <input type="text" name="question" value="{{ $faq->question }}" class="input text-sm">
                    <textarea name="answer" rows="3" class="textarea text-sm">{{ $faq->answer }}</textarea>
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary text-xs py-1.5">Save</button>
                        <button type="button" @click="editing=false" class="btn-secondary text-xs py-1.5">Cancel</button>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
</x-admin-layout>
