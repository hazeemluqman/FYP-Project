@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Worker Details</h1>
            <p class="text-gray-600 mt-1">Complete information for {{ $rfid->owner_name }}</p>
        </div>
        <a href="{{ route('rfids.index') }}"
            class="mt-4 md:mt-0 flex items-center text-blue-600 hover:text-blue-800 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Workers List
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
            <!-- Worker Information -->
            <div class="space-y-4">
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-id-card text-blue-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">RFID UID</h3>
                        <p class="mt-1 text-sm font-mono text-gray-900">{{ $rfid->uid }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 h-12 w-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-green-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Worker Name</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $rfid->owner_name }}</p>
                    </div>
                </div>
            </div>

            <!-- Contact & Activity -->
            <div class="space-y-4">
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 h-12 w-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-phone text-purple-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Phone Number</h3>
                        <p class="mt-1 text-sm text-gray-900">{{ $rfid->phone_number ?: 'Not provided' }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0 h-12 w-12 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-500"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Last Activity</h3>
                        <p class="mt-1 text-sm text-gray-900">
                            @if($rfid->last_tap_in)
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ \Carbon\Carbon::parse($rfid->last_tap_in)->diffForHumans() }}
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Never
                            </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('rfids.edit', $rfid->id) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center transition-colors">
                <i class="fas fa-edit mr-2"></i> Edit Worker
            </a>
            <form action="{{ route('rfids.destroy', $rfid->id) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this worker?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center transition-colors">
                    <i class="fas fa-trash-alt mr-2"></i> Delete Worker
                </button>
            </form>
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
        @if($rfid->checkpoints && $rfid->checkpoints->count() > 0)
        <div class="space-y-4">
            @foreach($rfid->checkpoints->sortByDesc('last_tap_in')->take(5) as $checkpoint)
            <div class="flex items-start space-x-4 p-3 border-b border-gray-100 last:border-0">
                <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-gray-500"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-900">{{ $checkpoint->checkpoint }}</h3>
                    <p class="text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($checkpoint->last_tap_in)->format('M j, Y g:i A') }}
                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded ml-2">
                            {{ \Carbon\Carbon::parse($checkpoint->last_tap_in)->diffForHumans() }}
                        </span>
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-history text-3xl mb-2"></i>
            <p>No recent activity found</p>
        </div>
        @endif
    </div>
</div>
@endsection