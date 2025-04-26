@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">RFID Checkpoint Monitoring</h1>
        <div class="relative">
            <input type="text" id="search-input" placeholder="Search cards or checkpoints..."
                class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    @if($checkpoints->isEmpty())
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-lg font-medium text-gray-900">No checkpoint activity yet</h3>
        <p class="mt-1 text-gray-500">RFID card taps will appear here automatically</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($checkpoints->groupBy('checkpoint') as $location => $locationCheckpoints)

        <div class="bg-white rounded-lg shadow overflow-hidden h-fit">
            <div class="px-6 py-4 bg-gray-50 border-b flex justify-between items-center">
                <h2 class="font-semibold text-lg text-gray-800">{{ $location }}</h2>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ $locationCheckpoints->count() }} {{ Str::plural('tap', $locationCheckpoints->count()) }}
                </span>
            </div>

            <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @foreach($locationCheckpoints as $checkpoint)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150 flex justify-between items-start">
                    <div>
                        <div class="flex items-center">
                            <div class="font-medium text-gray-900">{{ $checkpoint->uid }}</div>
                            @if($checkpoint->owner_name)
                            <span class="ml-2 text-sm text-gray-500">({{ $checkpoint->owner_name }})</span>
                            @endif
                        </div>
                        <div class="mt-1 text-sm text-gray-500">
                            <span
                                title="{{ $checkpoint->last_tap_in ? \Carbon\Carbon::parse($checkpoint->last_tap_in)->format('Y-m-d H:i:s') : 'Never' }}">
                                {{ $checkpoint->last_tap_in ? \Carbon\Carbon::parse($checkpoint->last_tap_in)->diffForHumans() : 'Never tapped' }}
                            </span>
                        </div>
                    </div>
                    <button onclick="deleteCheckpoint({{ $checkpoint->id }})"
                        class="text-red-500 hover:text-red-700 transition-colors duration-150 p-1"
                        title="Delete record">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Connection Status Indicator -->
    <div class="mt-6 flex items-center">
        <span id="connection-status" class="flex items-center">
            <span class="h-3 w-3 rounded-full bg-gray-400 mr-2" id="connection-indicator"></span>
            <span class="text-sm text-gray-600">Connecting to WebSocket...</span>
        </span>
    </div>
</div>

<script>
// WebSocket connection remains the same as original
const socket = new WebSocket("ws://172.20.10.8:81");
const connectionIndicator = document.getElementById('connection-indicator');
const connectionStatus = document.getElementById('connection-status');

socket.onopen = () => {
    connectionIndicator.classList.remove('bg-gray-400');
    connectionIndicator.classList.add('bg-green-500');
    connectionStatus.querySelector('span:last-child').textContent = 'Connected to RFID reader';
};

socket.onerror = (error) => {
    connectionIndicator.classList.remove('bg-gray-400', 'bg-green-500');
    connectionIndicator.classList.add('bg-red-500');
    connectionStatus.querySelector('span:last-child').textContent = 'Connection error - try refreshing the page';
};

socket.onclose = () => {
    connectionIndicator.classList.remove('bg-gray-400', 'bg-green-500');
    connectionIndicator.classList.add('bg-red-500');
    connectionStatus.querySelector('span:last-child').textContent = 'Disconnected from RFID reader';
};

socket.onmessage = async function(event) {
    try {
        const data = JSON.parse(event.data);
        const uid = data.uid;
        const checkpoint = data.checkpoint;

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
            console.log("✅ Checkpoint saved:", result.data);
            window.location.reload();
        } else {
            const errorText = await response.text();
            console.error("❌ Failed to save checkpoint:", errorText);
            showErrorNotification("Error saving checkpoint: " + errorText);
        }
    } catch (e) {
        console.error("❌ Error processing RFID tap:", e);
        showErrorNotification("Error processing RFID tap");
    }
};

async function deleteCheckpoint(id) {
    if (confirm('Are you sure you want to delete this checkpoint record?')) {
        try {
            const response = await fetch(`/checkpoints/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (response.ok) {
                showSuccessNotification('Checkpoint record deleted successfully');
                window.location.reload();
            } else {
                const errorText = await response.text();
                throw new Error(errorText || 'Failed to delete record');
            }
        } catch (error) {
            console.error("❌ Failed to delete checkpoint:", error);
            showErrorNotification('Failed to delete record: ' + error.message);
        }
    }
}

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
    setTimeout(() => {
        notification.classList.add('animate-fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
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

// Search functionality for side-by-side view
document.getElementById('search-input').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.divide-y > div'); // Target individual tap records

    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            card.style.display = '';
            // Make sure parent sections are visible
            card.closest('.bg-white').style.display = '';
        } else {
            card.style.display = 'none';
        }

        // Hide entire location sections if they have no visible cards
        document.querySelectorAll('.bg-white').forEach(section => {
            const hasVisibleCards = section.querySelector('.divide-y > div[style=""]') !== null;
            section.style.display = hasVisibleCards ? '' : 'none';
        });
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

/* Custom scrollbar for checkpoint lists */
.max-h-96::-webkit-scrollbar {
    width: 6px;
}

.max-h-96::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

.max-h-96::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}
</style>
@endsection