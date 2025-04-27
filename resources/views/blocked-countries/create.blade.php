@extends('layouts.dashboard')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Block New Country</h1>
        <a href="{{ route('blocked-countries.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Back to List
        </a>
    </div>

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('blocked-countries.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="country_select">
                    Select Country
                </label>
                <select id="country_select" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" onchange="updateCountryFields()">
                    <option value="">-- Select a country --</option>
                    @foreach($availableCountries as $country)
                        <option value="{{ $country['code'] }}" data-name="{{ $country['name'] }}">
                            {{ $country['name'] }} ({{ $country['code'] }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="country_code">
                    Country Code
                </label>
                <input type="text" name="country_code" id="country_code" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required readonly>
                @error('country_code')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="country_name">
                    Country Name
                </label>
                <input type="text" name="country_name" id="country_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required readonly>
                @error('country_name')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="block_reason">
                    Reason for Blocking (Optional)
                </label>
                <textarea name="block_reason" id="block_reason" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                @error('block_reason')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Block Country
                </button>
            </div>
        </form>
    </div>

    <script>
        function updateCountryFields() {
            const select = document.getElementById('country_select');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption.value) {
                document.getElementById('country_code').value = selectedOption.value;
                document.getElementById('country_name').value = selectedOption.getAttribute('data-name');
            } else {
                document.getElementById('country_code').value = '';
                document.getElementById('country_name').value = '';
            }
        }
    </script>
@endsection
