@extends('layouts.dashboard')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Login Logs</h1>

    <form method="GET" action="{{ route('logs.index') }}" class="mb-4">
        <label for="timezone" class="block text-gray-700 text-sm font-bold mb-2">Select Timezone:</label>
        <select name="timezone" id="timezone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @foreach (timezone_identifiers_list() as $timezone)
                <option value="{{ $timezone }}"{{ $timezone == request('timezone', config('app.timezone')) ? ' selected' : '' }}>{{ $timezone }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2">Update</button>
    </form>

    <button id="deleteLogsBtn" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mb-4">Delete All Logs</button>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border-b">Date</th>
                    <th class="py-2 px-4 border-b">Type</th>
                    <th class="py-2 px-4 border-b">Email</th>
                    <th class="py-2 px-4 border-b">IP</th>
                    <th class="py-2 px-4 border-b">Message</th>
                </tr>
            </thead>
            <tbody id="logTableBody">
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border-b log-date whitespace-nowrap">{{ $log['date'] }}</td>
                        <td class="py-2 px-4 border-b whitespace-nowrap">{{ $log['type'] }}</td>
                        <td class="py-2 px-4 border-b whitespace-nowrap">{{ $log['email'] }}</td>
                        <td class="py-2 px-4 border-b whitespace-nowrap">{{ $log['ip'] }}</td>
                        <td class="py-2 px-4 border-b break-words">{{ $log['message'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-2 px-4 border-b text-center">No logs available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->appends(['timezone' => request('timezone')])->links() }}
    </div>

    <script>
        document.getElementById('deleteLogsBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to delete all logs?')) {
                window.location.href = "{{ route('logs.deleteAll') }}";
            }
        });
    </script>
@endsection

@php
use Illuminate\Support\Str;
@endphp
