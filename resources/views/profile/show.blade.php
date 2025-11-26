<x-app>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Mi Perfil</h1>
            <p class="text-gray-600">Gestiona la información de tu cuenta</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <div class="border-t border-gray-200 my-6"></div>
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-6">
                    @livewire('profile.update-password-form')
                </div>

                <div class="border-t border-gray-200 my-6"></div>
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-6">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <div class="border-t border-gray-200 my-6"></div>
            @endif

            <div class="mt-6">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div class="border-t border-gray-200 my-6"></div>

                <div class="mt-6">
                    @livewire('profile.delete-user-form')
                </div>
            @endif
        </div>
    </div>
</x-app>
