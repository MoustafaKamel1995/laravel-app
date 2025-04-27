<!-- resources/views/users/show.blade.php -->

@extends('layouts.dashboard')

@section('content')
    <h1 class="text-3xl font-bold mb-4">User Details</h1>
    <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <p class="text-lg"><strong>Name:</strong> {{ $user->name }}</p>
        </div>
        <div class="mb-4">
            <p class="text-lg"><strong>Email:</strong> {{ $user->email }}</p>
        </div>
        <div class="mb-4">
            <p class="text-lg"><strong>IP Address:</strong> {{ $user->ip_address }}</p>
        </div>
        <div class="mb-4">
            <p class="text-lg"><strong>Disk Serial:</strong></p>
            <div class="bg-gray-100 p-2 rounded-lg text-sm overflow-auto break-all">{{ $user->disk_serial }}</div>
        </div>
        <div class="mb-4">
            <p class="text-lg"><strong>Memory Serial:</strong></p>
            <div class="bg-gray-100 p-2 rounded-lg text-sm overflow-auto break-all">{{ $user->memory_serial }}</div>
        </div>
        <div class="mb-4">
            <p class="text-lg"><strong>Active:</strong> {{ $user->is_active ? 'Yes' : 'No' }}</p>
        </div>
        <div class="mb-4">
            <p class="text-lg"><strong>Expiry Date:</strong> {{ \Carbon\Carbon::parse($user->expiry_date)->format('Y-m-d') }}</p>
        </div>
        <a href="{{ route('users.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Back to Users</a>
    </div>
@endsection
