@extends('layout')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-green-600 to-green-800 p-6">
        <h1 class="text-3xl font-bold text-white">About SMART-WET System</h1>
        <p class="mt-2 text-green-100">Smart Workforce Efficiency Tracker</p>
    </div>

    <div class="p-8">
        <div class="prose prose-green max-w-none">
            <p class="text-lg leading-relaxed text-gray-700">
                <strong class="text-green-700">SMART-WET</strong> (Smart Workforce Efficiency Tracker) is a plantation
                workforce tracking system designed to monitor worker activities using RFID technology. The system
                provides real-time tracking, attendance, and reporting features to improve productivity and
                transparency.
            </p>

            <div class="mt-8">
                <h2 class="text-xl font-semibold text-green-700 mb-4">Key Features</h2>
                <ul class="space-y-3">
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">RFID-based worker identification and tracking</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Checkpoint monitoring and activity logs</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Role-based access for Admin, Manager, and Worker</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">Comprehensive reports and analytics</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="text-gray-700">User-friendly dashboard and management tools</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="bg-gray-50 px-8 py-4 border-t border-gray-200">
        <p class="text-sm text-gray-600 text-center">
            Version 1.0.0 &mdash; &copy; {{ date('Y') }} SMART-WET System. All rights reserved.
        </p>
    </div>
</div>
@endsection