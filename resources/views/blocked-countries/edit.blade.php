@extends('layouts.dashboard')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Edit Blocked Country</h1>
        <a href="{{ route('blocked-countries.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('blocked-countries.update', $blockedCountry->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Country
                </label>
                <div class="py-2 px-3 bg-gray-100 rounded">
                    {{ $blockedCountry->country_name }} ({{ $blockedCountry->country_code }})
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="is_blocked">
                    Status
                </label>
                <select name="is_blocked" id="is_blocked" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="1" {{ $blockedCountry->is_blocked ? 'selected' : '' }}>Blocked</option>
                    <option value="0" {{ !$blockedCountry->is_blocked ? 'selected' : '' }}>Allowed</option>
                </select>
                @error('is_blocked')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="block_reason">
                    Reason for Blocking (Optional)
                </label>
                <textarea name="block_reason" id="block_reason" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $blockedCountry->block_reason }}</textarea>
                @error('block_reason')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update Country
                </button>
            </div>
        </form>
    </div>
@endsection
