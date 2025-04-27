@extends('layouts.dashboard')

@section('content')
    <h1 class="text-3xl font-bold mb-4">Users</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="py-2 px-4 border-b">ID</th>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Email</th>
                    <th class="py-2 px-4 border-b">IP Address</th>
                    <th class="py-2 px-4 border-b">Disk Serial</th>
                    <th class="py-2 px-4 border-b">Memory Serial</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $user->id }}</td>
                        <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                        <td class="py-2 px-4 border-b">{{ $user->ip_address }}</td>
                        <td class="py-2 px-4 border-b">
                            <div class="truncate max-w-xs" title="{{ $user->disk_serial }}">
                                {{ $user->disk_serial }}
                            </div>
                        </td>
                        <td class="py-2 px-4 border-b">
                            <div class="truncate max-w-xs" title="{{ $user->memory_serial }}">
                                {{ $user->memory_serial }}
                            </div>
                        </td>
                        <td class="py-2 px-4 border-b flex space-x-2">
                            <a href="{{ route('users.show', $user->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">View</a>
                            <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
