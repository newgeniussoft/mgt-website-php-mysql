<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? 'Page Management' }} - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-900">
                            <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i>
                            Admin Panel
                        </h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="/admin/dashboard" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to Dashboard
                    </a>
                    <div class="relative">
                        <button class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button">
                            <div class="h-8 w-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="ml-2 text-gray-700">Admin</span>
                            <i class="fas fa-chevron-down ml-1 text-gray-400"></i>
                        </button>
                        
                        <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" id="user-menu">
                            <a href="/admin/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user-cog mr-2"></i>Profile
                            </a>
                            <a href="/admin/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i>Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Page Management</h2>
                    <p class="text-gray-600">Create and manage your website pages</p>
                </div>
                <a href="/admin/pages/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Create New Page
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @php
            $success = $_SESSION['success_message'] ?? '';
            $error = $_SESSION['error_message'] ?? '';
            unset($_SESSION['success_message'], $_SESSION['error_message']);
        @endphp

        @if($success)
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md mb-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span>{{ $success }}</span>
                </div>
            </div>
        @endif

        @if($error)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ $error }}</span>
                </div>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow mb-6">
            <div class="p-6">
                <form method="GET" action="/admin/pages" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-64">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input 
                            type="text" 
                            id="search" 
                            name="search" 
                            value="{{ $search }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Search pages by title or content..."
                        >
                    </div>
                    
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Language</label>
                        <select 
                            id="language" 
                            name="language"
                            class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">All Languages</option>
                            <option value="en" {{ $language === 'en' ? 'selected' : '' }}>English</option>
                            <option value="es" {{ $language === 'es' ? 'selected' : '' }}>Español</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select 
                            id="status" 
                            name="status"
                            class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">All Status</option>
                            <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="private" {{ $status === 'private' ? 'selected' : '' }}>Private</option>
                            <option value="archived" {{ $status === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-200">
                            <i class="fas fa-search mr-2"></i>
                            Filter
                        </button>
                    </div>
                    @php
                        $hasFilters = $search || $status || $language;
                    @endphp
                    
                    @if($hasFilters)
                        <div class="flex items-end">
                            <a href="/admin/pages" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md transition duration-200">
                                <i class="fas fa-times mr-2"></i>
                                Clear
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Pages Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            @php
                $empty_page = empty($pages)
            @endphp
            @if($empty_page)
                <div class="p-8 text-center">
                    <i class="fas fa-file-alt text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No pages found</h3>
                    <p class="text-gray-600 mb-4">Get started by creating your first page.</p>
                    <a href="/admin/pages/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Page
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Language
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Author
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Updated
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pages as $page)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $page['title'] }}
                                                    @if($page['is_homepage'])
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <i class="fas fa-home mr-1"></i>
                                                            Homepage
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    /{{ $page['slug'] }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $languageColors = [
                                                'en' => 'bg-blue-100 text-blue-800',
                                                'es' => 'bg-orange-100 text-orange-800'
                                            ];
                                            $languageNames = [
                                                'en' => 'English',
                                                'es' => 'Español'
                                            ];
                                            $languageFlags = [
                                                'en' => '🇺🇸',
                                                'es' => '🇪🇸'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $languageColors[$page['language'] ?? 'en'] }}">
                                            <span class="mr-1">{{ $languageFlags[$page['language'] ?? 'en'] }}</span>
                                            {{ $languageNames[$page['language'] ?? 'en'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-gray-100 text-gray-800',
                                                'published' => 'bg-green-100 text-green-800',
                                                'private' => 'bg-blue-100 text-blue-800',
                                                'archived' => 'bg-red-100 text-red-800'
                                            ];
                                            $statusIcons = [
                                                'draft' => 'fas fa-edit',
                                                'published' => 'fas fa-eye',
                                                'private' => 'fas fa-lock',
                                                'archived' => 'fas fa-archive'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$page['status']] }}">
                                            <i class="{{ $statusIcons[$page['status']] }} mr-1"></i>
                                            {{ ucfirst($page['status']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $page['author_name'] ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ date('M d, Y', strtotime($page['updated_at'])) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="/admin/pages/preview?id={{ $page['id'] }}" 
                                               class="text-blue-600 hover:text-blue-900" 
                                               title="Preview">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/admin/pages/edit?id={{ $page['id'] }}" 
                                               class="text-indigo-600 hover:text-indigo-900" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deletePage({{ $page['id'] }}, '{{ addslashes($page['title']) }}')" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($totalPages > 1)
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            @if($currentPage > 1)
                                <a href="/admin/pages?page={{ $currentPage - 1 }}&search={{ $search }}&status={{ $status }}&language={{ $language }}" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif
                            @if($currentPage < $totalPages)
                                <a href="/admin/pages?page={{ $currentPage + 1 }}&search={{ $search }}&status={{ $status }}&language={{ $language }}" 
                                   class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Next
                                </a>
                            @endif
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Page <span class="font-medium">{{ $currentPage }}</span> of <span class="font-medium">{{ $totalPages }}</span>
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    @if($currentPage > 1)
                                        <a href="/admin/pages?page={{ $currentPage - 1 }}&search={{ $search }}&status={{ $status }}&language={{ $language }}" 
                                           class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    @endif
                                    @php
                                     $maxPages = min($totalPages, $currentPage + 2);
                                     $minPages = max(1, $currentPage - 2);
                                    @endphp
                                    @for($i = $minPages; $i <= $maxPages; $i++)
                                        <a href="/admin/pages?page={{ $i }}&search={{ $search }}&status={{ $status }}&language={{ $language }}" 
                                           class="relative inline-flex items-center px-4 py-2 border text-sm font-medium {{ $i === $currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' }}">
                                            {{ $i }}
                                        </a>
                                    @endfor
                                    
                                    @if($currentPage < $totalPages)
                                        <a href="/admin/pages?page={{ $currentPage + 1 }}&search={{ $search }}&status={{ $status }}&language={{ $language }}" 
                                           class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    @endif
                                </nav>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Delete Page</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete "<span id="pageTitle"></span>"? This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="deleteForm" method="POST" action="/admin/pages/delete" class="inline">
                        <input type="hidden" name="csrf_token" value="{{ $csrf_token }}">
                        <input type="hidden" name="id" id="deletePageId">
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Delete
                        </button>
                    </form>
                    <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle user menu
        document.getElementById('user-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('user-menu-button');
            const menu = document.getElementById('user-menu');
            
            if (!button.contains(event.target) && !menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // Delete page functionality
        function deletePage(id, title) {
            document.getElementById('deletePageId').value = id;
            document.getElementById('pageTitle').textContent = title;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('deleteModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
