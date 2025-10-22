@extends('frontend.page')

@section('additional_styles')
<style>
    .gallery-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    .gallery-item:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
    }
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        max-height: 80%;
        object-fit: contain;
    }
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover {
        color: #bbb;
    }
</style>
@endsection

@section('additional_content')
<!-- Gallery Filters -->
<section class="py-8 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-wrap justify-center gap-4">
            <button onclick="filterGallery('all')" class="filter-btn active bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                All
            </button>
            <button onclick="filterGallery('web')" class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition duration-200">
                Web Design
            </button>
            <button onclick="filterGallery('mobile')" class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition duration-200">
                Mobile Apps
            </button>
            <button onclick="filterGallery('branding')" class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition duration-200">
                Branding
            </button>
            <button onclick="filterGallery('photography')" class="filter-btn bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition duration-200">
                Photography
            </button>
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="gallery-grid">
            <!-- Web Design Items -->
            <div class="gallery-item" data-category="web" onclick="openModal(this)">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                        <i class="fas fa-laptop-code text-white text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">E-commerce Website</h3>
                        <p class="text-gray-600 text-sm">Modern online store with payment integration</p>
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mt-2">Web Design</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="web" onclick="openModal(this)">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                        <i class="fas fa-globe text-white text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Corporate Website</h3>
                        <p class="text-gray-600 text-sm">Professional business website with CMS</p>
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mt-2">Web Design</span>
                    </div>
                </div>
            </div>
            
            <!-- Mobile App Items -->
            <div class="gallery-item" data-category="mobile" onclick="openModal(this)">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
                        <i class="fas fa-mobile-alt text-white text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Fitness Tracking App</h3>
                        <p class="text-gray-600 text-sm">iOS and Android fitness application</p>
                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mt-2">Mobile Apps</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="mobile" onclick="openModal(this)">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-white text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Shopping App</h3>
                        <p class="text-gray-600 text-sm">Cross-platform e-commerce mobile app</p>
                        <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded mt-2">Mobile Apps</span>
                    </div>
                </div>
            </div>
            
            <!-- Branding Items -->
            <div class="gallery-item" data-category="branding" onclick="openModal(this)">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 bg-gradient-to-br from-red-400 to-pink-500 flex items-center justify-center">
                        <i class="fas fa-palette text-white text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Brand Identity</h3>
                        <p class="text-gray-600 text-sm">Complete brand identity design package</p>
                        <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded mt-2">Branding</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="branding" onclick="openModal(this)">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
                        <i class="fas fa-trademark text-white text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Logo Design</h3>
                        <p class="text-gray-600 text-sm">Modern logo design with brand guidelines</p>
                        <span class="inline-block bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded mt-2">Branding</span>
                    </div>
                </div>
            </div>
            
            <!-- Photography Items -->
            <div class="gallery-item" data-category="photography" onclick="openModal(this)">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 bg-gradient-to-br from-teal-400 to-blue-500 flex items-center justify-center">
                        <i class="fas fa-camera text-white text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Product Photography</h3>
                        <p class="text-gray-600 text-sm">Professional product photography session</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded mt-2">Photography</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="photography" onclick="openModal(this)">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 bg-gradient-to-br from-pink-400 to-red-500 flex items-center justify-center">
                        <i class="fas fa-portrait text-white text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Portrait Session</h3>
                        <p class="text-gray-600 text-sm">Professional portrait photography</p>
                        <span class="inline-block bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded mt-2">Photography</span>
                    </div>
                </div>
            </div>
            
            <div class="gallery-item" data-category="web" onclick="openModal(this)">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-64 bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-4xl"></i>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Analytics Dashboard</h3>
                        <p class="text-gray-600 text-sm">Data visualization and analytics platform</p>
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mt-2">Web Design</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal for Image Preview -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <div class="flex items-center justify-center h-full">
        <div class="bg-white rounded-lg p-8 max-w-2xl mx-4">
            <div id="modal-content">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<section class="py-16 bg-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-4">Like What You See?</h2>
        <p class="text-xl opacity-90 mb-8">Let's create something amazing together. Get in touch to discuss your project.</p>
        <div class="space-x-4">
            <a href="/contact" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                Start Your Project
            </a>
            <a href="/services" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-200">
                View Services
            </a>
        </div>
    </div>
</section>

<script>
function filterGallery(category) {
    const items = document.querySelectorAll('.gallery-item');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button states
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    event.target.classList.add('active', 'bg-blue-600', 'text-white');
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
    
    // Filter items
    items.forEach(item => {
        if (category === 'all' || item.dataset.category === category) {
            item.style.display = 'block';
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'scale(1)';
            }, 10);
        } else {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.8)';
            setTimeout(() => {
                item.style.display = 'none';
            }, 300);
        }
    });
}

function openModal(element) {
    const modal = document.getElementById('imageModal');
    const modalContent = document.getElementById('modal-content');
    
    // Get project details
    const title = element.querySelector('h3').textContent;
    const description = element.querySelector('p').textContent;
    const category = element.querySelector('span').textContent;
    const gradient = element.querySelector('.bg-gradient-to-br').className;
    const icon = element.querySelector('i').className;
    
    // Populate modal content
    modalContent.innerHTML = `
        <div class="text-center">
            <div class="h-64 ${gradient} rounded-lg flex items-center justify-center mb-6">
                <i class="${icon} text-white text-6xl"></i>
            </div>
            <h2 class="text-2xl font-bold mb-4">${title}</h2>
            <p class="text-gray-600 mb-4">${description}</p>
            <span class="inline-block bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">${category}</span>
            <div class="mt-6 space-x-4">
                <button onclick="closeModal()" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                    Close
                </button>
                <a href="/contact" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200 inline-block">
                    Get Quote
                </a>
            </div>
        </div>
    `;
    
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('imageModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection
