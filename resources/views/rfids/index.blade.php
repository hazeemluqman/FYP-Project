@extends('layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">RFID Card Management</h1>
            <p class="text-gray-600 mt-1">Manage all assigned RFID cards and worker information</p>
        </div>

        <a href="{{ route('rfids.create') }}"
            class="mt-4 md:mt-0 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow-sm flex items-center transition-all duration-200">
            <i class="fas fa-plus-circle mr-2"></i>
            Assign New RFID
        </a>
    </div>

    @if ($rfids->isEmpty())
    <div class="bg-white rounded-xl shadow-sm p-8 text-center">
        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-id-card text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-700">No RFID Cards Assigned</h3>
        <p class="text-gray-500 mt-1 mb-4">Get started by assigning your first RFID card</p>
        <a href="{{ route('rfids.create') }}"
            class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
            Assign RFID Card
        </a>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Worker</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                            Activity</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($rfids as $rfid)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="text-sm font-medium text-gray-900 font-mono">{{ $rfid->uid }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $rfid->owner_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">{{ $rfid->phone_number ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                @if($rfid->last_tap_in)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ \Carbon\Carbon::parse($rfid->last_tap_in)->diffForHumans() }}
                                </span>
                                @else
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Never
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('rfids.show', $rfid->id) }}"
                                    class="text-green-600 hover:text-green-900 mr-3" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('rfids.edit', $rfid->id) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-3" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('rfids.destroy', $rfid->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this RFID card?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@if(session('success'))
<div id="notification"
    class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg flex items-center transform transition-all duration-300 translate-y-4 opacity-0">
    <i class="fas fa-check-circle mr-2"></i>
    <span>{{ session('success') }}</span>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notification = document.getElementById('notification');
    notification.classList.remove('translate-y-4', 'opacity-0');
    notification.classList.add('translate-y-0', 'opacity-100');

    setTimeout(() => {
        notification.classList.add('translate-y-4', 'opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
});
</script>
@endif
@endsection