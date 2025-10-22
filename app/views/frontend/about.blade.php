@extends('frontend.page')

@section('additional_styles')
<style>
    .team-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    .stats-counter {
        font-size: 3rem;
        font-weight: bold;
        color: #3B82F6;
    }
</style>
@endsection

@section('additional_content')
<!-- Stats Section -->
<section class="py-16 bg-blue-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="stats-counter">500+</div>
                <p class="text-gray-600 font-medium">Happy Clients</p>
            </div>
            <div>
                <div class="stats-counter">1000+</div>
                <p class="text-gray-600 font-medium">Projects Completed</p>
            </div>
            <div>
                <div class="stats-counter">10+</div>
                <p class="text-gray-600 font-medium">Years Experience</p>
            </div>
            <div>
                <div class="stats-counter">24/7</div>
                <p class="text-gray-600 font-medium">Support Available</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Meet Our Team</h2>
            <p class="text-xl text-gray-600">The talented people behind our success</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="team-card bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-64 bg-gradient-to-br from-blue-400 to-purple-500"></div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-semibold mb-2">John Doe</h3>
                    <p class="text-blue-600 mb-3">CEO & Founder</p>
                    <p class="text-gray-600 text-sm">Leading the company with vision and passion for excellence.</p>
                    <div class="flex justify-center space-x-3 mt-4">
                        <a href="#" class="text-gray-400 hover:text-blue-600"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-600"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="team-card bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-64 bg-gradient-to-br from-green-400 to-blue-500"></div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-semibold mb-2">Jane Smith</h3>
                    <p class="text-blue-600 mb-3">CTO</p>
                    <p class="text-gray-600 text-sm">Driving technical innovation and ensuring quality delivery.</p>
                    <div class="flex justify-center space-x-3 mt-4">
                        <a href="#" class="text-gray-400 hover:text-blue-600"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-600"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="team-card bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-64 bg-gradient-to-br from-purple-400 to-pink-500"></div>
                <div class="p-6 text-center">
                    <h3 class="text-xl font-semibold mb-2">Mike Johnson</h3>
                    <p class="text-blue-600 mb-3">Lead Designer</p>
                    <p class="text-gray-600 text-sm">Creating beautiful and user-friendly experiences.</p>
                    <div class="flex justify-center space-x-3 mt-4">
                        <a href="#" class="text-gray-400 hover:text-blue-600"><i class="fab fa-dribbble"></i></a>
                        <a href="#" class="text-gray-400 hover:text-blue-600"><i class="fab fa-behance"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Values</h2>
            <p class="text-xl text-gray-600">What drives us every day</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-heart text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Passion</h3>
                <p class="text-gray-600">We love what we do and it shows in every project we deliver.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-star text-green-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Excellence</h3>
                <p class="text-gray-600">We strive for perfection in everything we create and deliver.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Collaboration</h3>
                <p class="text-gray-600">We work together with our clients to achieve amazing results.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lightbulb text-yellow-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Innovation</h3>
                <p class="text-gray-600">We embrace new technologies and creative solutions.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-handshake text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Integrity</h3>
                <p class="text-gray-600">We build trust through honest and transparent relationships.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trophy text-indigo-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold mb-3">Quality</h3>
                <p class="text-gray-600">We never compromise on quality and attention to detail.</p>
            </div>
        </div>
    </div>
</section>
@endsection
