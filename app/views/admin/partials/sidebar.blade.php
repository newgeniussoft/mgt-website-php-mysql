   <div class="w-64 bg-white shadow-lg min-h-screen">
            <div class="py-4">
                <nav class="mt-4">
                    <a href="/admin/dashboard" class="flex items-center px-4 py-2 {{ page_admin() === 'dashboard' ? 'text-gray-700 bg-blue-50 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-700' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="/admin/pages" class="flex items-center px-4 py-2 {{ page_admin() === 'pages' ? 'text-gray-700 bg-blue-50 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-700' }}">
                        <i class="fas fa-file-alt mr-3"></i>
                        Pages
                    </a>
                    <a href="/admin/media" class="flex items-center px-4 py-2 {{ page_admin() === 'media' ? 'text-gray-700 bg-blue-50 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-700' }}">
                        <i class="fas fa-images mr-3"></i>
                        Media
                    </a>
                    <a href="/admin/layouts" class="flex items-center px-4 py-2 {{ page_admin() === 'layout' ? 'text-gray-700 bg-blue-50 border-r-4 border-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-700' }}">
                        <i class="fas fa-columns mr-3"></i>
                        Layout
                    </a>
                </nav>
            </div>
        </div>