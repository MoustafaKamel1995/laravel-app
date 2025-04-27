<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="flex">
        <aside class="w-1/6 h-screen bg-gray-800 text-white p-4">
            <h2 class="text-2xl font-bold mb-4">Admin Panel</h2>
            <ul>
                <li class="mb-2"><a href="{{ route('dashboard') }}" class="hover:bg-gray-700 block py-2 px-4 rounded">Dashboard</a></li>
                <li class="mb-2"><a href="{{ route('users.index') }}" class="hover:bg-gray-700 block py-2 px-4 rounded">Users</a></li>
                <li class="mb-2"><a href="{{ route('users.create') }}" class="hover:bg-gray-700 block py-2 px-4 rounded">Create User</a></li>
                <li class="mb-2"><a href="{{ route('logs.index') }}" class="hover:bg-gray-700 block py-2 px-4 rounded">Login Logs</a></li>
                <li class="mb-2"><a href="{{ route('settings.index') }}" class="hover:bg-gray-700 block py-2 px-4 rounded">Settings</a></li>
                <li class="mb-2"><a href="{{ route('blocked-countries.index') }}" class="hover:bg-gray-700 block py-2 px-4 rounded">Blocked Countries</a></li>
                <li class="mb-2"><a href="{{ route('update.show') }}" class="hover:bg-gray-700 block py-2 px-4 rounded">Update Script</a></li>
            </ul>
            <form action="{{ route('logout') }}" method="POST" class="mt-4">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full">Logout</button>
            </form>
            <button id="clearCacheBtn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded w-full mt-4">Clear Cache</button>
        </aside>
        <main class="flex-1 p-4">
            @yield('content')
        </main>
    </div>

    <script>
        document.getElementById('clearCacheBtn').addEventListener('click', function() {
            fetch('/clear-cache', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    key: 'MySecureKey123'  // ������ ��� �������� ������ �� .env
                }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                } else {
                    alert('Failed to clear cache');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
