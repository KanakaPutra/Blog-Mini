<section class="md:grid md:grid-cols-3 md:gap-6">
    <div class="md:col-span-1">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Personal Information') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __("Use a permanent address where you can receive mail.") }}
            </p>
        </header>
    </div>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="flex items-center gap-4 mb-6">
            @php
                $initial = strtoupper(substr($user->name, 0, 1));
            @endphp
            <div
                class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-500 overflow-hidden border border-gray-300">
                {{ $initial }}
            </div>
            <div>
                <button type="button"
                    class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Change avatar') }}
                </button>
                <p class="mt-2 text-xs text-gray-500">JPG, GIF or PNG. 1MB max.</p>
            </div>
        </div>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 gap-6">
                <!-- Name Field -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text"
                        class="mt-1 block w-full"
                        :value="old('name', $user->name)" required autofocus autocomplete="name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <!-- Email Field -->
                <div>
                    <x-input-label for="email" :value="__('Email address')" />
                    <x-text-input id="email" name="email" type="email"
                        class="mt-1 block w-full"
                        :value="old('email', $user->email)" required autocomplete="username" />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800">
                                {{ __('Your email address is unverified.') }}

                                <button form="send-verification"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600">
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>
</section>