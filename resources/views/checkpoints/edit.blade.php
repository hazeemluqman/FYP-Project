@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-edit text-green-600 mr-2"></i>
                Edit Checkpoint Record
            </h1>
            <p class="text-gray-600">Update tap-in time • {{ date('l, F j, Y') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Form Section -->
        <div class="p-6">
            @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
            @endif

            <form action="{{ route('checkpoints.update', $checkpoint->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Worker Info (Read-only) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RFID UID</label>
                        <div
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-700">
                            {{ $checkpoint->uid }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Worker Name</label>
                        <div
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-700">
                            {{ $checkpoint->owner_name }}
                        </div>
                    </div>
                </div>

                <!-- Checkpoint Display (Read-only) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Checkpoint Location</label>
                    <div class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-700">
                        {{ $checkpoint->checkpoint }}
                    </div>
                </div>

                <!-- Only editable field: Date/Time Picker -->
                <div class="mb-6">
                    <label for="last_tap_in" class="block text-sm font-medium text-gray-700 mb-1">Tap In Time</label>
                    <div class="relative">
                        <input type="datetime-local" name="last_tap_in" id="last_tap_in"
                            value="{{ old('last_tap_in', \Carbon\Carbon::parse($checkpoint->last_tap_in)->format('Y-m-d\TH:i')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            required>

                    </div>
                    @error('last_tap_in')
                    <p class="text-red-500 text-sm mt-1 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 border-t border-gray-200 pt-6">
                    <a href="{{ route('checkpoints.index') }}"
                        class="flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </a>
                    <button type="submit"
                        class="flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-save mr-2"></i> Update Time
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection