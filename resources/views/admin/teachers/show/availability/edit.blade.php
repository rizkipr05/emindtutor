@extends('layouts.admin')

@section('title', 'Edit Availability')

@section('content')
<div class="container mx-auto px-2">
    <div class="mx-auto bg-white shadow-xl rounded-3xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-orange-600 to-amber-600 px-8 py-6">
            <h2 class="text-3xl font-bold text-white">Edit Availability</h2>
            <p class="text-blue-100 mt-2">{{ $teacher->user->name }}</p>
        </div>

        <!-- Form Content -->
        <div class="p-8">
            <form action="{{ route('admin.teachers.unavailabilities.update', $teacher->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-6">
                    @php
                        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                        $dayColors = ['bg-red-50 border-red-200', 'bg-orange-50 border-orange-200', 'bg-yellow-50 border-yellow-200', 'bg-green-50 border-green-200', 'bg-blue-50 border-blue-200', 'bg-indigo-50 border-indigo-200', 'bg-purple-50 border-purple-200'];
                    @endphp

                    @foreach($days as $index => $day)
                        <div class="border-2 {{ $dayColors[$index] }} rounded-2xl overflow-hidden transition-all duration-200 hover:shadow-lg">
                            <!-- Day Header -->
                            <div class="flex justify-between items-center px-6 py-4 bg-white/50 backdrop-blur-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full {{ str_replace(['bg-', '-50', 'border-', '-200'], ['bg-', '-400', '', ''], $dayColors[$index]) }}"></div>
                                    <span class="font-bold text-lg text-gray-800">{{ $day }}</span>
                                </div>
                                <button type="button" 
                                    class="add-slot inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors duration-200 shadow-sm" 
                                    data-day="{{ $index }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Slot
                                </button>
                            </div>

                            <!-- Time Slots -->
                            <div class="day-slots px-6 pb-4 space-y-3" id="day-{{ $index }}">
                                @foreach($unavailabilities->where('day', $index) as $slot)
                                    <div class="flex items-center gap-3 slot bg-white rounded-xl p-4 shadow-sm border border-gray-100">
                                        <input type="hidden" name="unavailability[{{ $day }}][{{ $loop->index }}][day]" value="{{ $index }}">
                                        
                                        <div class="flex items-center gap-3 flex-1">
                                            <div class="flex flex-col">
                                                <label class="text-xs font-medium text-gray-500 mb-1">Start Time</label>
                                                <input type="time" 
                                                    name="unavailability[{{ $day }}][{{ $loop->index }}][start_time]" 
                                                    value="{{ $slot->start_time }}" 
                                                    class="border-2 border-gray-200 px-3 py-2 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-sm font-medium">
                                            </div>
                                            
                                            <div class="flex items-center justify-center px-2">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                </svg>
                                            </div>
                                            
                                            <div class="flex flex-col">
                                                <label class="text-xs font-medium text-gray-500 mb-1">End Time</label>
                                                <input type="time" 
                                                    name="unavailability[{{ $day }}][{{ $loop->index }}][end_time]" 
                                                    value="{{ $slot->end_time }}" 
                                                    class="border-2 border-gray-200 px-3 py-2 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-sm font-medium">
                                            </div>
                                        </div>
                                        
                                        <button type="button" 
                                            class="remove-slot inline-flex items-center justify-center w-10 h-10 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-10 pt-6 border-t border-gray-200">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-orange-600 to-amber-600 text-white font-semibold rounded-xl hover:from-orange-700 hover:to-amber-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const dayColors = ['border-red-200', 'border-orange-200', 'border-yellow-200', 'border-green-200', 'border-blue-200', 'border-indigo-200', 'border-purple-200'];
    
    document.querySelectorAll(".add-slot").forEach(btn => {
        btn.addEventListener("click", function() {
            const dayIndex = this.dataset.day;
            const container = document.getElementById("day-" + dayIndex);
            const slotIndex = container.querySelectorAll(".slot").length;

            const div = document.createElement("div");
            div.classList.add("flex", "items-center", "gap-3", "slot", "bg-white", "rounded-xl", "p-4", "shadow-sm", "border", "border-gray-100");
            div.innerHTML = `
                <input type="hidden" name="unavailability[${dayIndex}][${slotIndex}][day]" value="${dayIndex}">
                <div class="flex items-center gap-3 flex-1">
                    <div class="flex flex-col">
                        <label class="text-xs font-medium text-gray-500 mb-1">Start Time</label>
                        <input type="time" 
                            name="unavailability[${dayIndex}][${slotIndex}][start_time]" 
                            class="border-2 border-gray-200 px-3 py-2 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-sm font-medium">
                    </div>
                    <div class="flex items-center justify-center px-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <label class="text-xs font-medium text-gray-500 mb-1">End Time</label>
                        <input type="time" 
                            name="unavailability[${dayIndex}][${slotIndex}][end_time]" 
                            class="border-2 border-gray-200 px-3 py-2 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 text-sm font-medium">
                    </div>
                </div>
                <button type="button" 
                    class="remove-slot inline-flex items-center justify-center w-10 h-10 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            `;
            container.appendChild(div);

            div.querySelector(".remove-slot").addEventListener("click", function() {
                div.remove();
            });
        });
    });

    document.querySelectorAll(".remove-slot").forEach(btn => {
        btn.addEventListener("click", function() {
            this.closest(".slot").remove();
        });
    });
});
</script>
@endsection