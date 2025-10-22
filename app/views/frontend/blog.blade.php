@extends('frontend.page')

@section('additional_content')
<!-- Blog Content -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Article Meta -->
        <div class="mb-8">
            <div class="flex items-center text-sm text-gray-500 mb-4">
                <span>Published on {{ date('F j, Y', strtotime($page->published_at ?: $page->created_at)) }}</span>
                @if($page->author_name)
                    <span class="mx-2">•</span>
                    <span>By {{ $page->author_name }}</span>
                @endif
                <span class="mx-2">•</span>
                <span>5 min read</span>
            </div>
            
            <!-- Tags -->
            @if($page->meta_keywords)
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $page->meta_keywords) as $tag)
                        <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                            {{ trim($tag) }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
        
        <!-- Article Content -->
        @if($page->content)
            <div class="prose prose-lg max-w-none">
                {!! $page->content !!}
            </div>
        @endif
        
        <!-- Share Buttons -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <h3 class="text-lg font-semibold mb-4">Share this article</h3>
            <div class="flex space-x-4">
                <a href="https://twitter.com/intent/tweet?url={{ urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) }}&text={{ urlencode($page->title) }}" 
                   target="_blank" 
                   class="bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition duration-200">
                    <i class="fab fa-twitter mr-2"></i>Twitter
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) }}" 
                   target="_blank" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    <i class="fab fa-facebook-f mr-2"></i>Facebook
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) }}" 
                   target="_blank" 
                   class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition duration-200">
                    <i class="fab fa-linkedin-in mr-2"></i>LinkedIn
                </a>
                <button onclick="copyToClipboard()" 
                        class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-200">
                    <i class="fas fa-link mr-2"></i>Copy Link
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Related Articles -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Related Articles</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Placeholder for related articles -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">Sample Article Title</h3>
                    <p class="text-gray-600 mb-4">Brief excerpt of the article content goes here...</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <span>March 15, 2024</span>
                        <span class="mx-2">•</span>
                        <span>3 min read</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-green-400 to-blue-500"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">Another Article Title</h3>
                    <p class="text-gray-600 mb-4">Brief excerpt of the article content goes here...</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <span>March 10, 2024</span>
                        <span class="mx-2">•</span>
                        <span>4 min read</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-purple-400 to-pink-500"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">Third Article Title</h3>
                    <p class="text-gray-600 mb-4">Brief excerpt of the article content goes here...</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <span>March 5, 2024</span>
                        <span class="mx-2">•</span>
                        <span>6 min read</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Signup -->
<section class="py-16 bg-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Stay Updated</h2>
        <p class="text-xl opacity-90 mb-8">Subscribe to our newsletter for the latest articles and updates.</p>
        
        <form class="max-w-md mx-auto flex">
            <input 
                type="email" 
                placeholder="Enter your email" 
                class="flex-1 px-4 py-3 rounded-l-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-300"
            >
            <button 
                type="submit" 
                class="bg-blue-800 hover:bg-blue-900 px-6 py-3 rounded-r-lg font-semibold transition duration-200"
            >
                Subscribe
            </button>
        </form>
    </div>
</section>

<script>
function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
        button.classList.add('bg-green-600');
        button.classList.remove('bg-gray-600');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-gray-600');
        }, 2000);
    });
}
</script>
@endsection
