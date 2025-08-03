<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    @auth
        @if(auth()->user()->is_admin)
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                {{ __('Admin Panel') }}
            </x-nav-link>
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('User Dashboard') }}
            </x-nav-link>
        @else
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('My Sites') }}
            </x-nav-link>
            <x-nav-link :href="route('subscribe.show')" :active="request()->routeIs('subscribe.show')">
                {{ __('Subscribe') }}
            </x-nav-link>
        @endif
    @endauth

    @guest
        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
            {{ __('Home') }}
        </x-nav-link>
        <x-nav-link :href="route('pricing')" :active="request()->routeIs('pricing')">
            {{ __('Pricing') }}
        </x-nav-link>
    @endguest
</div>