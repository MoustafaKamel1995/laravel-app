<!-- resources/views/users/edit.blade.php -->
@extends('layouts.dashboard')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Edit User</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold mb-2">Name:</label>
            <input type="text" name="name" value="{{ $user->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-bold mb-2">Email:</label>
            <input type="email" name="email" value="{{ $user->email }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-bold mb-2">Password:</label>
            <input type="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-bold mb-2">Confirm Password:</label>
            <input type="password" name="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="mb-4">
            <label for="is_active" class="block text-sm font-bold mb-2">Active:</label>
            <input type="checkbox" name="is_active" class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="1" {{ $user->is_active ? 'checked' : '' }}>
        </div>
        <div class="mb-4">
            <label for="expiry_date" class="block text-sm font-bold mb-2">Expiry Date:</label>
            <input type="date" name="expiry_date" value="{{ $user->expiry_date->format('Y-m-d') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <div class="mb-4">
            <label for="role" class="block text-sm font-bold mb-2">Role:</label>
            <select name="role" id="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="api_user" {{ $user->role == 'api_user' ? 'selected' : '' }}>API User</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update</button>
    </form>
@endsection
