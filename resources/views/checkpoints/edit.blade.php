@extends('layout')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Checkpoint</h1>

        @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('checkpoints.update', $checkpoint->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="owner_name" class="block text-sm font-medium text-gray-700">Owner Name</label>
                <input type="text" name="owner_name" id="owner_name"
                    value="{{ old('owner_name', $checkpoint->owner_name) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100 text-gray-700 cursor-not-allowed"
                    readonly>
            </div>


            <div class="mb-4">
                <label for="checkpoint" class="block text-sm font-medium text-gray-700">Checkpoint</label>
                <select name="checkpoint" id="checkpoint"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    @foreach($availableCheckpoints as $option)
                    <option value="{{ $option }}"
                        {{ old('checkpoint', $checkpoint->checkpoint) == $option ? 'selected' : '' }}>
                        {{ $option }}
                    </option>
                    @endforeach
                </select>
                @error('checkpoint')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="last_tap_in" class="block text-sm font-medium text-gray-700">Last Tap In</label>
                <input type="datetime-local" name="last_tap_in" id="last_tap_in"
                    value="{{ old('last_tap_in', \Carbon\Carbon::parse($checkpoint->last_tap_in)->format('Y-m-d\TH:i')) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                @error('last_tap_in')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('checkpoints.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Cancel</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection