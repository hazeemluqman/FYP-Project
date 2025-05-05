@extends('layout')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-route text-green-600 mr-2"></i>
            Worker Checkpoint Progress
        </h1>
        <p class="text-gray-600">Live tracking • {{ date('l, F j, Y') }}</p>
    </div>
    <div class="flex items-center space-x-3">
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open" class="relative p-1 text-gray-500 hover:text-green-600">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
            </button>
        </div>
    </div>
</div>

<div class="container mx-auto px-4">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Active Workers</p>
                    <h3 class="text-xl font-bold">{{ $activeWorkers }}</h3>
                </div>
                <i class="fas fa-users text-green-500 text-xl"></i>
            </div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Checkpoints</p>
                    <h3 class="text-xl font-bold">{{ $totalCheckpoints }}</h3>
                </div>
                <i class="fas fa-map-marker-alt text-blue-500 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Date Filter -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-800">Daily Records</h3>
            <div class="relative">
                <input type="date" id="date-filter" value="{{ $date }}"
                    class="border rounded-md px-3 py-2 focus:ring-2 focus:ring-green-500">
            </div>
        </div>
    </div>

    <!-- Checkpoint Progress Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div
            class="px-4 py-3 border-b flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 bg-gray-50">
            <h2 class="font-medium flex items-center">
                <i class="fas fa-user-check text-green-600 mr-2"></i>
                Current Worker Status
            </h2>
            <div class="relative w-full sm:w-64">
                <input type="text" id="search-input" placeholder="Search workers..."
                    class="w-full pl-8 pr-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <i class="fas fa-search absolute left-2.5 top-3 text-gray-400"></i>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Worker
                        </th>
                        <th scope="col"
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            UID
                        </th>
                        <th scope="col"
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Checkpoint 1
                        </th>
                        <th scope="col"
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Checkpoint 2
                        </th>
                        <th scope="col"
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Checkpoint 3
                        </th>
                        <th scope="col"
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Progress
                        </th>
                        <th scope="col"
                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="workers-table-body">
                    @if($checkpoints->isEmpty())
                    <tr>
                        <td colspan="7" class="px-4 py-3 text-center text-gray-500">
                            No checkpoint data available
                        </td>
                    </tr>
                    @else
                    @foreach($checkpoints->groupBy('uid') as $uid => $workerCheckpoints)
                    @php
                    $worker = $workerCheckpoints->first();

                    // Initialize checkpoint times
                    $checkpoint1 = $workerCheckpoints->where('checkpoint', 'Checkpoint 1')->first();
                    $checkpoint2 = $workerCheckpoints->where('checkpoint', 'Checkpoint 2')->first();
                    $checkpoint3 = $workerCheckpoints->where('checkpoint', 'Checkpoint 3')->first();

                    // Calculate progress
                    $completed = 0;
                    if ($checkpoint1) $completed++;
                    if ($checkpoint2) $completed++;
                    if ($checkpoint3) $completed++;
                    $progress = round(($completed / 3) * 100);
                    @endphp
                    <tr class="hover:bg-gray-50 worker-row" data-uid="{{ $uid }}"
                        data-name="{{ $worker->owner_name ?? 'Unknown' }}">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                    <i class="fas fa-user text-blue-500 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 worker-name">
                                        {{ $worker->owner_name ?? 'Unknown' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-mono text-gray-900 worker-uid">
                            {{ $uid }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            @if($checkpoint1)
                            <div>
                                <div>{{ \Carbon\Carbon::parse($checkpoint1->last_tap_in)->format('M j, Y g:i A') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($checkpoint1->last_tap_in)->diffForHumans() }}
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            @if($checkpoint2)
                            <div>
                                <div>{{ \Carbon\Carbon::parse($checkpoint2->last_tap_in)->format('M j, Y g:i A') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($checkpoint2->last_tap_in)->diffForHumans() }}
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            @if($checkpoint3)
                            <div>
                                <div>{{ \Carbon\Carbon::parse($checkpoint3->last_tap_in)->format('M j, Y g:i A') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($checkpoint3->last_tap_in)->diffForHumans() }}
                                </div>
                            </div>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-16 bg-gray-200 rounded-full h-1.5 mr-2">
                                    <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <span class="text-xs font-medium">{{ $progress }}%</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm  flex items-center space-x-2">
                            <!-- Edit Dropdown -->
                            <div class="relative inline-block text-left">
                                <button onclick="toggleEditDropdown({{ $worker->id }})"
                                    class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <div id="edit-dropdown-{{ $worker->id }}"
                                    class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-10">
                                    <div class="py-1">
                                        @if($checkpoint1)
                                        <a href="{{ route('checkpoints.edit', $checkpoint1->id) }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                            Edit Checkpoint 1
                                        </a>
                                        @endif
                                        @if($checkpoint2)
                                        <a href="{{ route('checkpoints.edit', $checkpoint2->id) }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                            Edit Checkpoint 2
                                        </a>
                                        @endif
                                        @if($checkpoint3)
                                        <a href="{{ route('checkpoints.edit', $checkpoint3->id) }}"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                                            Edit Checkpoint 3
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Dropdown -->
                            <div class="relative inline-block text-left">
                                <button onclick="toggleDeleteDropdown({{ $worker->id }})"
                                    class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <div id="delete-dropdown-{{ $worker->id }}"
                                    class="hidden absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-10">
                                    <div class="py-1">
                                        @if($checkpoint1)
                                        <button onclick="deleteCheckpoint({{ $checkpoint1->id }})"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50">
                                            Delete Checkpoint 1
                                        </button>
                                        @endif
                                        @if($checkpoint2)
                                        <button onclick="deleteCheckpoint({{ $checkpoint2->id }})"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50">
                                            Delete Checkpoint 2
                                        </button>
                                        @endif
                                        @if($checkpoint3)
                                        <button onclick="deleteCheckpoint({{ $checkpoint3->id }})"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50">
                                            Delete Checkpoint 3
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- System Status -->
    <div class="bg-white rounded-lg shadow p-3 flex items-center justify-between">
        <div class="flex items-center">
            <span id="connection-indicator" class="h-3 w-3 rounded-full bg-gray-400 mr-3 animate-pulse"></span>
            <span id="connection-status" class="text-sm text-gray-600">Connecting to WebSocket...</span>
        </div>
        <button onclick="window.location.reload()"
            class="text-sm text-green-600 hover:text-green-800 flex items-center">
            <i class="fas fa-sync-alt mr-1"></i> Refresh
        </button>
    </div>
</div>

<script>
// WebSocket connection code
let socket;
let retryCount = 0;
const maxRetries = 5;
const retryDelay = 3000;

function connectWebSocket() {
    socket = new WebSocket("ws://172.20.10.8:81");
    const connectionIndicator = document.getElementById('connection-indicator');
    const connectionStatus = document.getElementById('connection-status');

    socket.onopen = () => {
        connectionIndicator.classList.remove('bg-gray-400', 'bg-red-500', 'animate-pulse');
        connectionIndicator.classList.add('bg-green-500');
        connectionStatus.textContent = 'Connected to RFID reader';
        retryCount = 0;
    };

    socket.onerror = (error) => {
        connectionIndicator.classList.remove('bg-gray-400', 'bg-green-500');
        connectionIndicator.classList.add('bg-red-500');
        connectionStatus.textContent = 'Connection error - attempting to reconnect...';

        if (retryCount < maxRetries) {
            retryCount++;
            setTimeout(connectWebSocket, retryDelay);
        } else {
            connectionStatus.textContent = 'Failed to connect after multiple attempts';
        }
    };

    socket.onclose = () => {
        connectionIndicator.classList.remove('bg-green-500');
        connectionIndicator.classList.add('bg-gray-400', 'animate-pulse');
        connectionStatus.textContent = 'Disconnected - attempting to reconnect...';

        if (retryCount < maxRetries) {
            retryCount++;
            setTimeout(connectWebSocket, retryDelay);
        }
    };

    socket.onmessage = async function(event) {
        try {
            const data = JSON.parse(event.data);
            const uid = data.uid;
            const checkpoint = data.checkpoint;

            showTapNotification(uid, checkpoint);

            const response = await fetch("{{ route('checkpoints.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    uid,
                    checkpoint
                })
            });

            if (response.ok) {
                window.location.reload();
            } else {
                const errorText = await response.text();
                showErrorNotification("Error saving checkpoint: " + errorText);
            }
        } catch (e) {
            console.error("Error processing RFID tap:", e);
            showErrorNotification("Error processing RFID tap");
        }
    };
}

