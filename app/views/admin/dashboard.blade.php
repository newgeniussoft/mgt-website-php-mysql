 @extends('admin.layout')

 @section('title', 'Dashboard')
 @section('content')
 <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->username }}!</h2>
                <p class="text-gray-600">Here's what's happening with your website today.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900">1</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <i class="fas fa-file-alt text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pages</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-yellow-100 rounded-lg">
                            <i class="fas fa-images text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Media Files</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <i class="fas fa-eye text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Page Views</p>
                            <p class="text-2xl font-bold text-gray-900">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="/admin/pages/create" class="flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-200">
                                <i class="fas fa-plus-circle text-blue-600 mr-3"></i>
                                <span class="text-blue-700 font-medium">Create New Page</span>
                            </a>
                            <a href="/admin/media/upload" class="flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition duration-200">
                                <i class="fas fa-upload text-green-600 mr-3"></i>
                                <span class="text-green-700 font-medium">Upload Media</span>
                            </a>
                            <a href="/admin/users/create" class="flex items-center p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition duration-200">
                                <i class="fas fa-user-plus text-purple-600 mr-3"></i>
                                <span class="text-purple-700 font-medium">Add New User</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">System Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">PHP Version</span>
                                <span class="font-medium">{{ phpversion() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Server</span>
                                <span class="font-medium">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Last Login</span>
                                <span class="font-medium">{{ date('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 @endsection
