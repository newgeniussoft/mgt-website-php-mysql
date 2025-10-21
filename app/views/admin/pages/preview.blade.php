<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $page->meta_title ?: $page->title }} - Preview</title>
    <meta name="description" content="{{ $page->meta_description ?: $page->excerpt }}">
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .preview-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .content-area {
            line-height: 1.7;
        }
        .content-area h1, .content-area h2, .content-area h3, .content-area h4, .content-area h5, .content-area h6 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        .content-area h1 { font-size: 2.25rem; }
        .content-area h2 { font-size: 1.875rem; }
        .content-area h3 { font-size: 1.5rem; }
        .content-area h4 { font-size: 1.25rem; }
        .content-area h5 { font-size: 1.125rem; }
        .content-area h6 { font-size: 1rem; }
        .content-area p { margin-bottom: 1rem; }
        .content-area ul, .content-area ol { margin-bottom: 1rem; padding-left: 2rem; }
        .content-area li { margin-bottom: 0.5rem; }
        .content-area img { max-width: 100%; height: auto; margin: 1rem 0; border-radius: 0.5rem; }
        .content-area blockquote { 
            border-left: 4px solid #e5e7eb; 
            padding-left: 1rem; 
            margin: 1rem 0; 
            font-style: italic; 
            color: #6b7280; 
        }
        .content-area table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 1rem 0; 
        }
        .content-area th, .content-area td { 
            border: 1px solid #e5e7eb; 
            padding: 0.75rem; 
            text-align: left; 
        }
        .content-area th { 
            background-color: #f9fafb; 
            font-weight: 600; 
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Preview Notice -->
    <div class="bg-yellow-100 border-b border-yellow-200">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-eye text-yellow-600 mr-2"></i>
                    <span class="text-yellow-800 font-medium">Preview Mode</span>
                    <span class="ml-2 text-yellow-700">- This is how your page will look to visitors</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-yellow-700">
                        Status: 
                        <span class="font-medium capitalize">{{ $page->status }}</span>
                    </span>
                    <a href="/admin/pages/edit?id={{ $page->id }}" 
                       class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm transition duration-200">
                        <i class="fas fa-edit mr-1"></i>
                        Edit Page
                    </a>
                    <a href="/admin/pages" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition duration-200">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to Pages
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Header -->
    @if($page->featured_image)
        <div class="preview-header relative h-96 flex items-center justify-center text-white">
            <div class="absolute inset-0 bg-black opacity-50"></div>
            <img src="/uploads/pages/{{ $page->featured_image }}" 
                 alt="{{ $page->title }}" 
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="relative z-10 text-center">
                <h1 class="text-5xl font-bold mb-4">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="text-xl opacity-90 max-w-2xl mx-auto">{{ $page->excerpt }}</p>
                @endif
            </div>
        </div>
    @else
        <div class="preview-header py-20 text-white text-center">
            <div class="max-w-4xl mx-auto px-4">
                <h1 class="text-5xl font-bold mb-4">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="text-xl opacity-90">{{ $page->excerpt }}</p>
                @endif
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <div class="max-w-4xl mx-auto px-4 py-12">
        @if($page->content)
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="content-area prose prose-lg max-w-none">
                    {!! $page->content !!}
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <i class="fas fa-file-alt text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Content</h3>
                <p class="text-gray-600">This page doesn't have any content yet.</p>
                <a href="/admin/pages/edit?id={{ $page->id }}" 
                   class="inline-block mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-200">
                    <i class="fas fa-edit mr-2"></i>
                    Add Content
                </a>
            </div>
        @endif
    </div>

    <!-- Page Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-4xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Page Information</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><strong>Template:</strong> {{ ucfirst($page->template) }}</li>
                        <li><strong>Created:</strong> {{ date('M d, Y', strtotime($page->created_at)) }}</li>
                        <li><strong>Updated:</strong> {{ date('M d, Y', strtotime($page->updated_at)) }}</li>
                        @if($page->published_at)
                            <li><strong>Published:</strong> {{ date('M d, Y', strtotime($page->published_at)) }}</li>
                        @endif
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Settings</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li>
                            <strong>Status:</strong> 
                            <span class="capitalize">{{ $page->status }}</span>
                        </li>
                        <li>
                            <strong>Homepage:</strong> 
                            {{ $page->is_homepage ? 'Yes' : 'No' }}
                        </li>
                        <li>
                            <strong>Show in Menu:</strong> 
                            {{ $page->show_in_menu ? 'Yes' : 'No' }}
                        </li>
                        <li>
                            <strong>Menu Order:</strong> 
                            {{ $page->menu_order }}
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">SEO Information</h3>
                    <ul class="space-y-2 text-sm text-gray-300">
                        @if($page->meta_title)
                            <li><strong>Meta Title:</strong> {{ $page->meta_title }}</li>
                        @endif
                        @if($page->meta_description)
                            <li><strong>Meta Description:</strong> {{ substr($page->meta_description, 0, 100) }}{{ strlen($page->meta_description) > 100 ? '...' : '' }}</li>
                        @endif
                        @if($page->meta_keywords)
                            <li><strong>Keywords:</strong> {{ $page->meta_keywords }}</li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} Your Website. All rights reserved.</p>
                <p class="mt-2">
                    <i class="fas fa-eye mr-1"></i>
                    This is a preview of your page. Only administrators can see this notice.
                </p>
            </div>
        </div>
    </footer>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 right-6 space-y-3">
        <a href="/admin/pages/edit?id={{ $page->id }}" 
           class="block bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-full shadow-lg transition duration-200"
           title="Edit Page">
            <i class="fas fa-edit"></i>
        </a>
        <a href="/admin/pages" 
           class="block bg-gray-600 hover:bg-gray-700 text-white p-3 rounded-full shadow-lg transition duration-200"
           title="Back to Pages">
            <i class="fas fa-list"></i>
        </a>
    </div>

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add loading animation for images
        document.querySelectorAll('.content-area img').forEach(img => {
            img.addEventListener('load', function() {
                this.style.opacity = '1';
            });
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.3s ease';
        });
    </script>
</body>
</html>
