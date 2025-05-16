@extends('layout')

@section('content')
<div class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="card bg-white p-8 rounded-xl w-full max-w-md">
        <div class="text-center mb-6">
            <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-3">
                <i class="fas fa-id-card-alt text-blue-500 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Worker Details</h1>
            <p class="text-gray-500 mt-1">Update worker information and RFID card</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        There were {{ $errors->count() }} errors with your submission
                    </h3>
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

        <form action="{{ route('rfids.update', $rfid->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Worker Name -->
            <div>
                <label for="owner_name" class="block text-sm font-medium text-gray-700 mb-1">Worker Name</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <input type="text" id="owner_name" name="owner_name"
                        value="{{ old('owner_name', $rfid->owner_name) }}" required
                        class="input-focus block w-full pl-10 p-3 border border-gray-300 rounded-lg focus:outline-none"
                        placeholder="Enter worker name">
                </div>
            </div>

            <!-- RFID UID -->
            <div>
                <label for="uid" class="block text-sm font-medium text-gray-700 mb-1">RFID Card UID</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-rfid text-gray-400"></i>
                    </div>
                    <input type="text" id="uid" name="uid" value="{{ old('uid', $rfid->uid) }}" readonly
                        class="block w-full pl-10 p-3 border border-gray-300 bg-gray-50 rounded-lg focus:outline-none">
                </div>
                <div id="uid-placeholder" class="rfid-animation mt-2 flex items-center text-sm text-blue-600">
                    <i class="fas fa-circle-notch fa-spin mr-2"></i>
                    <span>Waiting for RFID card scan...</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">To update UID, scan a new RFID card</p>
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-phone text-gray-400"></i>
                    </div>
                    <input type="tel" id="phone_number" name="phone_number"
                        value="{{ old('phone_number', $rfid->phone_number) }}" required
                        class="input-focus block w-full pl-10 p-3 border border-gray-300 rounded-lg focus:outline-none"
                        placeholder="Enter phone number">
                </div>
            </div>

            <!-- Gender -->
            <div>
                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                <select id="gender" name="gender"
                    class="block w-full p-3 border border-gray-300 rounded-lg focus:outline-none">
                    <option value="">Select gender</option>
                    <option value="Male" {{ old('gender', $rfid->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $rfid->gender) == 'Female' ? 'selected' : '' }}>Female
                    </option>
                </select>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea id="address" name="address" rows="2"
                    class="block w-full p-3 border border-gray-300 rounded-lg focus:outline-none"
                    placeholder="Enter worker's home address">{{ old('address', $rfid->address) }}</textarea>
            </div>

            <!-- Birthday -->
            <div>
                <label for="birthday" class="block text-sm font-medium text-gray-700 mb-1">Birthday</label>
                <input type="date" id="birthday" name="birthday" value="{{ old('birthday', $rfid->birthday) }}"
                    class="block w-full p-3 border border-gray-300 rounded-lg focus:outline-none">
            </div>

            <!-- Emergency Contact -->
            <div>
                <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-1">Emergency
                    Contact</label>
                <input type="text" id="emergency_contact" name="emergency_contact"
                    value="{{ old('emergency_contact', $rfid->emergency_contact) }}"
                    class="block w-full p-3 border border-gray-300 rounded-lg focus:outline-none"
                    placeholder="Name and phone e.g. Ali (0199998888)">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $rfid->email) }}"
                    class="block w-full p-3 border border-gray-300 rounded-lg focus:outline-none"
                    placeholder="worker@email.com">
            </div>

            <!-- Form Actions -->
            <div class="pt-2 flex justify-end space-x-3">
                <a href="{{ route('rfids.index') }}"
                    class="btn px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit"
                    class="btn px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-2"></i> Update Worker
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const socket = new WebSocket("ws://172.20.10.8:81");

socket.onopen = () => {
    console.log("WebSocket Connected");
    document.getElementById('uid-placeholder').classList.remove('hidden');
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