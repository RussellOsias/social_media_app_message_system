<section class="space-y-6 bg-black p-6 rounded-lg shadow-md border border-red-600">
    <header>
        <h2 class="text-xl font-semibold text-white">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-2 text-sm text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-black rounded-lg shadow-inner border border-red-600">
            @csrf
            @method('delete')

            <h2 class="text-xl font-semibold text-white">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-sm text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-4">
                <x-input-label for="password" value="{{ __('Password') }}" class="text-white" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full p-2 bg-black text-white border border-red-600 rounded-lg focus:ring-2 focus:ring-red-400"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-500" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button class="bg-gray-600 text-white hover:bg-gray-700 py-2 px-4 rounded-lg" x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
