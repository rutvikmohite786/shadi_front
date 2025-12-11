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
