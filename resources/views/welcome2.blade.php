<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RFID Checkpoint System | SMART-WET</title>
    <!-- Include Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    @keyframes wave {
        0% {
            transform: rotate(0deg);
        }

        10% {
            transform: rotate(14deg);
        }

        20% {
            transform: rotate(-8deg);
        }

        30% {
            transform: rotate(14deg);
        }

        40% {
            transform: rotate(-4deg);
        }

        50% {
            transform: rotate(10deg);
        }

        60% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    .wave-animation {
        animation: wave 2.5s infinite;
        transform-origin: 70% 70%;
        display: inline-block;
    }

    .btn-primary {
        background-color: #5A6C57;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #4a5a47;
        transform: translateY(-1px);
    }

    .btn-outline {
        border-color: #5A6C57;
        color: #5A6C57;
        transition: all 0.2s ease;
    }

    .btn-outline:hover {
        background-color: rgba(90, 108, 87, 0.1);
    }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-md w-full border border-gray-200">
        <div class="flex flex-col items-center">
            <!-- Logo with SMART-WET branding -->
            <div class="mb-6 flex flex-col items-center">
                <div class="flex items-center space-x-2 mb-2">
                    <i class="fas fa-leaf text-3xl" style="color: #5A6C57;"></i>
                    <h1 class="text-2xl font-bold">SMART-WET</h1>
                </div>
                <p class="text-xs text-gray-500">Plantation Workforce Tracking</p>
            </div>

            <!-- RFID System Icon -->
            <div class="relative mb-6">
                <div class="h-20 w-20" style="background-color: #5A6C57;"
                    class="rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-id-card-alt text-white text-3xl"></i>
                </div>
                <div
                    class="absolute -bottom-2 -right-2 bg-yellow-400 rounded-full h-8 w-8 flex items-center justify-center shadow-md">
                    <i class="fas fa-bolt text-white text-sm"></i>
                </div>
            </div>

            <h1 class="text-2xl font-bold mb-2 text-gray-800">Checkpoint System</h1>
            <p class="text-gray-500 mb-6 text-center">Track and manage RFID access points</p>

            @if (Route::has('login'))
            <nav class="flex flex-col gap-4 w-full">
                @auth
                <a href="{{ url('/checkpoints') }}"
                    class="btn-primary text-white py-3 px-6 rounded-lg font-medium flex items-center justify-center shadow hover:shadow-md">
                    <span>Go to Dashboard</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="btn-primary text-white py-3 px-6 rounded-lg font-medium flex items-center justify-center shadow hover:shadow-md">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    <span>Log in</span>
                </a>

                @if (Route::has('register'))
                <a href="{{ route('register') }}"
                    class="btn-outline border py-3 px-6 rounded-lg font-medium flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    <span>Create Account</span>
                </a>
                @endif
                @endauth
            </nav>
            @endif

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">Need help? <a href="#"
                        class="text-green-600 hover:text-green-800">Contact support</a></p>
                <p class="text-xs text-gray-400 mt-2">System version 1.0.0</p>
            </div>
        </div>
    </div>
</body>

</html>