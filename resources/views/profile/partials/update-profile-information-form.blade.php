<section class="bg-black p-6 rounded-lg shadow-md border border-red-600">
    <header>
        <h2 class="text-lg font-semibold text-white">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-400">
            {{ __("Update your account's profile information.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-white" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full p-2 bg-black text-white border border-red-600 rounded-lg focus:ring-2 focus:ring-red-400" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-red-500" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="birthday" :value="__('Birthday')" class="text-white" />
            <x-text-input id="birthday" name="birthday" type="date" class="mt-1 block w-full p-2 bg-black text-white border border-red-600 rounded-lg focus:ring-2 focus:ring-red-400" :value="old('birthday', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '')" onchange="calculateAge()" />
            <x-input-error class="mt-2 text-red-500" :messages="$errors->get('birthday')" />
        </div>

        <div>
            <x-input-label for="age" :value="__('Age')" class="text-white" />
            <x-text-input id="age" name="age" type="number" class="mt-1 block w-full p-2 bg-black text-white border border-red-600 rounded-lg focus:ring-2 focus:ring-red-400" :value="old('age', $user->birthday ? \Carbon\Carbon::parse($user->birthday)->age : '')" readonly />
            <x-input-error class="mt-2 text-red-500" :messages="$errors->get('age')" />
        </div>

        <div>
            <x-input-label for="gender" :value="__('Gender')" class="text-white" />
            <select id="gender" name="gender" class="mt-1 block w-full p-2 bg-black text-white border border-red-600 rounded-lg focus:ring-2 focus:ring-red-400">
                <option value="">{{ __('Select Gender') }}</option>
                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
            </select>
            <x-input-error class="mt-2 text-red-500" :messages="$errors->get('gender')" />
        </div>

        <div>
            <x-input-label for="occupation" :value="__('Occupation')" class="text-white" />
            <x-text-input id="occupation" name="occupation" type="text" class="mt-1 block w-full p-2 bg-black text-white border border-red-600 rounded-lg focus:ring-2 focus:ring-red-400" :value="old('occupation', $user->occupation)" />
            <x-input-error class="mt-2 text-red-500" :messages="$errors->get('occupation')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Address')" class="text-white" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full p-2 bg-black text-white border border-red-600 rounded-lg focus:ring-2 focus:ring-red-400" :value="old('address', $user->address)" />
            <x-input-error class="mt-2 text-red-500" :messages="$errors->get('address')" />
        </div>

        <div>
            <x-input-label for="nationality" :value="__('Nationality')" class="text-white" />
            <x-text-input id="nationality" name="nationality" type="text" class="mt-1 block w-full p-2 bg-black text-white border border-red-600 rounded-lg focus:ring-2 focus:ring-red-400" :value="old('nationality', $user->nationality)" />
            <x-input-error class="mt-2 text-red-500" :messages="$errors->get('nationality')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

<script>
    function calculateAge() {
        const birthdayInput = document.getElementById('birthday');
        const ageInput = document.getElementById('age');
        const birthday = new Date(birthdayInput.value);
        const today = new Date();

        // Calculate age
        let age = today.getFullYear() - birthday.getFullYear();
        const monthDiff = today.getMonth() - birthday.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthday.getDate())) {
            age--;
        }

        // Set the calculated age in the age input
        ageInput.value = age;
    }
</script>
