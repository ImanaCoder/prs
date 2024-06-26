<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 dark:text-gray-200 leading-tight mx-xl-5 mx-2 py-3">
            Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                                <div class="max-w-xl mx-auto">
                                    @include('profile.partials.update-profile-information-form')
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                                <div class="max-w-xl mx-auto">
                                    @include('profile.partials.update-password-form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
            {{-- <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div> --}}
