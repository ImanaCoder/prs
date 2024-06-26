<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-8xl mx-xl-5 mx-2 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center mr-3">
                    @role('admin')
                        <a href="{{ route('admin.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @endrole
                    @role('verifier')
                        <a href="{{ route('verifier.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>

                    @endrole
                    @role('sales_manager')
                        <a href="{{ route('manager.dashboard') }}">
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                        </a>
                    @endrole



                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @role('admin')
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Dashboard V1
                    </x-nav-link>
                    <x-nav-link :href="route('admin.dashboardv2')" :active="request()->routeIs('admin.dashboardv2')">
                        Dashboard V2
                    </x-nav-link>
                    <x-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.index')">
                       Teams
                    </x-nav-link>
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                        Users
                    </x-nav-link>
                    <x-nav-link :href="route('admin.deals')" :active="request()->routeIs('admin.deals')">
                        Deals
                    </x-nav-link>
                    @endrole

                    @role('verifier')
                    <x-nav-link :href="route('verifier.dashboard')" :active="request()->routeIs('verifier.dashboard')">
                        Deals
                    </x-nav-link>
                    <x-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.index')">
                        Payments
                    </x-nav-link>
                    @endrole

                    @role('sales_manager')
                    <x-nav-link :href="route('manager.dashboard')" :active="request()->routeIs('manager.dashboard')">
                        Dashboard
                    </x-nav-link>

                    <x-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.index')">
                        Clients
                    </x-nav-link>

                    <x-nav-link :href="route('deals.index')" :active="request()->routeIs('deals.index')">
                        Deals
                    </x-nav-link>
                    @endrole

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="60">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center">
                                 <!-- Circle with the first letter of the user's name -->
                                 <div class="w-20 h-20 bg-blue text-black rounded-full flex items-center justify-center" style="background-color:blue;font-color:white;width:3rem;height:3rem">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>

                                <!-- User's name and role -->
                                <div class="ml-2 text-left" style="margin-left: 1rem">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-gray-600">
                                        {{ Auth::user()->getRoleNames()->first() }}
                                    </div>
                                </div>


                                <!-- Arrow Icon -->
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>

                            </div>
                        </button>
                    </x-slot>


                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
            <div class="pt-2 p-3 space-y-1">
                @role('admin')
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        Dashboard V1
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.dashboardv2')" :active="request()->routeIs('admin.dashboardv2')">
                        Dashboard V2
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.index')">
                       Teams
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                        Users
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.deals')" :active="request()->routeIs('admin.deals')">
                        Deals
                    </x-responsive-nav-link>
                    @endrole

                    @role('verifier')
                    <x-responsive-nav-link :href="route('verifier.dashboard')" :active="request()->routeIs('verifier.dashboard')">
                        Deals
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('payments.index')" :active="request()->routeIs('payments.index')">
                        Payments
                    </x-responsive-nav-link>
                    @endrole

                    @role('sales_manager')
                    <x-responsive-nav-link :href="route('manager.dashboard')" :active="request()->routeIs('manager.dashboard')">
                        Dashboard
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('clients.index')" :active="request()->routeIs('clients.index')">
                        Clients
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('deals.index')" :active="request()->routeIs('deals.index')">
                        Deals
                    </x-responsive-nav-link>
                    @endrole



            </div>
        </div>


        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500"> {{ Auth::user()->getRoleNames()->first() }} </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Profile
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Log Out
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
