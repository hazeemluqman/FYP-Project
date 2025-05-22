@extends('layout')

@section('content')
<div class="w-full max-w-2xl mx-auto">
    <!-- SMART-WET Header -->
    <div class="text-center mb-8">
        <div class="flex items-center justify-center space-x-2">
            <i class="fas fa-leaf text-3xl" style="color: #5A6C57;"></i>
            <h1 class="text-3xl font-bold text-gray-800">SMART-WET</h1>
        </div>
        <h2 class="text-lg text-gray-600 mt-1">Edit Profile</h2>
    </div>

    <!-- Edit Profile Form -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 p-6">
        <form action="{{ route('profile.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name Input -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="mt-1 block w-full rounded-lg border border-gray-300 p-2" required>
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Input -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone:</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                    class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
                @error('phone')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Input -->
            <div class="mb-6">
                <label for="role" class="block text-sm font-medium text-gray-700">Role:</label>
                <select name="role" id="role" class="mt-1 block w-full rounded-lg border border-gray-300 p-2">
                    <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>Manager
                    </option>
                    <option value="worker" {{ old('role', $user->role) == 'worker' ? 'selected' : '' }}>Worker</option>
                </select>
                @error('role')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('profile.index', $user->id) }}"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition duration-200">Cancel</a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection