@extends('frontend.page')

@section('additional_content')
<!-- Contact Form Section -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Get In Touch</h2>
                <p class="text-gray-600 mb-8">
                    Have a question or want to work together? We'd love to hear from you. 
                    Send us a message and we'll respond as soon as possible.
                </p>
                
                <form class="space-y-6" action="/contact/send" method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name *
                            </label>
                            <input 
                                type="text" 
                                id="first_name" 
                                name="first_name" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="John"
                            >
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name *
                            </label>
                            <input 
                                type="text" 
                                id="last_name" 
                                name="last_name" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Doe"
                            >
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address *
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="john@example.com"
                        >
                    </div>
                    
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="+1 (555) 123-4567"
                        >
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Subject *
                        </label>
                        <select 
                            id="subject" 
                            name="subject" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="support">Technical Support</option>
                            <option value="sales">Sales Question</option>
                            <option value="partnership">Partnership</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message *
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="6" 
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Tell us about your project or question..."
                        ></textarea>
                    </div>
                    
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="newsletter" 
                            name="newsletter" 
                            value="1"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                        <label for="newsletter" class="ml-2 block text-sm text-gray-700">
                            Subscribe to our newsletter for updates and tips
                        </label>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200"
                    >
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Message
                    </button>
                </form>
            </div>
            
            <!-- Contact Information -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact Information</h2>
                <p class="text-gray-600 mb-8">
                    Reach out to us through any of these channels. We're here to help!
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Address</h3>
                            <p class="text-gray-600">
                                123 Business Street<br>
                                Suite 100<br>
                                City, State 12345
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Phone</h3>
                            <p class="text-gray-600">
                                <a href="tel:+15551234567" class="hover:text-blue-600">+1 (555) 123-4567</a><br>
                                <span class="text-sm">Mon-Fri 9AM-6PM EST</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-purple-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Email</h3>
                            <p class="text-gray-600">
                                <a href="mailto:info@yourwebsite.com" class="hover:text-blue-600">info@yourwebsite.com</a><br>
                                <a href="mailto:support@yourwebsite.com" class="hover:text-blue-600">support@yourwebsite.com</a>
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">Business Hours</h3>
                            <p class="text-gray-600">
                                Monday - Friday: 9:00 AM - 6:00 PM<br>
                                Saturday: 10:00 AM - 4:00 PM<br>
                                Sunday: Closed
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center text-white hover:bg-blue-500 transition duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center text-white hover:bg-blue-800 transition duration-200">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center text-white hover:bg-pink-700 transition duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Find Us</h2>
            <p class="text-gray-600">Visit our office or get directions</p>
        </div>
        
        <!-- Placeholder for map - replace with actual map integration -->
        <div class="bg-gray-300 rounded-lg h-96 flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-map-marked-alt text-gray-500 text-4xl mb-4"></i>
                <p class="text-gray-600">Interactive map would go here</p>
                <p class="text-sm text-gray-500">Integrate with Google Maps, Mapbox, or similar service</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-gray-600">Quick answers to common questions</p>
        </div>
        
        <div class="space-y-6">
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left focus:outline-none" onclick="toggleFAQ(this)">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">How quickly do you respond to inquiries?</h3>
                        <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                    </div>
                </button>
                <div class="hidden px-6 pb-4">
                    <p class="text-gray-600">We typically respond to all inquiries within 24 hours during business days. For urgent matters, please call us directly.</p>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left focus:outline-none" onclick="toggleFAQ(this)">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">What services do you offer?</h3>
                        <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                    </div>
                </button>
                <div class="hidden px-6 pb-4">
                    <p class="text-gray-600">We offer a full range of web development, design, and digital marketing services. Contact us to discuss your specific needs.</p>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg">
                <button class="w-full px-6 py-4 text-left focus:outline-none" onclick="toggleFAQ(this)">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Do you offer support after project completion?</h3>
                        <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                    </div>
                </button>
                <div class="hidden px-6 pb-4">
                    <p class="text-gray-600">Yes, we provide ongoing support and maintenance services. We offer various support packages to fit your needs and budget.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function toggleFAQ(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('i');
    
    content.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}
</script>
@endsection
