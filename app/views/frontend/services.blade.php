@extends('frontend.page')

@section('additional_content')
<!-- Services Grid -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
            <p class="text-xl text-gray-600">Comprehensive solutions for your business needs</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition duration-300">
                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-code text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4">Web Development</h3>
                <p class="text-gray-600 mb-6">Custom web applications built with modern technologies and best practices.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Responsive Design</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Modern Frameworks</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Performance Optimization</li>
                </ul>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition duration-300">
                <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-mobile-alt text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4">Mobile Apps</h3>
                <p class="text-gray-600 mb-6">Native and cross-platform mobile applications for iOS and Android.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>iOS & Android</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Cross-platform</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>App Store Deployment</li>
                </ul>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition duration-300">
                <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-paint-brush text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4">UI/UX Design</h3>
                <p class="text-gray-600 mb-6">Beautiful and intuitive user interfaces that enhance user experience.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>User Research</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Wireframing</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Prototyping</li>
                </ul>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition duration-300">
                <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-search text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4">SEO Optimization</h3>
                <p class="text-gray-600 mb-6">Improve your search engine rankings and drive organic traffic.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Keyword Research</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>On-page SEO</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Performance Tracking</li>
                </ul>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition duration-300">
                <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-chart-line text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4">Digital Marketing</h3>
                <p class="text-gray-600 mb-6">Comprehensive digital marketing strategies to grow your business.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Social Media</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Content Marketing</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>PPC Campaigns</li>
                </ul>
            </div>
            
            <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition duration-300">
                <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-headset text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-4">Support & Maintenance</h3>
                <p class="text-gray-600 mb-6">Ongoing support and maintenance to keep your systems running smoothly.</p>
                <ul class="text-sm text-gray-600 space-y-2">
                    <li><i class="fas fa-check text-green-500 mr-2"></i>24/7 Monitoring</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Regular Updates</li>
                    <li><i class="fas fa-check text-green-500 mr-2"></i>Security Patches</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Process</h2>
            <p class="text-xl text-gray-600">How we work with you to deliver exceptional results</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white text-2xl font-bold">1</span>
                </div>
                <h3 class="text-xl font-semibold mb-3">Discovery</h3>
                <p class="text-gray-600">We learn about your business, goals, and requirements.</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white text-2xl font-bold">2</span>
                </div>
                <h3 class="text-xl font-semibold mb-3">Planning</h3>
                <p class="text-gray-600">We create a detailed plan and timeline for your project.</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white text-2xl font-bold">3</span>
                </div>
                <h3 class="text-xl font-semibold mb-3">Development</h3>
                <p class="text-gray-600">We build your solution using best practices and modern tools.</p>
            </div>
            
            <div class="text-center">
                <div class="w-20 h-20 bg-yellow-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-white text-2xl font-bold">4</span>
                </div>
                <h3 class="text-xl font-semibold mb-3">Launch</h3>
                <p class="text-gray-600">We deploy your solution and provide ongoing support.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-blue-600 text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl opacity-90 mb-8">Let's discuss your project and see how we can help you achieve your goals.</p>
        <div class="space-x-4">
            <a href="/contact" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                Get a Quote
            </a>
            <a href="/about" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-200">
                Learn More
            </a>
        </div>
    </div>
</section>
@endsection
