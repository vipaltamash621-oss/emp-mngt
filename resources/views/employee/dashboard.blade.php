<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Welcome Card -->
            <div class="md:col-span-3 bg-white rounded-lg shadow p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-gray-600">Employee Dashboard</p>
            </div>

            <!-- Profile Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-indigo-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-600 text-sm">{{ auth()->user()->email }}</p>
                    <p class="text-gray-500 text-sm mt-2">{{ auth()->user()->phone ?? 'N/A' }}</p>
                    <a href="{{ route('employee.profile') }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-700 font-semibold">View Profile</a>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Employee ID:</span>
                        <span class="font-semibold text-gray-800">{{ auth()->user()->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Role:</span>
                        <span class="font-semibold text-gray-800">Employee</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-green-800 bg-green-200 rounded-full">
                            {{ auth()->user()->status == 1 ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Attendance -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Attendance</h3>
                <div class="text-center">
                    <p class="text-3xl font-bold text-indigo-600 mb-2">-</p>
                    <p class="text-gray-600 text-sm mb-4">No attendance records yet</p>
                    <a href="{{ route('employee.attendance') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm">View Attendance</a>
                </div>
            </div>
        </div>

        <!-- Menu Options -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Menu</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('employee.profile') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-lg transition">
                    <h3 class="font-semibold text-gray-800 mb-2">👤 My Profile</h3>
                    <p class="text-gray-600 text-sm">View and update your profile information</p>
                </a>
                <a href="{{ route('employee.attendance') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-indigo-500 hover:shadow-lg transition">
                    <h3 class="font-semibold text-gray-800 mb-2">📋 Attendance</h3>
                    <p class="text-gray-600 text-sm">Check your attendance records</p>
                </a>
                <div class="block p-4 border border-gray-200 rounded-lg opacity-50">
                    <h3 class="font-semibold text-gray-800 mb-2">📝 Leave Requests</h3>
                    <p class="text-gray-600 text-sm">Coming soon</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
