<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | SMART-WET</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
    .btn-primary {
        background-color: #5A6C57;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #4a5a47;
        transform: translateY(-1px);
    }

    .focus-primary:focus {
        ring: 2px;
        ring-color: #5A6C57;
        border-color: #5A6C57;
    }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- SMART-WET Header -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center space-x-2">
                <i class="fas fa-leaf text-3xl" style="color: #5A6C57;"></i>
                <h1 class="text-3xl font-bold text-gray-800">SMART-WET</h1>
            </div>
            <h2 class="text-lg text-gray-600 mt-1">Plantation Workforce Tracking</h2>
        </div>

        <!-- Password Reset Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <div class="px-6 py-4" style="background-color: #5A6C57;">
                <h2 class="text-xl font-semibold text-white">Reset Password</h2>
            </div>

            <div class="p-6">
                @if (session('status'))
                <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded-lg">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 @error('email') border-red-500 @enderror"
                            required autocomplete="email" autofocus>

                        @error('email')
                        <span class="text-sm text-red-600 mt-1 block">
                            {{ $message }}
                        </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-3 px-4 btn-primary text-white font-medium rounded-lg transition duration-200 shadow hover:shadow-md">
                        Send Password Reset Link
                    </button>

                    <!-- Login Link -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('login') }}"
                            class="text-sm text-green-600 hover:text-green-800 transition duration-200">
                            Remember your password? Login here
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-sm text-gray-500 mt-6">
            <p>© {{ date('Y') }} SMART-WET System. All rights reserved.</p>
        </div>
    </div>
</body>

</html>