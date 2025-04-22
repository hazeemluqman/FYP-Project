<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART-WET Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    .sidebar-item.active {
        background-color: rgba(255, 255, 255, 0.2);
        border-left: 4px solid white;
    }

    .sidebar-item:hover {
        background-color: rgba(255, 255, 255, 0.15);
        transform: translateX(2px);
        transition: all 0.2s ease;
    }

    .logo-transition {
        transition: transform 0.3s ease;
    }

    .logo-transition:hover {
        transform: scale(1.05);
    }
    </style>
</head>

<body class="flex bg-gray-50">
    <!-- Sidebar -->
    <div class="w-64 h-screen text-white flex flex-col justify-between fixed" style="background-color: #5A6C57;">
        <div>
            <div class="p-4 mb-4">
                <div class="logo-transition flex items-center space-x-2">
                    <i class="fas fa-leaf text-2xl"></i>
                    <h1 class="text-xl font-bold">SMART-WET</h1>
                </div>
                <p class="text-xs opacity-80 mt-1">Plantation Workforce Tracking</p>
            </div>

            <ul class="space-y-1">
                <li class="sidebar-item {{ Request::is('checkpoints*') ? 'active' : '' }}">
                    <a href="{{ url('/checkpoints') }}" class="flex items-center p-4 space-x-3">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::is('activities*') ? 'active' : '' }}">
                    <a href="{{ url('/activities') }}" class="flex items-center p-4 space-x-3">
                        <i class="fas fa-tasks w-5"></i>
                        <span>Worker Activity</span>
                    </a>
                </li>
                <li class="sidebar-item {{ Request::is('rfids*') ? 'active' : '' }}">
                    <a href="{{ url('/rfids') }}" class="flex items-center p-4 space-x-3">
                        <i class="fas fa-users w-5"></i>
                        <span>List Workers</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- Logout Button -->
        <div class="p-4 border-t border-white border-opacity-10">
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center space-x-2 bg-red-500 bg-opacity-90 text-white p-2 rounded-lg hover:bg-red-600 transition transform hover:scale-[1.02]">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 ml-64 p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center space-x-2">
                <i class="fas fa-leaf text-green-600 text-3xl"></i>
                <h1 class="text-3xl font-bold text-green-700">SMART-WET</h1>
            </div>
            <h2 class="text-lg text-gray-600 mt-2">Smart System for Plantation Workforce Tracking</h2>
        </div>

        <!-- Content Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 mb-8">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500 mt-8">
            <p>© {{ date('Y') }} SMART-WET System. All rights reserved.</p>
        </div>
    </div>

    <!-- Notification Toast (hidden by default) -->
    <div id="notification-toast"
        class="fixed bottom-4 right-4 hidden bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <div class="flex items-center space-x-2">
            <i class="fas fa-check-circle"></i>
            <span id="notification-message">Operation successful</span>
        </div>
    </div>

    <script>
    // Simple notification system
    function showNotification(message, type = 'success') {
        const toast = document.getElementById('notification-toast');
        const messageEl = document.getElementById('notification-message');

        toast.className = `fixed bottom-4 right-4 flex items-center space-x-2 px-6 py-3 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white transform transition-all duration-300 translate-y-4 opacity-0`;

        messageEl.textContent = message;
        toast.classList.remove('hidden');

        setTimeout(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        }, 10);

        setTimeout(() => {
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => toast.classList.add('hidden'), 300);
        }, 3000);
    }
    </script>
</body>

</html>