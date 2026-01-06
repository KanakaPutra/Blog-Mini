<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
        <p class="text-sm text-gray-600 mt-1">Join us and start your journey</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" class="text-gray-700 font-medium" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <x-text-input id="name"
                        class="block w-full pl-10 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-200"
                        type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                        placeholder="Name" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <x-text-input id="email"
                        class="block w-full pl-10 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-200"
                        type="email" name="email" :value="old('email')" required autocomplete="username"
                        placeholder="Email" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password"
                    class="block w-full pl-10 pr-10 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-200"
                    type="password" name="password" required autocomplete="new-password" placeholder="Password" />
                <button type="button" id="togglePassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none transition duration-150">
                    <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.684-4.362M6.1 6.1A9.97 9.97 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.038 5.362M15 12a3 3 0 00-3-3M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                class="text-gray-700 font-medium" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password_confirmation"
                    class="block w-full pl-10 pr-10 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition duration-200"
                    type="password" name="password_confirmation" required autocomplete="new-password"
                    placeholder="Confirm Password" />
                <button type="button" id="togglePasswordConfirm"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-gray-600 focus:outline-none transition duration-150">
                    <svg id="icon-eye-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="icon-eye-off-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.684-4.362M6.1 6.1A9.97 9.97 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.038 5.362M15 12a3 3 0 00-3-3M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Google reCAPTCHA -->
        <div class="flex flex-col items-center">
            {!! NoCaptcha::display() !!}
            <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2" />
        </div>

        <!-- Register Button -->
        <div>
            <x-primary-button
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:-translate-y-0.5 transition-all duration-200">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}"
                    class="font-medium text-indigo-600 hover:text-indigo-500 hover:underline transition duration-150 ease-in-out">
                    Log in
                </a>
            </p>
        </div>
    </form>

    <script>
        function setupToggle(passwordFieldId, buttonId, eyeId, eyeOffId) {
            const input = document.getElementById(passwordFieldId);
            const btn = document.getElementById(buttonId);
            const eye = document.getElementById(eyeId);
            const eyeOff = document.getElementById(eyeOffId);

            function updateIcons() {
                if (input.value.length > 0) {
                    btn.classList.remove('hidden');
                    if (input.type === 'password') {
                        eyeOff.classList.remove('hidden');
                        eye.classList.add('hidden');
                    } else {
                        eyeOff.classList.add('hidden');
                        eye.classList.remove('hidden');
                    }
                } else {
                    btn.classList.add('hidden');
                }
            }

            updateIcons();

            input.addEventListener('input', updateIcons);

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