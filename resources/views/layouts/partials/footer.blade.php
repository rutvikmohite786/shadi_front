<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-brand-col">
                <a href="{{ url('/') }}" class="footer-brand">
                    <i class="fas fa-heart"></i> {{ config('app.name', 'Shadi') }}
                </a>
                <p class="footer-description">
                    Find your perfect life partner with our trusted matrimonial service.
                </p>
                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

            <!-- Links Columns -->
            <div class="footer-links-grid">
                <div class="footer-col">
                    <h5 class="footer-heading">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="#">Success Stories</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h5 class="footer-heading">Support</h5>
                    <ul class="footer-links">
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Safety Tips</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h5 class="footer-heading">Legal</h5>
                    <ul class="footer-links">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Shadi') }}. All rights reserved. Made with <i class="fas fa-heart text-primary"></i> in India</p>
        </div>
    </div>
</footer>

<style>
.footer-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
}

.footer-brand-col {
    flex: 1;
    min-width: 200px;
    max-width: 280px;
}

.footer-links-grid {
    flex: 2;
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.footer-col {
    flex: 1;
    min-width: 120px;
}

@media (max-width: 768px) {
    .footer-grid {
        flex-direction: column;
    }
    
    .footer-brand-col {
        max-width: 100%;
        text-align: center;
        margin-bottom: 1rem;
    }
    
    .footer-social {
        justify-content: center;
    }
    
    .footer-links-grid {
        justify-content: space-between;
    }
    
    .footer-col {
        min-width: 100px;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .footer-links-grid {
        flex-direction: column;
        align-items: center;
    }
    
    .footer-col {
        width: 100%;
    }
}
</style>
