<x-admin-layout>
<x-slot name="title">Locations</x-slot>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-2xl border border-gray-200 p-5 h-fit">
        <h3 class="font-semibold text-gray-800 mb-4">Add Location</h3>
        <form method="POST" action="{{ route('admin.locations.store') }}" class="space-y-3">
            @csrf
            <div><label class="label">City *</label><input type="text" name="city" class="input" required placeholder="e.g. Lahore"></div>
            <div><label class="label">City (Urdu)</label><input type="text" name="city_ur" class="input" dir="rtl" placeholder="لاہور"></div>
            <div><label class="label">Province</label><input type="text" name="province" class="input" placeholder="Punjab"></div>
            <div><label class="label">Display Order</label><input type="number" name="order" class="input" value="0"></div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" id="loc_active" checked class="accent-brand-600">
                <label for="loc_active" class="text-sm text-gray-600">Active</label>
            </div>
            <button type="submit" class="btn-primary w-full">Add Location</button>
        </form>
    </div>

    <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">All Locations ({{ $locations->count() }})</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-4 py-2.5 text-left">City</th>
                    <th class="px-4 py-2.5 text-left">Province</th>
                    <th class="px-4 py-2.5 text-left">Order</th>
                    <th class="px-4 py-2.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($locations as $loc)
                <tr class="hover:bg-gray-50 transition" x-data="{editing:false}">
                    <td class="px-4 py-2.5">
                        <div x-show="!editing">
                            <p class="font-medium text-gray-800">{{ $loc->city }}</p>
                            @if($loc->city_ur)<p class="text-xs text-gray-400" dir="rtl">{{ $loc->city_ur }}</p>@endif
                        </div>
                        <form x-show="editing" method="POST" action="{{ route('admin.locations.update', $loc->id) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <input type="text" name="city" value="{{ $loc->city }}" class="input text-sm flex-1">
                            <button type="submit" class="btn-primary text-xs py-1.5 px-3">Save</button>
                        </form>
                    </td>
                    <td class="px-4 py-2.5 text-gray-400 text-xs">{{ $loc->province }}</td>
                    <td class="px-4 py-2.5 text-gray-400 text-xs">{{ $loc->order }}</td>
                    <td class="px-4 py-2.5">
                        <div class="flex items-center justify-end gap-2">
                            <button @click="editing=!editing" class="text-xs text-brand-600 hover:underline">Edit</button>
                            <form method="POST" action="{{ route('admin.locations.destroy', $loc->id) }}" onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-400 hover:text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-admin-layout>
