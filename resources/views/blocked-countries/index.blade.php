@extends('layouts.dashboard')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Blocked Countries</h1>
        <a href="{{ route('blocked-countries.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Block New Country
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-md rounded my-6">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th class="py-3 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Country</th>
                    <th class="py-3 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Code</th>
                    <th class="py-3 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Status</th>
                    <th class="py-3 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Reason</th>
                    <th class="py-3 px-4 bg-gray-100 font-bold uppercase text-sm text-gray-600 border-b border-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($blockedCountries as $country)
                    <tr>
                        <td class="py-3 px-4 border-b border-gray-200">{{ $country->country_name }}</td>
                        <td class="py-3 px-4 border-b border-gray-200">{{ $country->country_code }}</td>
                        <td class="py-3 px-4 border-b border-gray-200">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $country->is_blocked ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $country->is_blocked ? 'Blocked' : 'Allowed' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 border-b border-gray-200">{{ $country->block_reason ?? 'N/A' }}</td>
                        <td class="py-3 px-4 border-b border-gray-200 flex">
                            <a href="{{ route('blocked-countries.edit', $country->id) }}" class="text-blue-500 hover:text-blue-700 mr-3">
                                Edit
                            </a>
                            <form action="{{ route('blocked-countries.destroy', $country->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this country from the block list?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-3 px-4 border-b border-gray-200 text-center text-gray-500">
                            No countries are currently blocked. Click "Block New Country" to add one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
