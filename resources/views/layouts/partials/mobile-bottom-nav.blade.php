@auth
<nav class="mobile-bottom-nav">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="{{ route('search.index') }}" class="{{ request()->routeIs('search.*') ? 'active' : '' }}">
        <i class="fas fa-search"></i>
        <span>Search</span>
    </a>
    <a href="{{ route('matches.daily') }}" class="{{ request()->routeIs('matches.*') ? 'active' : '' }}">
        <i class="fas fa-heart"></i>
        <span>Matches</span>
    </a>
    <a href="{{ route('interests.received') }}" class="{{ request()->routeIs('interests.*') ? 'active' : '' }}">
        <i class="fas fa-paper-plane"></i>
        <span>Interests</span>
    </a>
    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </a>
</nav>
@endauth