connectWebSocket();

function toggleEditDropdown(workerId) {
    const dropdown = document.getElementById(`edit-dropdown-${workerId}`);
    // Close any other open edit dropdowns
    document.querySelectorAll('.edit-dropdown').forEach(d => {
        if (d.id !== `edit-dropdown-${workerId}`) d.classList.add('hidden');
    });
    dropdown.classList.toggle('hidden');

    // Close delete dropdown if open
    const deleteDropdown = document.getElementById(`delete-dropdown-${workerId}`);
    if (deleteDropdown) deleteDropdown.classList.add('hidden');
}

function toggleDeleteDropdown(workerId) {
    const dropdown = document.getElementById(`delete-dropdown-${workerId}`);
    // Close any other open delete dropdowns
    document.querySelectorAll('.delete-dropdown').forEach(d => {
        if (d.id !== `delete-dropdown-${workerId}`) d.classList.add('hidden');
    });
    dropdown.classList.toggle('hidden');

    // Close edit dropdown if open
    const editDropdown = document.getElementById(`edit-dropdown-${workerId}`);
    if (editDropdown) editDropdown.classList.add('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative.inline-block.text-left')) {
        document.querySelectorAll('.edit-dropdown, .delete-dropdown').forEach(dropdown => {
            dropdown.classList.add('hidden');
        });
    }
});

async function deleteCheckpoint(id) {
    if (confirm('Are you sure you want to delete this checkpoint?')) {
        try {
            const response = await fetch(`/checkpoints/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (response.ok) {
                showSuccessNotification('Checkpoint deleted successfully');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                const errorText = await response.text();
                showErrorNotification('Failed to delete checkpoint: ' + errorText);
            }
        } catch (error) {
            showErrorNotification('An error occurred: ' + error.message);
        }
    }
}

// Notification functions
function showTapNotification(uid, checkpoint) {
    showNotification({
        title: 'New RFID Tap Detected',
        message: `UID: ${uid} at ${checkpoint}`,
        icon: 'fa-map-marker-alt',
        color: 'green'
    });
}

function showSuccessNotification(message) {
    showNotification({
        title: 'Success',
        message: message,
        icon: 'fa-check-circle',
        color: 'green'
    });
}

function showErrorNotification(message) {
    showNotification({
        title: 'Error',
        message: message,
        icon: 'fa-exclamation-triangle',
        color: 'red'
    });
}

function showNotification({
    title,
    message,
    icon,
    color
}) {
    const notification = document.createElement('div');
    notification.className =
        `fixed bottom-4 right-4 bg-${color}-500 text-white px-4 py-3 rounded-lg shadow-lg flex items-start max-w-xs animate-fade-in-up z-50`;
    notification.innerHTML = `
        <div class="h-6 w-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3 mt-0.5 flex-shrink-0">
            <i class="fas ${icon} text-sm"></i>
        </div>
        <div>
            <p class="font-semibold">${title}</p>
            <p class="text-sm mt-1">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="ml-4 opacity-70 hover:opacity-100">
            <i class="fas fa-times"></i>
        </button>
    `;
    document.body.appendChild(notification);
    setTimeout(() => {
        notification.classList.add('animate-fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Search functionality
document.getElementById('search-input').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.worker-row');

    if (searchTerm === '') {
        rows.forEach(row => {
            row.style.display = '';
            // Remove any highlights
            const nameCell = row.querySelector('.worker-name');
            const uidCell = row.querySelector('.worker-uid');
            if (nameCell) nameCell.innerHTML = nameCell.textContent;
            if (uidCell) uidCell.innerHTML = uidCell.textContent;
        });
        return;
    }

    rows.forEach(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const uid = row.getAttribute('data-uid').toLowerCase();

        // Clear previous highlights
        const nameCell = row.querySelector('.worker-name');
        const uidCell = row.querySelector('.worker-uid');
        if (nameCell) nameCell.innerHTML = nameCell.textContent;
        if (uidCell) uidCell.innerHTML = uidCell.textContent;

        if (name.includes(searchTerm) || uid.includes(searchTerm)) {
            row.style.display = '';
            // Highlight matches
            if (name.includes(searchTerm) && nameCell) {
                highlightText(nameCell, searchTerm);
            }
            if (uid.includes(searchTerm) && uidCell) {
                highlightText(uidCell, searchTerm);
            }
        } else {
            row.style.display = 'none';
        }
    });
});

function highlightText(element, searchText) {
    const text = element.textContent;
    const regex = new RegExp(searchText, 'gi');
    element.innerHTML = text.replace(regex, match =>
        `<span class="highlight bg-yellow-200">${match}</span>`
    );
}

// Date filter functionality
document.getElementById('date-filter').addEventListener('change', function(e) {
    const date = e.target.value;
    window.location.href = `{{ route('checkpoints.index') }}?date=${date}`;
});

// Update WebSocket handler to show creation/update
socket.onmessage = async function(event) {
    try {
        const data = JSON.parse(event.data);
        const uid = data.uid;
        const checkpoint = data.checkpoint;

        const response = await fetch("{{ route('checkpoints.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                uid,
                checkpoint
            })
        });

        if (response.ok) {
            const result = await response.json();
            const action = result.action === 'created' ? 'New daily record created' : 'Existing record updated';
            showTapNotification(uid, `${checkpoint} (${action})`);
            window.location.reload();
        } else {
            const errorText = await response.text();
            showErrorNotification("Error saving checkpoint: " + errorText);
        }
    } catch (e) {
        console.error("Error processing RFID tap:", e);
        showErrorNotification("Error processing RFID tap");
    }
};
</script>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }

    to {
        opacity: 0;
    }
}

.highlight {
    background-color: #fef08a;
    padding: 0.1rem 0.2rem;
    border-radius: 0.25rem;
}

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}

.animate-fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

/* Table styling */
table {
    border-collapse: separate;
    border-spacing: 0;
}

th {
    position: sticky;
    top: 0;
    background-color: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

th:first-child {
    border-left: 1px solid #e5e7eb;
}

th:last-child {
    border-right: 1px solid #e5e7eb;
}

tr:hover {
    background-color: #f8fafc;
}

/* Progress bar styling */
.progress-container {
    display: flex;
    align-items: center;
}

.progress-bar {
    width: 60px;
    height: 6px;
    background-color: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
    margin-right: 8px;
}

.progress-fill {
    height: 100%;
    background-color: #10b981;
    transition: width 0.3s ease;
}

/* Smooth transitions */
.smooth-transition {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Action buttons styling */
.fa-edit,
.fa-trash-alt {
    transition: all 0.2s ease;
    cursor: pointer;
}

.fa-edit:hover {
    color: #2563eb;
    transform: scale(1.1);
}

.fa-trash-alt:hover {
    color: #dc2626;
    transform: scale(1.1);
}

/* Dropdown styling */
.edit-dropdown a:hover,
.delete-dropdown button:hover {
    background-color: #f8fafc;
}
</style>
@endsection