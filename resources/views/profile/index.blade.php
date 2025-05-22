@extends('layout')

@section('content')
<div class="w-full max-w-2xl mx-auto">
    <!-- SMART-WET Header -->
    <div class="text-center mb-8">
        <div class="flex items-center justify-center space-x-2">
            <i class="fas fa-leaf text-3xl" style="color: #5A6C57;"></i>
            <h1 class="text-3xl font-bold text-gray-800">SMART-WET</h1>
        </div>
        <h2 class="text-lg text-gray-600 mt-1">User Profile</h2>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
        <div class="px-6 py-4 flex justify-between items-center" style="background-color: #5A6C57;">
            <h2 class="text-xl font-semibold text-white">My Profile</h2>
            <a href="{{ route('profile.edit', $user->id) }}"
                class="px-4 py-2 bg-white text-gray-800 rounded-lg hover:bg-gray-100 transition duration-200">
                <i class="fas fa-edit mr-2"></i>Edit Profile
            </a>
        </div>

        <div class="p-6">
            <div class="space-y-5">
                <!-- Name Field -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="block text-sm font-medium text-gray-700">Name:</label>
                    <div class="md:col-span-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-800">{{ $user->name }}</p>
                    </div>
                </div>

                <!-- Email Field -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="block text-sm font-medium text-gray-700">Email:</label>
                    <div class="md:col-span-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-800">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Role Field -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="block text-sm font-medium text-gray-700">Role:</label>
                    <div class="md:col-span-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-800">{{ $user->role ?? '-' }}</p>
                    </div>
                </div>

                <!-- Phone Field -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="block text-sm font-medium text-gray-700">Phone:</label>
                    <div class="md:col-span-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-800">{{ $user->phone ?? '-' }}</p>
                    </div>
                </div>

                <!-- Created At Field -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <label class="block text-sm font-medium text-gray-700">Member Since:</label>
                    <div class="md:col-span-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-800">{{ $user->created_at->format('M j, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection