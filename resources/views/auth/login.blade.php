<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

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
                            Dashboard Startup
                        </span>
                        <h1 class="text-4xl font-extrabold text-gray-900 leading-tight">
                            Elevate your <br />
                            <span class="text-indigo-600">Workflow</span> Today.
                        </h1>
                        <p class="mt-4 text-lg text-gray-600">
                            Join thousands of startups who manage their business with our minimal and powerful
                            dashboard.
                        </p>
                    </div>

                    <!-- Placeholder Illustration/Preview -->
                    <div class="relative group">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition duration-1000">
                        </div>
                        <div
                            class="relative bg-white border border-gray-100 rounded-xl shadow-2xl overflow-hidden aspect-video">
                            <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                                alt="Dashboard Preview" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-indigo-500"></div>
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-purple-500"></div>
                            <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-500"></div>
                        </div>
                        <p>Trusted by 10k+ users worldwide</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Login Form -->
            <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center">
                <div class="mb-10 text-center md:text-left flex flex-col items-center md:items-start">
                    <a href="/" class="mb-8">
                        <img src="{{ asset('images/the-times.png') }}" alt="Logo" class="w-32 h-auto">
                    </a>
                    <h2 class="text-3xl font-bold text-gray-900">Sign In</h2>
                    <p class="mt-2 text-gray-600">Welcome back! Please enter your details.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="block w-full px-4 py-3 rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all duration-200 bg-gray-50 hover:bg-white"
                            placeholder="Email">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 transition duration-150"
                                    href="{{ route('password.request') }}">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
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

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                        <label for="remember_me" class="ml-2 text-sm text-gray-600 cursor-pointer select-none">Keep me
                            signed in</label>
                    </div>

                    <!-- Google reCAPTCHA -->
                    <div class="flex justify-center md:justify-start">
                        {!! NoCaptcha::display() !!}
                    </div>
                    <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-1 text-xs" />

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform active:scale-[0.98] transition-all duration-200">
                        Sign In
                    </button>

                    <!-- Switch Link -->
                    <p class="text-center text-sm text-gray-600 mt-8">
                        New here?
                        <a href="{{ route('register') }}"
                            class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                            Create an account
                        </a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eye-icon');

        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';

            if (isPassword) {
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 012.684-4.362M6.1 6.1A9.97 9.97 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.038 5.362M15 12a3 3 0 00-3-3M3 3l18 18" />`;
            } else {
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
        });
    </script>
</body>

</html>