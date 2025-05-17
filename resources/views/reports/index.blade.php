@extends('layout')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
    <!-- Filter Section -->
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Worker Activity Report
                </h2>
                <p class="text-sm text-gray-600 mt-1">Track worker movements and time spent</p>
            </div>
            <div>
                <a href="{{ route('reports.download', ['date' => $date, 'name' => $name, 'checkpoint' => $checkpoint]) }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-150 shadow-sm">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
            </div>
        </div>
        <form method="GET" action="{{ route('reports.index') }}"
            class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" id="date" name="date" value="{{ $date ?? '' }}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3">
            </div>
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Worker Name</label>
                <input type="text" id="name" name="name" value="{{ $name ?? '' }}" placeholder="Search by name"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3">
            </div>
            <div>
                <label for="checkpoint" class="block text-sm font-medium text-gray-700 mb-1">Checkpoint</label>
                <select id="checkpoint" name="checkpoint"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 py-2 px-3">
                    <option value="">All Checkpoints</option>
                    @foreach($checkpoints as $cp)
                    <option value="{{ $cp }}" {{ ($checkpoint ?? '') == $cp ? 'selected' : '' }}>{{ $cp }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-150 shadow-sm flex-1 justify-center">
                    <i class="fas fa-filter mr-2"></i> Apply
                </button>
                <a href="{{ route('reports.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition-colors duration-150 shadow-sm flex-1 justify-center">
                    <i class="fas fa-undo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table Section -->
    <div class="p-6">
        @php
        $workerGroups = $activities->groupBy(function($item) {
        return $item->uid . '|' . \Carbon\Carbon::parse($item->last_tap_in)->format('Y-m-d');
        });
        @endphp

        @forelse($workerGroups as $workerKey => $records)
        @php
        [$uid, $workDate] = explode('|', $workerKey);
        $sorted = $records->sortBy('last_tap_in');
        $first = $sorted->first();
        $last = $sorted->last();
        $checkpointCount = $records->pluck('checkpoint')->unique()->count();
        $duration = ($checkpointCount === 3 && $first && $last && $first->last_tap_in !== $last->last_tap_in)
        ?
        \Carbon\Carbon::parse($first->last_tap_in)->diff(\Carbon\Carbon::parse($last->last_tap_in))->format('%H:%I:%S')
        : 'Not done yet';
        @endphp
        <div class="border rounded-lg overflow-hidden mb-6 shadow-sm">
            <div
                class="bg-gray-50 px-4 py-3 border-b flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <h3 class="font-medium text-gray-700">
                    <i class="far fa-calendar-alt mr-2 text-green-600"></i>
                    {{ \Carbon\Carbon::parse($workDate)->format('l, F j, Y') }}
                </h3>
                <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm">
                    <span class="text-gray-700">
                        <i class="fas fa-user mr-1 text-green-600"></i>
                        <span class="font-semibold">Worker:</span> {{ $first->owner_name ?? 'Unknown' }} (UID:
                        {{ $uid }})
                    </span>
                    <span class="text-gray-700">
                        <i class="fas fa-clock mr-1 text-green-600"></i>
                        <span class="font-semibold">Duration:</span> {{ $duration }}
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Checkpoint</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($records->sortBy('last_tap_in') as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $activity->checkpoint }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($activity->last_tap_in)->format('g:i A') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <i class="fas fa-inbox text-gray-400 text-4xl mb-3"></i>
            <h3 class="text-lg font-medium text-gray-900">No activity data found</h3>
            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or select a different date.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection