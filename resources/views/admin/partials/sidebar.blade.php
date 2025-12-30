<aside class="w-64 bg-gray-800 min-h-screen">
    <div class="p-4">
        <h2 class="text-2xl font-bold text-white">Admin Panel</h2>
    </div>
    
    <nav class="mt-4">
        <a href="{{ route('admin.dashboard') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : '' }}">
            <span>Dashboard</span>
        </a>
        
        <a href="{{ route('admin.users.index') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white' : '' }}">
            <span>Users</span>
        </a>
        
        <a href="{{ route('admin.photos.pending') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.photos.*') ? 'bg-gray-700 text-white' : '' }}">
            <span>Photo Moderation</span>
        </a>
        
        <a href="{{ route('admin.plans.index') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.plans.*') ? 'bg-gray-700 text-white' : '' }}">
            <span>Subscription Plans</span>
        </a>
        
        <a href="{{ route('admin.banners.index') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white {{ request()->routeIs('admin.banners.*') ? 'bg-gray-700 text-white' : '' }}">
            <span>Banners</span>
        </a>
        
        <div class="px-4 py-3 text-gray-500 text-sm uppercase tracking-wider mt-4">
            Master Data
        </div>
        
        <a href="{{ route('admin.master.religions') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white">
            <span>Religions</span>
        </a>
        
        <a href="{{ route('admin.master.castes') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white">
            <span>Castes</span>
        </a>
        
        <a href="{{ route('admin.master.mother-tongues') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white">
            <span>Mother Tongues</span>
        </a>
        
        <a href="{{ route('admin.master.locations') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white">
            <span>Locations</span>
        </a>
        
        <a href="{{ route('admin.master.educations') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white">
            <span>Educations</span>
        </a>
        
        <a href="{{ route('admin.master.occupations') }}" 
           class="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-700 hover:text-white">
            <span>Occupations</span>
        </a>
    </nav>
</aside>
















