<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {!! NoCaptcha::renderJs() !!}
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <!-- Main Card Container -->
        <div
            class="max-w-5xl w-full bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row min-h-[600px]">

            <!-- Left Column: Illustration & Info (Hidden on Mobile) -->
            <div
                class="hidden md:flex md:w-1/2 bg-gradient-to-br from-indigo-50 to-white p-12 flex-col justify-center border-r border-gray-100">
                <div class="space-y-8">
                    <div>
                        <span
                            class="inline-block px-4 py-1.5 mb-4 text-xs font-semibold tracking-wider text-indigo-600 uppercase bg-indigo-100 rounded-full">
                            Join our Community
                        </span>
                        <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">
                            Start Your <br />
                            <span class="text-indigo-600">Journey</span> With Us.
                        </h1>
                        <p class="mt-4 text-lg text-gray-600">
                            Create an account and get access to all the features of our startup dashboard.
                        </p>
                    </div>

                    <!-- Placeholder Illustration/Preview -->
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-1000">
                        </div>
                        <div
                            class="relative bg-white border border-gray-100 rounded-xl shadow-2xl overflow-hidden aspect-video">
                            <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                alt="Analytics Preview" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-green-500"></div>
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-500"></div>
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-yellow-500"></div>
                        </div>
                        <p>Join 10k+ creators already on board</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Register Form -->
            <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center overflow-y-auto max-h-screen">
                <div class="mb-10 text-center md:text-left flex flex-col items-center md:items-start">
                    <a href="/" class="mb-8">
                        <img src="{{ asset('images/the-times.png') }}" alt="Logo" class="w-32 h-auto">
                    </a>
                    <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
                    <p class="mt-2 text-gray-600">Enter your details to get started.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                            class="block w-full px-4 py-3 rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 bg-gray-50 hover:bg-white"
                            placeholder="Name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="block w-full px-4 py-3 rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 bg-gray-50 hover:bg-white"
                            placeholder="Email">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <input id="password" type="password" name="password" required
                                class="block w-full px-4 py-3 rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 bg-gray-50 hover:bg-white"
                                placeholder="Password">
                            <button type="button" id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-500 transition-colors">
                                <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                            Password</label>
                        <div class="relative">
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="block w-full px-4 py-3 rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 bg-gray-50 hover:bg-white"
                                placeholder="Confirm Password">
                            <button type="button" id="togglePasswordConfirm"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-500 transition-colors">
                                <svg id="eye-icon-confirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs" />
                    </div>

                    <!-- Google reCAPTCHA -->
                    <div class="flex justify-center md:justify-start pt-2">
                        {!! NoCaptcha::display() !!}
                    </div>
                    <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-1 text-xs" />

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform active:scale-[0.98] transition-all duration-200 mt-6">
                        Register
                    </button>

                    <!-- Switch Link -->
                    <p class="text-center text-sm text-gray-600 mt-8">
                        Already have an account?
                        <a href="{{ route('login') }}"
                            class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                            Sign In
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        function setupToggle(passwordFieldId, buttonId, eyeId) {
            const input = document.getElementById(passwordFieldId);
            const btn = document.getElementById(buttonId);
            const eye = document.getElementById(eyeId);

            btn.addEventListener('click', () => {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';

                if (isPassword) {
                    eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.684-4.362M6.1 6.1A9.97 9.97 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.038 5.362M15 12a3 3 0 00-3-3M3 3l18 18" />`;
                } else {
                    eye.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
                }
            });
        }

        setupToggle('password', 'togglePassword', 'eye-icon');
        setupToggle('password_confirmation', 'togglePasswordConfirm', 'eye-icon-confirm');
    </script>
</body>

</html>