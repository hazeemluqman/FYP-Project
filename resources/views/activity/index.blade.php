@extends('layout')

@section('content')
<div class="container mx-auto px-4 ">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">RFID Access Control</h1>
            <p class="text-gray-600 mt-1">Manage and monitor all registered access cards</p>
        </div>

        <div class="w-full md:w-64">
            <form id="search-form">
                <div class="relative">
                    <input type="text" id="search-input" placeholder="Search cards or names..."
                        class="pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full transition-all duration-200"
                        value="{{ request('search') }}">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </form>
        </div>
    </div>

    @if ($rfids->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
        <div class="mx-auto h-16 w-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                </path>
            </svg>
        </div>
        <h3 class="mt-2 text-lg font-medium text-gray-700">No RFID Cards Registered</h3>
        <p class="mt-2 text-gray-500">Register new cards to begin tracking access activity</p>
        <div class="mt-6">
            <a href="{{ route('rfids.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Register New Card
            </a>
        </div>
    </div>
    @else
    <!-- Cards Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
        @if(request('search'))
        <div
            class="px-6 py-3 bg-blue-50 text-sm text-blue-800 flex justify-between items-center border-b border-blue-100">
            <span class="flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Showing results for "{{ request('search') }}"
            </span>
            <a href="{{ route('rfids.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
                Clear
            </a>
        </div>
        @endif

        <div class="overflow-x-auto bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('activity.index') }}" class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-full sm:w-auto">
                        <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Filter by Date</label>
                        <div class="relative rounded-md shadow-sm">
                            <input type="date" id="date" name="date" value="{{ $date }}"
                                class="block w-full sm:w-48 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3 border">
                        </div>
                    </div>
                    <div class="flex gap-2 sm:self-end">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Apply Filter
                        </button>
                        <a href="{{ route('activity.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Reset Date
                        </a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Card Details
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Last Activity
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Checkpoints
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="cards-table-body">
                    @foreach ($rfids as $rfid)
                    <tr class="hover:bg-blue-50 transition-colors duration-150" data-uid="{{ $rfid->uid }}"
                        data-name="{{ $rfid->owner_name }}">
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
                                    <div class="font-mono text-sm font-medium text-gray-900 flex items-center">
                                        {{ $rfid->uid }}
                                        <span class="ml-2 text-xs font-normal text-gray-400">ID: {{ $rfid->id }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $rfid->owner_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($rfid->checkpoints && $rfid->checkpoints->count() > 0)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor"
                                    viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Active
                            </span>
                            @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                Inactive
                            </span>
                            @endif
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
                            <div class="flex flex-wrap gap-1.5 max-w-xs">
                                @foreach($rfid->checkpoints as $checkpoint)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    {{ $checkpoint->checkpoint }}
                                    <span class="ml-1 text-green-600">{{ $checkpoint->access_count }}</span>
                                </span>
                                @endforeach
                            </div>
                            @else
                            <span class="text-sm text-gray-400">No checkpoints</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('rfids.show', $rfid->id) }}"
                                class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const cardsTableBody = document.getElementById('cards-table-body');
    let debounceTimer;

    // Debounced search function
    searchInput.addEventListener('input', function(e) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const searchTerm = e.target.value.toLowerCase().trim();
            filterCards(searchTerm);
        }, 300);
    });

    // Immediate filter on clear (when user deletes text quickly)
    searchInput.addEventListener('keyup', function(e) {
        if (e.target.value === '') {
            clearTimeout(debounceTimer);
            filterCards('');
        }
    });

    function filterCards(searchTerm) {
        const rows = cardsTableBody.querySelectorAll('tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const uid = row.getAttribute('data-uid').toLowerCase();
            const name = row.getAttribute('data-name').toLowerCase();

            // Clear previous highlights
            const uidCell = row.querySelector('.card-uid');
            const nameCell = row.querySelector('.card-name');
            if (uidCell) uidCell.innerHTML = uidCell.textContent;
            if (nameCell) nameCell.innerHTML = nameCell.textContent;

            if (searchTerm === '' || uid.includes(searchTerm) || name.includes(searchTerm)) {
                row.style.display = '';
                visibleCount++;

                // Highlight matches
                if (searchTerm !== '') {
                    if (uid.includes(searchTerm) && uidCell) {
                        highlightText(uidCell, searchTerm);
                    }
                    if (name.includes(searchTerm) && nameCell) {
                        highlightText(nameCell, searchTerm);
                    }
                }
            } else {
                row.style.display = 'none';
            }
        });

        // Show "no results" message if needed
        if (visibleCount === 0 && searchTerm !== '') {
            showNoResultsMessage(searchTerm);
        } else {
            removeNoResultsMessage();
        }
    }

    function showNoResultsMessage(searchTerm) {
        if (!document.getElementById('no-results-message')) {
            const messageRow = document.createElement('tr');
            messageRow.id = 'no-results-message';
            messageRow.className = 'bg-white';
            messageRow.innerHTML = `
                <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h4 class="text-lg font-medium text-gray-700">No matching cards found</h4>
                        <p class="text-gray-500 mt-1">No cards match "${searchTerm}"</p>
                    </div>
                </td>
            `;
            cardsTableBody.appendChild(messageRow);
        }
    }

    function removeNoResultsMessage() {
        const message = document.getElementById('no-results-message');
        if (message) {
            message.remove();
        }
    }

    function highlightText(element, searchText) {
        const text = element.textContent;
        const regex = new RegExp(searchText, 'gi');
        element.innerHTML = text.replace(regex, match =>
            `<span class="bg-yellow-100 text-yellow-800 px-0.5 rounded">${match}</span>`
        );
    }
});
</script>
@endsection