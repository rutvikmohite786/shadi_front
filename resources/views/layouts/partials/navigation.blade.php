<nav class="navbar">
    <div class="container">
        <div class="flex items-center justify-between py-3">
            <!-- Brand -->
            <a href="{{ url('/') }}" class="navbar-brand">
                <i class="fas fa-heart"></i>
                {{ config('app.name', 'Shadi') }}
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggle" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars fa-lg"></i>
            </button>

            <!-- Navigation Links -->
            <ul class="navbar-nav">
                @guest
                    <li>
                        <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('register') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-user-plus"></i> Register Free
                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('search.index') }}" class="nav-link {{ request()->routeIs('search.*') ? 'active' : '' }}">
                            <i class="fas fa-search"></i> Search
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('matches.daily') }}" class="nav-link {{ request()->routeIs('matches.*') ? 'active' : '' }}">
                            <i class="fas fa-heart"></i> Matches
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('interests.received') }}" class="nav-link {{ request()->routeIs('interests.*') ? 'active' : '' }}">
                            <i class="fas fa-paper-plane"></i> Interests
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('chat.index') }}" class="nav-link {{ request()->routeIs('chat.*') ? 'active' : '' }}">
                            <i class="fas fa-comments"></i> Chat
                        </a>
                    </li>
                    <li class="nav-dropdown">
                        <a href="{{ route('profile.edit') }}" class="nav-link">
                            <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                        </a>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link" style="background: none; border: none; cursor: pointer;">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

@push('styles')
<style>
/* Mobile-friendly navbar */
.navbar { position: sticky; top: 0; z-index: 1000; background: #fff; border-bottom: 1px solid var(--gray-200); }
.navbar .container { display: flex; align-items: center; justify-content: space-between; flex-wrap: nowrap; }
.navbar-nav { display: flex; align-items: center; gap: 12px; }
.navbar-toggle { display: none; background: none; border: none; }

@media (max-width: 768px) {
    .navbar .container { flex-wrap: wrap; }
    .navbar-toggle { display: inline-flex; align-items: center; justify-content: center; padding: 8px; }
    .navbar-nav {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
        margin-top: 10px;
        display: none !important;
        padding: 10px 0 4px;
    }
    .navbar-nav.open { display: flex !important; }
    .navbar-nav li { width: 100%; }
    .navbar-nav .nav-link,
    .navbar-nav .btn {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
        gap: 8px;
        padding: 12px 14px;
        border-radius: 10px;
    }
    .navbar-nav .btn {
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.navbar-toggle');
    const nav = document.querySelector('.navbar-nav');
    if (!toggle || !nav) return;
    const closeNav = () => {
        nav.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
    };
    const openNav = () => {
        nav.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
    };
    toggle.addEventListener('click', () => {
        const isOpen = nav.classList.contains('open');
        if (isOpen) {
            closeNav();
        } else {
            openNav();
        }
    });
    nav.querySelectorAll('a, button').forEach(el => {
        el.addEventListener('click', closeNav);
    });
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            nav.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        }
    });
    // Ensure closed on load
    closeNav();
});
</script>
@endpush