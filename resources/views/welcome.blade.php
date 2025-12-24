@extends('layouts.app')

@section('title', 'Find Your Perfect Life Partner')

@section('content')
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Find Your Perfect <span style="color: var(--secondary-300);">Life Partner</span></h1>
                <p>Join millions of people who found love through {{ config('app.name', 'Shadi') }}. Verified profiles, advanced matching, and a safe platform to begin your journey to happiness.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-user-plus"></i> Register Free
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="stats">
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="stat-item" data-animate="fade-in" data-animate-delay="0">
                    <div class="stat-number">2M+</div>
                    <div class="stat-label">Active Members</div>
                </div>
                <div class="stat-item" data-animate="fade-in" data-animate-delay="100">
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">Success Stories</div>
                </div>
                <div class="stat-item" data-animate="fade-in" data-animate-delay="200">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Verified Profiles</div>
                </div>
                <div class="stat-item" data-animate="fade-in" data-animate-delay="300">
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Years of Trust</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Highlights Section -->
    <section class="py-16" style="background: linear-gradient(135deg, var(--primary-50) 0%, var(--secondary-50) 100%);">
        <div class="container">
            <div class="section-header">
                <h2>Our Commitment to You</h2>
                <p>We prioritize your safety, privacy, and satisfaction above all else.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="feature-card" data-animate="slide-up" data-animate-delay="0" style="background: white; border: 2px solid var(--primary-200);">
                    <div class="feature-icon" style="background: linear-gradient(135deg, var(--primary-100) 0%, var(--primary-200) 100%);">
                        <i class="fas fa-user-check" style="color: var(--primary-600);"></i>
                    </div>
                    <h3 style="color: var(--primary-700); margin-bottom: 1rem;">Manual Image Verification</h3>
                    <p style="color: var(--gray-700); line-height: 1.8;">
                        We manually verify every image to ensure authenticity. We do not use AI to detect fake images - our dedicated team personally reviews each photo to protect you from deception.
                    </p>
                </div>
                
                <div class="feature-card" data-animate="slide-up" data-animate-delay="100" style="background: white; border: 2px solid var(--primary-200);">
                    <div class="feature-icon" style="background: linear-gradient(135deg, var(--primary-100) 0%, var(--primary-200) 100%);">
                        <i class="fas fa-shield-alt" style="color: var(--primary-600);"></i>
                    </div>
                    <h3 style="color: var(--primary-700); margin-bottom: 1rem;">Privacy First</h3>
                    <p style="color: var(--gray-700); line-height: 1.8;">
                        We do not show any personal information without your explicit consent. Your privacy is our top priority, and we ensure your sensitive details remain protected and confidential.
                    </p>
                </div>
                
                <div class="feature-card" data-animate="slide-up" data-animate-delay="200" style="background: white; border: 2px solid var(--primary-200);">
                    <div class="feature-icon" style="background: linear-gradient(135deg, var(--secondary-100) 0%, var(--secondary-200) 100%);">
                        <i class="fas fa-gift" style="color: var(--secondary-600);"></i>
                    </div>
                    <h3 style="color: var(--primary-700); margin-bottom: 1rem;">Free Subscription Offer</h3>
                    <p style="color: var(--gray-700); line-height: 1.8;">
                        Be among the first 10,000 users and get a free subscription! Join now to unlock premium features and enhance your matchmaking experience at no cost.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose {{ config('app.name', 'Shadi') }}?</h2>
                <p>We're committed to helping you find your perfect match with features designed for your success.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="feature-card" data-animate="slide-up" data-animate-delay="0">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>100% Verified Profiles</h3>
                    <p>Every profile goes through our strict verification process to ensure genuine and authentic matches.</p>
                </div>
                
                <div class="feature-card" data-animate="slide-up" data-animate-delay="100">
                    <div class="feature-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h3>Smart Matchmaking</h3>
                    <p>Our intelligent algorithm analyzes your preferences to suggest the most compatible matches for you.</p>
                </div>
                
                <div class="feature-card" data-animate="slide-up" data-animate-delay="200">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Privacy Protected</h3>
                    <p>Your personal information is secure. Control who sees your profile and contact details.</p>
                </div>
                
                <div class="feature-card" data-animate="slide-up" data-animate-delay="300">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Secure Communication</h3>
                    <p>Chat securely with your matches using our encrypted messaging system.</p>
                </div>
                
                <div class="feature-card" data-animate="slide-up" data-animate-delay="400">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile Friendly</h3>
                    <p>Access your matches anytime, anywhere with our responsive design optimized for all devices.</p>
                </div>
                
                <div class="feature-card" data-animate="slide-up" data-animate-delay="500">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>24/7 Support</h3>
                    <p>Our dedicated support team is always ready to help you on your journey to finding love.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16" style="background: var(--gray-100);">
        <div class="container">
            <div class="section-header">
                <h2>How It Works</h2>
                <p>Finding your life partner is easy with our simple 4-step process.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-6" data-animate="slide-up">
                    <div class="feature-icon" style="background: linear-gradient(135deg, var(--secondary-100) 0%, var(--secondary-200) 100%);">
                        <span style="font-family: 'Playfair Display', serif; font-size: 1.5rem; color: var(--secondary-700);">1</span>
                    </div>
                    <h4 class="mt-4 mb-2">Create Profile</h4>
                    <p class="text-muted">Sign up for free and create your detailed profile with photos and preferences.</p>
                </div>
                
                <div class="text-center p-6" data-animate="slide-up" data-animate-delay="100">
                    <div class="feature-icon" style="background: linear-gradient(135deg, var(--secondary-100) 0%, var(--secondary-200) 100%);">
                        <span style="font-family: 'Playfair Display', serif; font-size: 1.5rem; color: var(--secondary-700);">2</span>
                    </div>
                    <h4 class="mt-4 mb-2">Browse Matches</h4>
                    <p class="text-muted">Get personalized match recommendations based on your preferences.</p>
                </div>
                
                <div class="text-center p-6" data-animate="slide-up" data-animate-delay="200">
                    <div class="feature-icon" style="background: linear-gradient(135deg, var(--secondary-100) 0%, var(--secondary-200) 100%);">
                        <span style="font-family: 'Playfair Display', serif; font-size: 1.5rem; color: var(--secondary-700);">3</span>
                    </div>
                    <h4 class="mt-4 mb-2">Connect</h4>
                    <p class="text-muted">Send interests and start conversations with profiles you like.</p>
                </div>
                
                <div class="text-center p-6" data-animate="slide-up" data-animate-delay="300">
                    <div class="feature-icon" style="background: linear-gradient(135deg, var(--secondary-100) 0%, var(--secondary-200) 100%);">
                        <span style="font-family: 'Playfair Display', serif; font-size: 1.5rem; color: var(--secondary-700);">4</span>
                    </div>
                    <h4 class="mt-4 mb-2">Find Your Match</h4>
                    <p class="text-muted">Meet your perfect partner and start your journey together.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Stories -->
    <section class="success-stories">
        <div class="container">
            <div class="section-header">
                <h2>Happy Couples</h2>
                <p>Real stories from couples who found their soulmates on {{ config('app.name', 'Shadi') }}.</p>
            </div>
            
            <div class="story-card mb-8" data-animate="fade-in">
                <div class="story-image" style="background-image: url('{{ asset('images/static/couple1.jpg') }}'); background-color: var(--primary-200);"></div>
                <div class="story-content">
                    <h3>"We found each other when we least expected it"</h3>
                    <p>"After years of searching, I finally found my soulmate on {{ config('app.name', 'Shadi') }}. The platform made it so easy to connect with genuine people. Today, we're happily married with two beautiful children. Thank you for bringing us together!"</p>
                    <div class="story-couple">— Priya & Rahul, Married since 2022</div>
                </div>
            </div>
            
            <div class="text-center">
                <a href="#" class="btn btn-primary btn-lg">
                    <i class="fas fa-heart"></i> Read More Success Stories
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16" style="background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-800) 100%); color: white;">
        <div class="container text-center">
            <h2 style="color: white; font-size: 2.5rem; margin-bottom: 1rem;">Start Your Journey Today</h2>
            <p style="font-size: 1.25rem; opacity: 0.9; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                Join thousands of happy couples who found their perfect match. Registration is free!
            </p>
            <a href="{{ route('register') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-user-plus"></i> Register Free Now
            </a>
        </div>
    </section>
@endsection
