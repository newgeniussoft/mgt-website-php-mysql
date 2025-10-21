<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page_title ?? 'Edit Page' }} - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Summernote Editor -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
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
                    <a href="/admin/pages/preview?id={{ $page->id }}" 
                       class="text-blue-600 hover:text-blue-900" 
                       target="_blank">
                        <i class="fas fa-eye mr-1"></i>
                        Preview
                    </a>
                    <a href="/admin/pages" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to Pages
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Edit Page: {{ $page->title }}</h2>
            <p class="text-gray-600">Update page content and settings</p>
        </div>

        <!-- Error Messages -->
        @php
            session_start();
            $error = $_SESSION['error_message'] ?? '';
            unset($_SESSION['error_message']);
        @endphp

        @if($error)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md mb-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span>{{ $error }}</span>
                </div>
            </div>
        @endif

        <form method="POST" action="/admin/pages/update" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="csrf_token" value="{{ $csrf_token }}">
            <input type="hidden" name="id" value="{{ $page->id }}">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-file-alt mr-2"></i>
                                Basic Information
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Title *
                                    </label>
                                    <input 
                                        type="text" 
                                        id="title" 
                                        name="title" 
                                        required 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Enter page title"
                                        value="{{ $_POST['title'] ?? $page->title }}"
                                        onkeyup="generateSlug()"
                                    >
                                </div>
                                
                                <div>
                                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                        URL Slug
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                            /
                                        </span>
                                        <input 
                                            type="text" 
                                            id="slug" 
                                            name="slug" 
                                            class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="auto-generated-from-title"
                                            value="{{ $_POST['slug'] ?? $page->slug }}"
                                        >
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                                </div>
                                
                                <div>
                                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                                        Excerpt
                                    </label>
                                    <textarea 
                                        id="excerpt" 
                                        name="excerpt" 
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Brief description of the page"
                                    >{{ $_POST['excerpt'] ?? $page->excerpt }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-edit mr-2"></i>
                                Content
                            </h3>
                            
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                    Page Content
                                </label>
                                <textarea 
                                    id="content" 
                                    name="content" 
                                    class="w-full"
                                    style="height: 400px;"
                                >{{ $_POST['content'] ?? $page->content }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-search mr-2"></i>
                                SEO Settings
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Meta Title
                                    </label>
                                    <input 
                                        type="text" 
                                        id="meta_title" 
                                        name="meta_title" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="SEO title for search engines"
                                        value="{{ $_POST['meta_title'] ?? $page->meta_title }}"
                                    >
                                </div>
                                
                                <div>
                                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Meta Description
                                    </label>
                                    <textarea 
                                        id="meta_description" 
                                        name="meta_description" 
                                        rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="SEO description for search engines (160 characters max)"
                                    >{{ $_POST['meta_description'] ?? $page->meta_description }}</textarea>
                                </div>
                                
                                <div>
                                    <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                                        Meta Keywords
                                    </label>
                                    <input 
                                        type="text" 
                                        id="meta_keywords" 
                                        name="meta_keywords" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="keyword1, keyword2, keyword3"
                                        value="{{ $_POST['meta_keywords'] ?? $page->meta_keywords }}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Page Info -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-info-circle mr-2"></i>
                                Page Information
                            </h3>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Created:</span>
                                    <span class="font-medium">{{ date('M d, Y', strtotime($page->created_at)) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Last Updated:</span>
                                    <span class="font-medium">{{ date('M d, Y H:i', strtotime($page->updated_at)) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Author:</span>
                                    <span class="font-medium">{{ $page->author_name ?? 'Unknown' }}</span>
                                </div>
                                @if($page->published_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Published:</span>
                                        <span class="font-medium">{{ date('M d, Y H:i', strtotime($page->published_at)) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Publish Settings -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-cog mr-2"></i>
                                Publish Settings
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="language" class="block text-sm font-medium text-gray-700 mb-2">
                                        Language
                                    </label>
                                    <select 
                                        id="language" 
                                        name="language"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        <option value="en" {{ ($_POST['language'] ?? $page->language ?? 'en') === 'en' ? 'selected' : '' }}>English</option>
                                        <option value="es" {{ ($_POST['language'] ?? $page->language ?? '') === 'es' ? 'selected' : '' }}>Español</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="translation_group" class="block text-sm font-medium text-gray-700 mb-2">
                                        Translation Group
                                    </label>
                                    <input 
                                        type="text" 
                                        id="translation_group" 
                                        name="translation_group" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Leave empty to auto-generate"
                                        value="{{ $_POST['translation_group'] ?? $page->translation_group ?? '' }}"
                                    >
                                    <p class="mt-1 text-sm text-gray-500">Used to link translations of the same page</p>
                                </div>
                                
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status
                                    </label>
                                    <select 
                                        id="status" 
                                        name="status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        <option value="draft" {{ ($_POST['status'] ?? $page->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ ($_POST['status'] ?? $page->status) === 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="private" {{ ($_POST['status'] ?? $page->status) === 'private' ? 'selected' : '' }}>Private</option>
                                        <option value="archived" {{ ($_POST['status'] ?? $page->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="template" class="block text-sm font-medium text-gray-700 mb-2">
                                        Template
                                    </label>
                                    <select 
                                        id="template" 
                                        name="template"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        @foreach($templates as $key => $name)
                                            <option value="{{ $key }}" {{ ($_POST['template'] ?? $page->template) === $key ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="layout_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Custom Layout
                                    </label>
                                    <select 
                                        id="layout_id" 
                                        name="layout_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                        <option value="">Use Default Template</option>
                                        @foreach($layouts as $layout)
                                            <option value="{{ $layout['id'] }}" {{ ($_POST['layout_id'] ?? $page->layout_id) == $layout['id'] ? 'selected' : '' }}>
                                                {{ $layout['name'] }}{{ $layout['is_system'] ? ' (System)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">Choose a custom layout or leave empty to use the template above</p>
                                </div>
                                
                                <div class="form-check">
                                    <input 
                                        type="checkbox" 
                                        id="use_sections" 
                                        name="use_sections" 
                                        value="1"
                                        class="form-check-input"
                                        {{ ($_POST['use_sections'] ?? $page->use_sections) ? 'checked' : '' }}
                                    >
                                    <label for="use_sections" class="form-check-label text-sm text-gray-700">
                                        Enable modular sections for this page
                                    </label>
                                    <p class="mt-1 text-sm text-gray-500">Allow adding and reordering content sections</p>
                                </div>
                                
                                <div>
                                    <label for="menu_order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Menu Order
                                    </label>
                                    <input 
                                        type="number" 
                                        id="menu_order" 
                                        name="menu_order" 
                                        min="0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        value="{{ $_POST['menu_order'] ?? $page->menu_order }}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Page Options -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-sliders-h mr-2"></i>
                                Page Options
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        id="is_homepage" 
                                        name="is_homepage" 
                                        value="1"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        {{ (isset($_POST['is_homepage']) ? $_POST['is_homepage'] : $page->is_homepage) ? 'checked' : '' }}
                                    >
                                    <label for="is_homepage" class="ml-2 block text-sm text-gray-700">
                                        Set as Homepage
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        id="show_in_menu" 
                                        name="show_in_menu" 
                                        value="1"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                        {{ (isset($_POST['show_in_menu']) ? $_POST['show_in_menu'] : $page->show_in_menu) ? 'checked' : '' }}
                                    >
                                    <label for="show_in_menu" class="ml-2 block text-sm text-gray-700">
                                        Show in Menu
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                <i class="fas fa-image mr-2"></i>
                                Featured Image
                            </h3>
                            
                            @if($page->featured_image)
                                <div class="mb-4">
                                    <img src="/uploads/pages/{{ $page->featured_image }}" 
                                         alt="Current featured image" 
                                         class="w-full h-32 object-cover rounded-md">
                                    <p class="mt-2 text-sm text-gray-600">Current image</p>
                                </div>
                            @endif
                            
                            <div>
                                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $page->featured_image ? 'Replace Image' : 'Upload Image' }}
                                </label>
                                <input 
                                    type="file" 
                                    id="featured_image" 
                                    name="featured_image" 
                                    accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                >
                                <p class="mt-1 text-sm text-gray-500">Recommended size: 1200x630px</p>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-lg shadow">
                        <div class="p-6">
                            <div class="space-y-3">
                                <button 
                                    type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200"
                                >
                                    <i class="fas fa-save mr-2"></i>
                                    Update Page
                                </button>
                                
                                <a href="/admin/pages" 
                                   class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-md transition duration-200 block text-center">
                                    <i class="fas fa-times mr-2"></i>
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('#content').summernote({
                height: 400,
                placeholder: 'Enter your page content here...',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica Neue', 'Helvetica', 'Impact', 'Lucida Grande', 'Tahoma', 'Times New Roman', 'Verdana'],
                fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '36', '48'],
                callbacks: {
                    onImageUpload: function(files) {
                        // Handle image upload if needed
                        for (let i = 0; i < files.length; i++) {
                            uploadImage(files[i]);
                        }
                    }
                }
            });

            // Character counter for meta description
            const metaDescription = document.getElementById('meta_description');
            if (metaDescription) {
                metaDescription.addEventListener('input', function() {
                    const length = this.value.length;
                    const maxLength = 160;
                    
                    // Create or update character counter
                    let counter = document.getElementById('meta-desc-counter');
                    if (!counter) {
                        counter = document.createElement('p');
                        counter.id = 'meta-desc-counter';
                        counter.className = 'mt-1 text-sm';
                        this.parentNode.appendChild(counter);
                    }
                    
                    counter.textContent = `${length}/${maxLength} characters`;
                    counter.className = `mt-1 text-sm ${length > maxLength ? 'text-red-500' : 'text-gray-500'}`;
                });
                
                // Trigger initial count
                metaDescription.dispatchEvent(new Event('input'));
            }
        });

        // Generate slug from title (only if slug is empty)
        function generateSlug() {
            const slugField = document.getElementById('slug');
            if (slugField.value.trim() === '') {
                const title = document.getElementById('title').value;
                const slug = title
                    .toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim('-');
                
                slugField.value = slug;
            }
        }

        // Image upload function for Summernote
        function uploadImage(file) {
            const data = new FormData();
            data.append("file", file);
            
            // You can implement image upload to server here
            // For now, we'll just insert a placeholder
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#content').summernote('insertImage', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    </script>
</body>
</html>
