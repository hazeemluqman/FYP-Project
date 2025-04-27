@extends('layout')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
            <i class="fas fa-leaf text-green-600 mr-2"></i>
            SMART-WET Dashboard
        </h1>
        <p class="text-gray-600">Welcome back, Admin! <span class="text-green-600">Today is
                {{ date('l, F j, Y') }}</span></p>
    </div>
    <div class="flex items-center space-x-4">
        <div class="relative">
            <i class="fas fa-bell text-gray-500 text-xl cursor-pointer hover:text-green-600 smooth-transition"></i>
            <span
                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
        </div>
        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center cursor-pointer">
            <i class="fas fa-user text-green-600"></i>
        </div>
    </div>
</div>
<div class="container mx-auto px-4 py-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div
            class="card bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Active Workers</p>
                    <h3 class="text-2xl font-bold mt-1">142</h3>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <i class="fas fa-users text-green-600"></i>
                </div>
            </div>
            <p class="text-xs text-green-600 mt-2"><i class="fas fa-arrow-up mr-1"></i> 12% from yesterday</p>
        </div>

        <div
            class="card bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Checkpoints</p>
                    <h3 class="text-2xl font-bold mt-1">15</h3>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i class="fas fa-map-marker-alt text-yellow-600"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">All checkpoints operational</p>
        </div>
    </div>

    <!-- Checkpoint Monitoring Section -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                RFID Checkpoint Monitoring
            </h2>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" id="search-input" placeholder="Search cards or checkpoints..."
                        class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
                <button class="text-sm text-green-600 hover:text-green-800 flex items-center smooth-transition">
                    View All <i class="fas fa-chevron-right ml-1 text-xs"></i>
                </button>
            </div>
        </div>

        <div class="p-6">
            @if($checkpoints->isEmpty())
            <div class="bg-gray-50 rounded-lg p-8 text-center">
                <div class="mx-auto h-16 w-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-map-marked-alt text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">No checkpoint activity yet</h3>
                <p class="mt-1 text-gray-500">RFID card taps will appear here automatically</p>
            </div>
            @else
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($checkpoints->groupBy('checkpoint') as $location => $locationCheckpoints)
                <div
                    class="bg-gray-50 rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="px-6 py-4 bg-white border-b flex justify-between items-center">
                        <div class="flex items-center">
                            <div class="h-10 w-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-map-pin text-green-600"></i>
                            </div>
                            <h3 class="font-semibold text-gray-800">{{ $location }}</h3>
                        </div>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                            {{ $locationCheckpoints->count() }} {{ Str::plural('tap', $locationCheckpoints->count()) }}
                        </span>
                    </div>

                    <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                        @foreach($locationCheckpoints as $checkpoint)
                        <div
                            class="px-6 py-4 hover:bg-white transition-colors duration-150 flex justify-between items-start group">
                            <div class="flex items-start">
                                <div
                                    class="h-8 w-8 bg-blue-50 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <i class="fas fa-id-card text-blue-500 text-sm"></i>
                                </div>
                                <div>
                                    <div class="flex items-center">
                                        <div class="font-medium text-gray-900">{{ $checkpoint->uid }}</div>
                                        @if($checkpoint->owner_name)
                                        <span class="ml-2 text-sm text-gray-500">({{ $checkpoint->owner_name }})</span>
                                        @endif
                                    </div>
                                    <div class="mt-1 text-sm text-gray-500 flex items-center">
                                        <i class="far fa-clock mr-1.5 text-gray-400"></i>
                                        <span
                                            title="{{ $checkpoint->last_tap_in ? \Carbon\Carbon::parse($checkpoint->last_tap_in)->format('Y-m-d H:i:s') : 'Never' }}">
                                            {{ $checkpoint->last_tap_in ? \Carbon\Carbon::parse($checkpoint->last_tap_in)->diffForHumans() : 'Never tapped' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button onclick="deleteCheckpoint({{ $checkpoint->id }})"
                                class="text-gray-400 hover:text-red-500 transition-colors duration-150 p-1 opacity-0 group-hover:opacity-100"
                                title="Delete record">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Connection Status -->
    <div class="mt-6 flex items-center bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="flex items-center">
            <span id="connection-indicator" class="h-3 w-3 rounded-full bg-gray-400 mr-3 animate-pulse"></span>
            <span id="connection-status" class="text-sm text-gray-600">Connecting to WebSocket...</span>
        </div>
        <button onclick="window.location.reload()"
            class="ml-auto text-sm text-green-600 hover:text-green-800 flex items-center">
            <i class="fas fa-sync-alt mr-1"></i> Reconnect
        </button>
    </div>
</div>

<script>
// Enhanced WebSocket connection with retry logic
let socket;
let retryCount = 0;
const maxRetries = 5;
const retryDelay = 3000; // 3 seconds

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

// Initialize WebSocket connection
connectWebSocket();

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
                throw new Error(await response.text());
            }
        } catch (error) {
            showErrorNotification('Failed to delete record: ' + error.message);
        }
    }
}

// Notification functions with improved styling
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

// Enhanced search functionality
document.getElementById('search-input').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.divide-y > div');

    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? '' : 'none';

        // Show/hide entire location sections
        const section = card.closest('.bg-gray-50');
        const hasVisibleCards = section.querySelector('.divide-y > div[style=""]') !== null;
        section.style.display = hasVisibleCards ? '' : 'none';
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

/* Custom scrollbar */
.max-h-96::-webkit-scrollbar {
    width: 6px;
    height: 6px;
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

.smooth-transition {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
@endsection