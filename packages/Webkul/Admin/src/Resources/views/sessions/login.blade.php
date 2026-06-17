<x-admin::layouts.anonymous>
    <x-slot:title>
        @lang('admin::app.users.login.title')
    </x-slot>

    @push('styles')
    <style>
        body, html { margin: 0; padding: 0; height: 100%; }
        #app { height: 100%; }

        .login-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
            font-family: 'Poppins', sans-serif;
        }

        /* ── Left branding panel ── */
        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, #0a6fa8 0%, #0E90D9 45%, #38b6ff 100%);
            padding: 48px 40px;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: -120px; right: -120px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }
        .login-left::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }

        .login-left-inner {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 32px;
        }

        .login-logo {
            width: 120px;
            height: 120px;
            object-fit: contain;
            border-radius: 20px;
            background: rgba(255,255,255,0.15);
            padding: 16px;
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }

        .login-brand-name {
            font-size: 36px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .login-brand-tagline {
            font-size: 15px;
            color: rgba(255,255,255,0.80);
            line-height: 1.7;
            max-width: 280px;
            margin: 0;
            font-weight: 400;
        }

        .login-features {
            display: flex;
            flex-direction: column;
            gap: 14px;
            width: 100%;
            max-width: 280px;
        }

        .login-feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 12px;
            padding: 12px 16px;
            backdrop-filter: blur(4px);
        }

        .login-feature-icon {
            font-size: 20px;
            line-height: 1;
        }

        .login-feature-text {
            font-size: 13px;
            color: rgba(255,255,255,0.90);
            font-weight: 500;
        }

        /* ── Right form panel ── */
        .login-right {
            width: 480px;
            min-width: 480px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 48px 52px;
            box-shadow: -4px 0 30px rgba(0,0,0,0.06);
        }

        .login-form-box {
            width: 100%;
            max-width: 360px;
        }

        .login-form-header {
            margin-bottom: 36px;
        }

        .login-form-title {
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 6px;
        }

        .login-form-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        .login-field {
            margin-bottom: 20px;
        }

        .login-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }

        .login-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .login-label .req {
            color: #ef4444;
            margin-left: 2px;
        }

        .login-forgot {
            font-size: 12px;
            font-weight: 500;
            color: #0E90D9;
            text-decoration: none;
        }
        .login-forgot:hover { text-decoration: underline; }

        .login-input-wrap {
            position: relative;
        }

        .login-input-wrap input,
        .login-input-wrap [type="email"],
        .login-input-wrap [type="password"],
        .login-input-wrap [type="text"] {
            width: 100% !important;
            padding: 11px 16px !important;
            border: 1.5px solid #e5e7eb !important;
            border-radius: 10px !important;
            font-size: 14px !important;
            color: #111827 !important;
            background: #f9fafb !important;
            outline: none !important;
            transition: border-color 0.2s, box-shadow 0.2s !important;
            box-sizing: border-box !important;
        }

        .login-input-wrap input:focus,
        .login-input-wrap [type="email"]:focus,
        .login-input-wrap [type="password"]:focus,
        .login-input-wrap [type="text"]:focus {
            border-color: #0E90D9 !important;
            box-shadow: 0 0 0 3px rgba(14,144,217,0.12) !important;
            background: #ffffff !important;
        }

        .login-eye-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #9ca3af;
            background: none;
            border: none;
            padding: 0;
            line-height: 1;
        }
        .login-eye-btn:hover { color: #374151; }

        .login-submit-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
            margin-top: 8px;
            letter-spacing: 0.2px;
            transition: opacity 0.2s, transform 0.1s;
            background: linear-gradient(135deg, #0E90D9, #0a6fa8);
            box-shadow: 0 4px 14px rgba(14,144,217,0.35);
        }
        .login-submit-btn:hover { opacity: 0.92; transform: translateY(-1px); }
        .login-submit-btn:active { transform: translateY(0); }

        .login-footer {
            margin-top: 32px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
        }

        /* Responsive: stack on small screens */
        @media (max-width: 900px) {
            .login-wrapper { flex-direction: column; height: auto; min-height: 100vh; }
            .login-left { padding: 40px 24px; flex: none; }
            .login-right { width: 100%; min-width: unset; padding: 36px 24px; box-shadow: none; }
        }
    </style>
    @endpush

    <div class="login-wrapper">

        <!-- ── Left: Branding ── -->
        <div class="login-left">
            <div class="login-left-inner">

                @if ($logo = core()->getConfigData('general.design.admin_logo.logo_image'))
                    <img class="login-logo" src="{{ Storage::url($logo) }}" alt="{{ config('app.name') }}" />
                @else
                    <img class="login-logo" src="{{ asset('Apx.jpeg') }}" alt="{{ config('app.name') }}" />
                @endif

                <div>
                    <p class="login-brand-name">{{ config('app.name') }}</p>
                </div>

                <p class="login-brand-tagline">
                    Manage your leads, contacts, and sales pipelines — all in one powerful platform.
                </p>

                <div class="login-features">
                    <div class="login-feature-item">
                        <span class="login-feature-icon">⚡</span>
                        <span class="login-feature-text">Fast & Intuitive CRM</span>
                    </div>
                    <div class="login-feature-item">
                        <span class="login-feature-icon">📊</span>
                        <span class="login-feature-text">Pipeline & Lead Analytics</span>
                    </div>
                    <div class="login-feature-item">
                        <span class="login-feature-icon">🔒</span>
                        <span class="login-feature-text">Role-Based Access Control</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- ── Right: Login Form ── -->
        <div class="login-right">
            <div class="login-form-box">

                <div class="login-form-header">
                    <h1 class="login-form-title">Welcome back</h1>
                    <p class="login-form-subtitle">Sign in to your account to continue</p>
                </div>

                @if (session('error'))
                    <div style="margin-bottom:20px; padding:12px 16px; background:#fef2f2; border:1px solid #fecaca; border-radius:10px; font-size:13px; color:#dc2626;">
                        ⚠️ {{ session('error') }}
                    </div>
                @endif

                {!! view_render_event('admin.sessions.login.form_controls.before') !!}

                <x-admin::form :action="route('admin.session.store')">

                    <!-- Email Field -->
                    <div class="login-field">
                        <div class="login-label-row">
                            <label class="login-label" for="email">
                                @lang('admin::app.users.login.email') <span class="req">*</span>
                            </label>
                        </div>
                        <div class="login-input-wrap">
                            <x-admin::form.control-group.control
                                type="email"
                                id="email"
                                name="email"
                                rules="required|email"
                                :label="trans('admin::app.users.login.email')"
                                placeholder="you@example.com"
                            />
                        </div>
                        <x-admin::form.control-group.error control-name="email" />
                    </div>

                    <!-- Password Field -->
                    <div class="login-field">
                        <div class="login-label-row">
                            <label class="login-label" for="password">
                                @lang('admin::app.users.login.password') <span class="req">*</span>
                            </label>
                            <a class="login-forgot" href="{{ route('admin.forgot_password.create') }}">
                                @lang('admin::app.users.login.forget-password-link')
                            </a>
                        </div>
                        <div class="login-input-wrap">
                            <x-admin::form.control-group.control
                                type="password"
                                id="password"
                                name="password"
                                rules="required|min:6"
                                :label="trans('admin::app.users.login.password')"
                                :placeholder="trans('admin::app.users.login.password')"
                            />
                            <span
                                class="login-eye-btn icon-eye-hide"
                                onclick="switchVisibility()"
                                id="visibilityIcon"
                                role="button"
                                tabindex="0"
                            ></span>
                        </div>
                        <x-admin::form.control-group.error control-name="password" />
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="login-submit-btn" aria-label="{{ trans('admin::app.users.login.submit-btn') }}">
                        @lang('admin::app.users.login.submit-btn')
                    </button>

                </x-admin::form>

                {!! view_render_event('admin.sessions.login.form_controls.after') !!}

                <div class="login-footer">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </div>

            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        function switchVisibility() {
            var f = document.getElementById('password');
            var i = document.getElementById('visibilityIcon');
            if (f.type === 'password') {
                f.type = 'text';
                i.classList.remove('icon-eye-hide');
                i.classList.add('icon-eye');
            } else {
                f.type = 'password';
                i.classList.remove('icon-eye');
                i.classList.add('icon-eye-hide');
            }
        }
    </script>
    @endpush

</x-admin::layouts.anonymous>
