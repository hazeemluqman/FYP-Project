@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">RFID Checkpoints</h1>
        <div class="relative">
            <input type="text" id="search-input" placeholder="Search checkpoints..."
                class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner
                            Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Checkpoint</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                            Tap In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="checkpoint-table" class="bg-white divide-y divide-gray-200">
                    @foreach ($checkpoints as $checkpoint)
                    <tr id="row-{{ $checkpoint->id }}" data-uid="{{ $checkpoint->uid }}"
                        class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $checkpoint->uid }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $checkpoint->owner_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $checkpoint->checkpoint }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $checkpoint->last_tap_in ? \Carbon\Carbon::parse($checkpoint->last_tap_in)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <button onclick="deleteCheckpoint({{ $checkpoint->id }})"
                                class="text-red-600 hover:text-red-900 transition-colors duration-150"
                                title="Delete checkpoint">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($checkpoints->isEmpty())
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No checkpoints</h3>
            <p class="mt-1 text-sm text-gray-500">Start tapping RFID cards to see checkpoints appear here.</p>
        </div>
        @endif
    </div>

    <!-- Connection Status Indicator -->
    <div class="mt-4 flex items-center">
        <span id="connection-status" class="flex items-center">
            <span class="h-3 w-3 rounded-full bg-gray-400 mr-2" id="connection-indicator"></span>
            <span class="text-sm text-gray-600">Connecting to WebSocket...</span>
        </span>
    </div>
</div>

<script>
const socket = new WebSocket("ws://172.20.10.8:81");
const connectionIndicator = document.getElementById('connection-indicator');
const connectionStatus = document.getElementById('connection-status');

socket.onopen = () => {
    console.log("✅ WebSocket connected");
    connectionIndicator.classList.remove('bg-gray-400');
    connectionIndicator.classList.add('bg-green-500');
    connectionStatus.querySelector('span:last-child').textContent = 'Connected to RFID reader';
};

socket.onerror = (error) => {
    console.error("❌ WebSocket error:", error);
    connectionIndicator.classList.remove('bg-gray-400', 'bg-green-500');
    connectionIndicator.classList.add('bg-red-500');
    connectionStatus.querySelector('span:last-child').textContent = 'Connection error - try refreshing the page';
};

socket.onclose = () => {
    connectionIndicator.classList.remove('bg-gray-400', 'bg-green-500');
    connectionIndicator.classList.add('bg-red-500');
    connectionStatus.querySelector('span:last-child').textContent = 'Disconnected from RFID reader';
};

// Handle WebSocket message and add or update checkpoint rows
socket.onmessage = async function(event) {
    try {
        const data = JSON.parse(event.data);
        const uid = data.uid;
        const checkpoint = data.checkpoint;

        console.log("📡 Received:", data);

        // Show visual feedback for new tap
        showTapNotification(uid, checkpoint);

        // Send data to the backend to save it
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
            const newTap = result.data;
            console.log("✅ Checkpoint saved:", newTap);
            addCheckpointRow(newTap);
        } else {
            const errorText = await response.text();
            console.error("❌ Failed to save checkpoint:", errorText);
            showErrorNotification("Error saving checkpoint: " + errorText);
        }
    } catch (e) {
        console.error("❌ Error parsing WebSocket message or saving checkpoint:", e);
        showErrorNotification("Error processing RFID tap");
    }
};

// Function to show visual feedback for new tap
function showTapNotification(uid, checkpoint) {
    const notification = document.createElement('div');
    notification.className =
        'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center animate-fade-in-up';
    notification.innerHTML = `
        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
        </svg>
        <div>
            <p class="font-semibold">New RFID Tap</p>
            <p class="text-sm">UID: ${uid} at ${checkpoint}</p>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.classList.add('animate-fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function showErrorNotification(message) {
    const notification = document.createElement('div');
    notification.className =
        'fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center animate-fade-in-up';
    notification.innerHTML = `
        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div>
            <p class="font-semibold">Error</p>
            <p class="text-sm">${message}</p>
        </div>
    `;

    document.body.appendChild(notification);

    // Remove notification after 5 seconds
    setTimeout(() => {
        notification.classList.add('animate-fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Function to add or update a checkpoint row
function addCheckpointRow(newTap) {
    const table = document.getElementById("checkpoint-table");
    const existingRow = document.getElementById(`row-${newTap.id}`);

    // Format timestamp consistently with server
    const formatTime = (timestamp) => {
        if (!timestamp) return 'Never';
        try {
            const date = new Date(timestamp);
            // Match server format (Y-m-d H:i:s)
            const pad = num => num.toString().padStart(2, '0');
            return `${date.getFullYear()}-${pad(date.getMonth()+1)}-${pad(date.getDate())} ` +
                `${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
        } catch (e) {
            console.error("Error formatting date:", e);
            return timestamp; // fallback to raw value
        }
    };

    const newRowHTML = `
        <tr id="row-${newTap.id}" data-uid="${newTap.uid}" class="hover:bg-gray-50 transition-colors duration-150 bg-green-50 animate-pulse">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${newTap.uid}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${newTap.owner_name || 'Unknown'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${newTap.checkpoint}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${formatTime(newTap.last_tap_in)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <button onclick="deleteCheckpoint(${newTap.id})"
                    class="text-red-600 hover:text-red-900 transition-colors duration-150"
                    title="Delete checkpoint">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </td>
        </tr>`;

    if (existingRow) {
        existingRow.outerHTML = newRowHTML;
        const row = document.getElementById(`row-${newTap.id}`);
        row.classList.add('bg-green-50', 'animate-pulse');
        setTimeout(() => row.classList.remove('bg-green-50', 'animate-pulse'), 2000);
    } else {
        table.insertAdjacentHTML('afterbegin', newRowHTML);
        const row = document.getElementById(`row-${newTap.id}`);
        setTimeout(() => row.classList.remove('bg-green-50', 'animate-pulse'), 2000);
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
}

// Function to delete a checkpoint row
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
                const row = document.getElementById(`row-${id}`);
                if (row) {
                    row.classList.add('bg-red-50', 'animate-pulse');
                    setTimeout(() => row.remove(), 300);
                }
                showSuccessNotification('Checkpoint deleted successfully');
            } else {
                const errorText = await response.text();
                throw new Error(errorText || 'Failed to delete checkpoint');
            }
        } catch (error) {
            console.error("❌ Failed to delete checkpoint:", error);
            showErrorNotification('Failed to delete checkpoint: ' + error.message);
        }
    }
}

function showSuccessNotification(message) {
    const notification = document.createElement('div');
    notification.className =
        'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center animate-fade-in-up';
    notification.innerHTML = `
        <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <div>
            <p class="font-semibold">Success</p>
            <p class="text-sm">${message}</p>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add('animate-fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Search functionality
document.getElementById('search-input').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#checkpoint-table tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
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

.animate-fade-in-up {
    animation: fadeInUp 0.3s ease-out forwards;
}

.animate-fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1);
}

@keyframes pulse {

    0%,
    100% {
        background-color: inherit;
    }

    50% {
        background-color: #f0fdf4;
    }
}
</style>
@endsection