@extends('layout')

@section('content')
<div class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="card bg-white p-8 rounded-xl w-full max-w-md">
        <div class="text-center mb-6">
            <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-id-card-alt text-blue-500 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Assign RFID Card</h1>
            <p class="text-gray-500 mt-1">Register a new worker with their RFID card</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} errors with your
                        submission</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('rfids.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Worker Name -->
            <div>
                <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">Worker Name</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" id="owner_name" name="owner_name" required
                        class="input-focus block w-full pl-10 p-3 border border-gray-300 rounded-lg focus:outline-none"
                        placeholder="John Doe">
                </div>
            </div>

            <!-- RFID UID -->
            <div>
                <label for="uid" class="block text-sm font-medium text-gray-700 mb-1">RFID Card UID</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-rfid text-gray-400"></i>
                    </div>
                    <input type="text" id="uid" name="uid" readonly
                        class="block w-full pl-10 p-3 border border-gray-300 bg-gray-50 rounded-lg focus:outline-none">
                </div>
                <div id="uid-placeholder" class="rfid-animation mt-2 flex items-center text-sm text-blue-600">
                    <i class="fas fa-circle-notch fa-spin mr-2"></i>
                    <span>Waiting for RFID card scan...</span>
                </div>
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                    <input type="tel" id="phone_number" name="phone_number" required
                        class="input-focus block w-full pl-10 p-3 border border-gray-300 rounded-lg focus:outline-none"
                        placeholder="081234567890">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                    class="btn-primary w-full px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Assign Card
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tailwind CSS & Font Awesome -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<!-- Extra Styles -->
<style>
.card {
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.input-focus:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

.btn-primary {
    transition: all 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
}

.rfid-animation {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        opacity: 0.7;
    }

    50% {
        opacity: 1;
    }

    100% {
        opacity: 0.7;
    }
}
</style>

<!-- WebSocket Script -->
<script>
const socket = new WebSocket("ws://172.20.10.8:81"); // Adjust IP to your ESP32 WebSocket server

socket.onopen = () => {
    console.log("WebSocket Connected");
    document.getElementById('uid-placeholder').innerHTML =
        '<i class="fas fa-circle-notch fa-spin mr-2"></i><span>Connected - Waiting for RFID card scan...</span>';
};

socket.onmessage = (event) => {
    try {
        const data = JSON.parse(event.data);
        const uidInput = document.getElementById('uid');
        const placeholder = document.getElementById('uid-placeholder');

        if (data.uid) {
            uidInput.value = data.uid;
            placeholder.innerHTML =
                '<i class="fas fa-check-circle text-green-500 mr-2"></i><span>RFID card detected!</span>';
            placeholder.classList.remove('text-blue-600', 'rfid-animation');
            placeholder.classList.add('text-green-600');
        }
    } catch (e) {
        console.error("Error parsing WebSocket message:", e);
    }
};

socket.onerror = () => {
    const placeholder = document.getElementById('uid-placeholder');
    placeholder.innerHTML =
        '<i class="fas fa-exclamation-triangle text-red-500 mr-2"></i><span>Connection error - Please check ESP32 connection</span>';
    placeholder.classList.remove('text-blue-600', 'rfid-animation');
    placeholder.classList.add('text-red-600');
};
</script>
@endsection