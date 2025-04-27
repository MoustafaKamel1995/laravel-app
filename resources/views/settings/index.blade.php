@extends('layouts.dashboard')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Settings</h1>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('settings.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="api_url" class="block text-sm font-bold mb-2">API URL:</label>
            <input type="text" name="settings[api_url]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $settings['api_url'] ?? '' }}">
        </div>
        <div class="mb-4">
            <label for="serial_check_enabled" class="block text-sm font-bold mb-2">Serial Check Enabled:</label>
            <select name="serial_check_enabled" id="serial_check_enabled" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="1" {{ $serialCheckEnabled == '1' ? 'selected' : '' }}>Enabled</option>
                <option value="0" {{ $serialCheckEnabled == '0' ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="login_attempts" class="block text-sm font-bold mb-2">Login Attempts Limit:</label>
            <input type="number" name="settings[login_attempts]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $settings['login_attempts'] ?? 5 }}">
        </div>
        <div class="mb-4">
            <label for="strict_ip_check" class="block text-sm font-bold mb-2">Strict IP Check:</label>
            <select name="settings[strict_ip_check]" id="strict_ip_check" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="1" {{ $settings['strict_ip_check'] == '1' ? 'selected' : '' }}>Enabled</option>
                <option value="0" {{ $settings['strict_ip_check'] == '0' ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="lockout_duration" class="block text-sm font-bold mb-2">Account Lockout Duration (minutes):</label>
            <input type="number" name="settings[lockout_duration]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $settings['lockout_duration'] ?? 60 }}">
        </div>
        <div class="mb-4">
            <label for="api_status" class="block text-sm font-bold mb-2">API Status:</label>
            <select name="settings[api_status]" id="api_status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="1" {{ $settings['api_status'] == '1' ? 'selected' : '' }}>Enabled</option>
                <option value="0" {{ $settings['api_status'] == '0' ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Settings</button>
    </form>
    <form action="{{ route('settings.clearCache') }}" method="POST" class="mt-4">
        @csrf
        <div class="mb-4">
            <label for="clear_cache_key" class="block text-sm font-bold mb-2">Clear Cache Key:</label>
            <input type="text" name="key" id="clear_cache_key" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Clear Cache</button>
    </form>
@endsection
