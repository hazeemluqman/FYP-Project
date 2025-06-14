<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART-WET Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --primary-color: #5A6C57;
        --primary-light: #6B7D68;
        --secondary-color: #F0F7F4;
        --accent-color: #4CAF82;
    }

    .sidebar-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-radius: 0.5rem;
        margin: 0 0.5rem;
    }

    .sidebar-item.active {
        background-color: rgba(255, 255, 255, 0.2);
        border-left: 4px solid white;
        font-weight: 500;
    }

    .sidebar-item:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.15);
        transform: translateX(4px);
    }

    .logo-transition {
        transition: all 0.3s ease;
    }

    .logo-transition:hover {
        transform: scale(1.05);
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .smooth-transition {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.5s ease-out forwards;
    }

    .notification-badge {
        font-size: 0.65rem;
        min-width: 1.25rem;
        height: 1.25rem;
    }

    /* Custom scrollbar for sidebar */
    .sidebar-scroll {
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }

    .sidebar-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }
    </style>
</head>

<body class="flex bg-gray-50 font-sans antialiased">
    <!-- Sidebar -->
    <div class="w-64 h-screen text-white flex flex-col justify-between fixed shadow-xl"
        style="background-color: var(--primary-color); z-index: 50;">
        <div class="flex flex-col h-full">
            <div class="p-4 mb-4 border-b border-white border-opacity-10">
                <div class="logo-transition flex items-center space-x-2 cursor-pointer">
                    <i class="fas fa-leaf text-2xl text-green-200"></i>
                    <h1 class="text-xl font-bold text-white">SMART-WET</h1>
                </div>
                <p class="text-xs opacity-80 mt-1 text-green-100">Plantation Workforce Tracking</p>
            </div>

            @php
            $role = Auth::user()->role ?? '';
            @endphp
            <nav class="flex-1 overflow-y-auto sidebar-scroll">
                <ul class="space-y-1 px-2">
                    <li class="sidebar-item {{ Request::is('checkpoints*') ? 'active' : '' }}">
                        <a href="{{ url('/checkpoints') }}" class="flex items-center p-3 space-x-3">
                            <i class="fas fa-tachometer-alt w-5 text-green-200"></i>
                            <span class="flex-1">Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('activities*') ? 'active' : '' }}">
                        <a href="{{ url('/activities') }}" class="flex items-center p-3 space-x-3">
                            <i class="fas fa-tasks w-5 text-green-200"></i>
                            <span class="flex-1">Worker Activity</span>
                        </a>
                    </li>
                    @if(in_array($role, ['admin', 'manager']))
                    <li class="sidebar-item {{ Request::is('rfids*') ? 'active' : '' }}">
                        <a href="{{ url('/rfids') }}" class="flex items-center p-3 space-x-3">
                            <i class="fas fa-users w-5 text-green-200"></i>
                            <span class="flex-1">List Workers</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('reports*') ? 'active' : '' }}">
                        <a href="{{ route('reports.index') }}" class="flex items-center p-3 space-x-3">
                            <i class="fas fa-chart-bar w-5 text-green-200"></i>
                            <span class="flex-1">Reports</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('accounts*') ? 'active' : '' }}">
                        <a href="{{ route('accounts.index') }}" class="flex items-center p-3 space-x-3">
                            <i class="fas fa-address-book w-5 text-green-200"></i>
                            <span class="flex-1">User Accounts</span>
                        </a>
                    </li>
                    <li class="sidebar-item {{ Request::is('about') ? 'active' : '' }}">
                        <a href="{{ route('about') }}" class="flex items-center p-3 space-x-3">
                            <i class="fas fa-info-circle w-5 text-green-200"></i>
                            <span class="flex-1">About System</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>

            <!-- User Profile & Logout -->
            <div class="p-4 border-t border-white border-opacity-10">
                <a href="{{ route('profile.index') }}"
                    class="flex items-center mb-4 p-2 rounded-lg hover:bg-white hover:bg-opacity-10 cursor-pointer smooth-transition">
                    <div class="h-10 w-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                        <i class="fas fa-user text-green-200"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs opacity-70">{{ Auth::user()->email }}</p>
                    </div>
                </a>
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center space-x-2 bg-red-500 hover:bg-red-600 text-white p-2 rounded-lg transition-all hover:shadow-md">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 ml-64 min-h-screen flex flex-col">
        <div class="flex-1 p-8 animate-fadeIn">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500 py-4 border-t border-gray-200">
            <p>© {{ date('Y') }} SMART-WET System. All rights reserved. v1.0.0</p>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="notification-toast"
        class="fixed bottom-4 right-4 hidden bg-green-500 text-white px-6 py-3 rounded-lg shadow-xl items-center space-x-3 z-50 max-w-xs">
        <div class="h-6 w-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check text-sm"></i>
        </div>
        <div class="flex-1 min-w-0">
            <span id="notification-message" class="truncate"></span>
        </div>
        <button onclick="hideNotification()" class="ml-4 opacity-70 hover:opacity-100 flex-shrink-0">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <script>
    // Enhanced notification system
    let notificationTimeout;

    function showNotification(message, type = 'success', duration = 3000) {
        const toast = document.getElementById('notification-toast');
        const messageEl = document.getElementById('notification-message');
        const iconEl = toast.querySelector('div i');

        // Clear any existing timeout
        clearTimeout(notificationTimeout);

        // Set toast style based on type
        const colors = {
            success: {
                bg: 'bg-green-500',
                icon: 'fa-check'
            },
            error: {
                bg: 'bg-red-500',
                icon: 'fa-exclamation-triangle'
            },
            info: {
                bg: 'bg-blue-500',
                icon: 'fa-info-circle'
            },
            warning: {
                bg: 'bg-yellow-500',
                icon: 'fa-exclamation'
            }
        };

        const {
            bg,
            icon
        } = colors[type] || colors.success;

        // Reset classes
        toast.className =
            `fixed bottom-4 right-4 ${bg} text-white px-6 py-3 rounded-lg shadow-xl flex items-center space-x-3 z-50 max-w-xs`;

        // Update content
        iconEl.className = `fas ${icon} text-sm`;
        messageEl.textContent = message;

        // Show with animation
        toast.classList.remove('hidden');
        toast.style.animation = 'fadeIn 0.3s ease-out forwards';

        // Auto-hide after duration
        notificationTimeout = setTimeout(() => {
            hideNotification();
        }, duration);
    }

    function hideNotification() {
        const toast = document.getElementById('notification-toast');
        toast.style.animation = 'fadeOut 0.3s ease-out forwards';
        setTimeout(() => {
            toast.classList.add('hidden');
            toast.style.animation = '';
        }, 300);
    }
    </script>
</body>

</html>