<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang('admin::app.users.login.title')
    </x-slot>

    <div class="flex min-h-screen">
        <!-- Left Panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 flex-col items-center justify-center relative overflow-hidden"
             style="background: linear-gradient(135deg, #0E90D9 0%, #0a6fa8 50%, #064e7a 100%);">

            <!-- Background decoration circles -->
            <div class="absolute top-[-80px] left-[-80px] w-[320px] h-[320px] rounded-full opacity-10"
                 style="background: white;"></div>
            <div class="absolute bottom-[-60px] right-[-60px] w-[260px] h-[260px] rounded-full opacity-10"
                 style="background: white;"></div>
            <div class="absolute top-1/2 left-[-40px] w-[160px] h-[160px] rounded-full opacity-5"
                 style="background: white;"></div>

            <div class="relative z-10 flex flex-col items-center gap-8 px-12 text-center">
                <!-- Logo -->
                @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                    <img class="h-20 object-contain drop-shadow-lg" src="{{ Storage::url($logo) }}" alt="{{ config('app.name') }}" />
                @else
                    <img class="h-20 object-contain drop-shadow-lg" src="{{ asset('Apx.jpeg') }}" alt="{{ config('app.name') }}" />
                @endif

                <div class="flex flex-col gap-3">
                    <h1 class="text-4xl font-bold text-white tracking-tight">
                        {{ config('app.name') }}
                    </h1>
                    <p class="text-lg text-blue-100 font-light max-w-xs leading-relaxed">
                        Manage your leads, contacts, and pipelines all in one place.
                    </p>
                </div>

                <!-- Feature bullets -->
                <div class="flex flex-col gap-3 mt-4">
                    @foreach ([
                        ['icon' => '⚡', 'text' => 'Fast & Intuitive CRM'],
                        ['icon' => '📊', 'text' => 'Pipeline Analytics'],
                        ['icon' => '🔒', 'text' => 'Role-Based Access'],
                    ] as $item)
                        <div class="flex items-center gap-3 text-blue-100">
                            <span class="text-xl">{{ $item['icon'] }}</span>
                            <span class="text-sm font-medium">{{ $item['text'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Panel - Login Form -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center px-6 py-12 bg-white dark:bg-gray-950">

            <div class="w-full max-w-sm">
                <!-- Mobile logo (shown only on small screens) -->
                <div class="flex justify-center mb-8 lg:hidden">
                    @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                        <img class="h-14 object-contain" src="{{ Storage::url($logo) }}" alt="{{ config('app.name') }}" />
                    @else
                        <img class="h-14 object-contain" src="{{ asset('Apx.jpeg') }}" alt="{{ config('app.name') }}" />
                    @endif
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Welcome back
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Sign in to your account to continue
                    </p>
                </div>

                <!-- Flash errors -->
                @if (session('error'))
                    <div class="mb-4 flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
                        <span class="text-base">⚠️</span>
                        {{ session('error') }}
                    </div>
                @endif

                {!! view_render_event('admin.sessions.login.form_controls.before') !!}

                <!-- Login Form -->
                <x-admin::form :action="route('admin.session.store')">
                    <div class="flex flex-col gap-5">
                        <!-- Email -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required text-sm font-medium text-gray-700 dark:text-gray-300">
                                @lang('admin::app.users.login.email')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="email"
                                class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 focus:border-brandColor focus:ring-1 focus:ring-brandColor dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                id="email"
                                name="email"
                                rules="required|email"
                                :label="trans('admin::app.users.login.email')"
                                :placeholder="trans('admin::app.users.login.email')"
                            />

                            <x-admin::form.control-group.error control-name="email" />
                        </x-admin::form.control-group>

                        <!-- Password -->
                        <x-admin::form.control-group class="relative">
                            <div class="flex items-center justify-between">
                                <x-admin::form.control-group.label class="required text-sm font-medium text-gray-700 dark:text-gray-300">
                                    @lang('admin::app.users.login.password')
                                </x-admin::form.control-group.label>

                                <a
                                    href="{{ route('admin.forgot_password.create') }}"
                                    class="text-xs font-medium text-brandColor hover:underline"
                                >
                                    @lang('admin::app.users.login.forget-password-link')
                                </a>
                            </div>

                            <div class="relative">
                                <x-admin::form.control-group.control
                                    type="password"
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 pr-11 text-sm text-gray-900 focus:border-brandColor focus:ring-1 focus:ring-brandColor dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                    id="password"
                                    name="password"
                                    rules="required|min:6"
                                    :label="trans('admin::app.users.login.password')"
                                    :placeholder="trans('admin::app.users.login.password')"
                                />

                                <span
                                    class="icon-eye-hide absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer text-xl text-gray-400 hover:text-gray-600"
                                    onclick="switchVisibility()"
                                    id="visibilityIcon"
                                    role="presentation"
                                    tabindex="0"
                                ></span>
                            </div>

                            <x-admin::form.control-group.error control-name="password" />
                        </x-admin::form.control-group>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="mt-2 w-full rounded-lg px-4 py-3 text-sm font-semibold text-white shadow-sm transition-opacity hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-brandColor focus:ring-offset-2"
                            style="background-color: var(--brand-color);"
                            aria-label="{{ trans('admin::app.users.login.submit-btn') }}"
                        >
                            @lang('admin::app.users.login.submit-btn')
                        </button>
                    </div>
                </x-admin::form>

                {!! view_render_event('admin.sessions.login.form_controls.after') !!}

                <!-- Footer -->
                <p class="mt-8 text-center text-xs text-gray-400 dark:text-gray-600">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function switchVisibility() {
                let passwordField = document.getElementById("password");
                let visibilityIcon = document.getElementById("visibilityIcon");
                passwordField.type = passwordField.type === "password" ? "text" : "password";
                visibilityIcon.classList.toggle("icon-eye");
                visibilityIcon.classList.toggle("icon-eye-hide");
            }
        </script>
    @endpush
</x-admin::layouts.anonymous>
