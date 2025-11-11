<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pr-10">

                <button type="button" id="togglePassword" class="absolute right-0 inset-y-0 flex items-center pr-3 hidden">
                    <svg id="eyeOff" class="h-5 w-5 text-gray-600 hidden" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.27-2.943-9.543-7 
                                a9.97 9.97 0 012.318-4.362M9.88 9.88A3 3 0 0114.12 14.12M3 3l18 18"/>
                    </svg>

                    <svg id="eyeOn" class="h-5 w-5 text-gray-600 hidden" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
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
                    <svg id="eyeOffConfirm" class="h-5 w-5 text-gray-600 hidden" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.27-2.943-9.543-7 
                                a9.97 9.97 0 012.318-4.362M9.88 9.88A3 3 0 0114.12 14.12M3 3l18 18"/>
                    </svg>

                    <svg id="eyeOnConfirm" class="h-5 w-5 text-gray-600 hidden" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
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
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function setupToggle(inputId, btnId, eyeOnId, eyeOffId) {
            const input = document.getElementById(inputId);
            const btn = document.getElementById(btnId);
            const eyeOn = document.getElementById(eyeOnId);
            const eyeOff = document.getElementById(eyeOffId);

            input.addEventListener('input', () => {
                if (input.value.length > 0) {
                    btn.classList.remove('hidden');
                    eyeOff.classList.remove('hidden');
                } else {
                    btn.classList.add('hidden');
                    eyeOn.classList.add('hidden');
                    eyeOff.classList.add('hidden');
                }
            });

            btn.addEventListener('click', () => {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                eyeOn.classList.toggle('hidden', !isPassword);
                eyeOff.classList.toggle('hidden', isPassword);
            });
        }

        setupToggle('password', 'togglePassword', 'eyeOn', 'eyeOff');
        setupToggle('password_confirmation', 'togglePasswordConfirm', 'eyeOnConfirm', 'eyeOffConfirm');
    </script>
</x-guest-layout>
