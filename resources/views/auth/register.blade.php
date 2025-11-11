<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10">

                <button type="button" id="togglePassword" class="absolute right-0 inset-y-0 flex items-center pr-3 hidden">
                    <svg id="icon-eye-off" class="h-5 w-5 text-gray-600 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.27-2.943-9.543-7 
                                a9.97 9.97 0 012.318-4.362M9.88 9.88A3 3 0 0114.12 14.12M3 3l18 18"/>
                    </svg>

                    <svg id="icon-eye" class="h-5 w-5 text-gray-600 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                            -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10">

                <button type="button" id="togglePasswordConfirm" class="absolute right-0 inset-y-0 flex items-center pr-3 hidden">
                    <svg id="icon-eye-off-confirm" class="h-5 w-5 text-gray-600 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.27-2.943-9.543-7 
                                a9.97 9.97 0 012.318-4.362M9.88 9.88A3 3 0 0114.12 14.12M3 3l18 18"/>
                    </svg>

                    <svg id="icon-eye-confirm" class="h-5 w-5 text-gray-600 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                            -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Password Toggle Script -->
    <script>
        function setupToggle(passwordFieldId, buttonId, eyeId, eyeOffId) {
            const input = document.getElementById(passwordFieldId);
            const btn = document.getElementById(buttonId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);

            btn.classList.add('hidden');

            input.addEventListener('input', () => {
                if (input.value.length > 0) {
                    btn.classList.remove('hidden');
                    eyeOff.classList.remove('hidden');
                } else {
                    btn.classList.add('hidden');
                    eye.classList.add('hidden');
                    eyeOff.classList.add('hidden');
                }
            });

            btn.addEventListener('click', () => {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                eye.classList.toggle('hidden', !isPassword);
                eyeOff.classList.toggle('hidden', isPassword);
            });
        }

        setupToggle('password', 'togglePassword', 'icon-eye', 'icon-eye-off');
        setupToggle('password_confirmation', 'togglePasswordConfirm', 'icon-eye-confirm', 'icon-eye-off-confirm');
    </script>
</x-guest-layout>
