@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">RFID Access Cards</h1>
            <p class="text-gray-600 mt-1">Track all registered cards and their checkpoint activity</p>
        </div>

        <div class="w-full md:w-auto">
            <div class="relative">
                <input type="text" placeholder="Search cards..."
                    class="pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    @if ($rfids->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
            </path>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-700">No RFID Cards Found</h3>
        <p class="mt-2 text-gray-500">Register a new RFID card to start tracking checkpoints</p>
    </div>
    @else
    <!-- Cards Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Card Details
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Last Activity
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Checkpoints
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($rfids as $rfid)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <div class="font-mono text-sm font-medium text-gray-900">{{ $rfid->uid }}</div>
                                    <div class="text-sm text-gray-500">{{ $rfid->owner_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($rfid->checkpoints && $rfid->checkpoints->count() > 0)
                            <div class="text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($rfid->checkpoints->sortByDesc('last_tap_in')->first()->last_tap_in)->format('M j, Y g:i A') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($rfid->checkpoints->sortByDesc('last_tap_in')->first()->last_tap_in)->diffForHumans() }}
                            </div>
                            @else
                            <span class="text-sm text-gray-400">No activity</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($rfid->checkpoints && $rfid->checkpoints->count() > 0)
                            <div class="flex flex-wrap gap-2 max-w-xs">
                                @foreach($rfid->checkpoints->sortByDesc('last_tap_in')->take(3) as $checkpoint)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $checkpoint->checkpoint }}
                                </span>
                                @endforeach
                                @if($rfid->checkpoints->count() > 3)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    +{{ $rfid->checkpoints->count() - 3 }} more
                                </span>
                                @endif
                            </div>
                            @else
                            <span class="text-sm text-gray-400">No checkpoints</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection