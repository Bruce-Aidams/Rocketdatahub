@php
    $hideNav = true;
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp
@extends('layouts.app')

@section('title', 'Register')

@section('content')

<div class="auth-page" x-data="{
        loading: false,
        showPass: false,
        showConfirm: false,
        submit() { this.loading = true; this.$refs.registerForm.submit(); }
    }">

    <div class="auth-bg">
        <div class="auth-bg-orb auth-bg-orb-1"></div>
        <div class="auth-bg-orb auth-bg-orb-2"></div>
    </div>

    <div class="auth-card auth-card-wide">

        {{-- ─── Left: Brand copy ─── --}}
        <div class="auth-left">
            <a href="{{ url('/') }}" class="auth-logo-link">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="auth-logo-img">
                <span class="auth-logo-name">{{ config('app.name') }}</span>
            </a>
            <div class="auth-brand-copy">
                <h1 class="auth-heading">Create Your<br>Account</h1>
                <p class="auth-subtext">
                    Join thousands of happy customers.<br>
                    Already have an account?
                    <a href="{{ route('login') }}" class="auth-link-blue">Sign in here!</a>
                </p>
            </div>
        </div>

        {{-- ─── Center: 3D Character ─── --}}
        <div class="auth-character">
            <img src="{{ asset('images/auth-character.png') }}"
                 alt="Floating character"
                 class="auth-char-img"
                 onerror="this.style.display='none'">
        </div>

        {{-- ─── Right: Form ─── --}}
        <div class="auth-right">

            @if(session('error'))
                <div class="auth-alert auth-alert-error">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="auth-alert auth-alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}" x-ref="registerForm" @submit.prevent="submit" class="auth-form">
                @csrf

                {{-- Name + Email row --}}
                <div class="auth-row-2">
                    <div class="auth-field">
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                               required autofocus placeholder="Full Name"
                               class="auth-input">
                        <span class="auth-input-icon">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                    </div>
                    <div class="auth-field">
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               required placeholder="Email Address"
                               class="auth-input">
                        <span class="auth-input-icon">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                    </div>
                </div>

                {{-- Phone --}}
                <div class="auth-field">
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                           required placeholder="Phone Number (e.g. 054XXXXXXX)"
                           class="auth-input">
                    <span class="auth-input-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </span>
                </div>

                {{-- Password + Confirm row --}}
                <div class="auth-row-2">
                    <div class="auth-field">
                        <input id="password" :type="showPass ? 'text' : 'password'" name="password"
                               required placeholder="••••••••"
                               class="auth-input">
                        <button type="button" @click="showPass = !showPass" class="auth-input-icon auth-eye-btn">
                            <svg x-show="!showPass" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPass" x-cloak width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m4.066-1.426A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <div class="auth-field">
                        <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                               required placeholder="Confirm Password"
                               class="auth-input">
                        <button type="button" @click="showConfirm = !showConfirm" class="auth-input-icon auth-eye-btn">
                            <svg x-show="!showConfirm" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showConfirm" x-cloak width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m4.066-1.426A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Referral code --}}
                <div class="auth-field">
                    <input id="referral_code" type="text" name="referral_code" value="{{ old('referral_code') }}"
                           placeholder="Referral Code (Optional)"
                           class="auth-input">
                    <span class="auth-input-icon">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </span>
                </div>

                {{-- Terms --}}
                <div class="auth-terms">
                    <input type="checkbox" name="terms" id="terms" required class="auth-checkbox">
                    <label for="terms" class="auth-terms-label">
                        I agree to the <a href="#" class="auth-link-blue">privacy policy & terms</a>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" :disabled="loading" class="auth-btn-primary">
                    <template x-if="!loading"><span>Create Account</span></template>
                    <template x-if="loading">
                        <span class="auth-loading">
                            Registering
                            <span class="auth-dots"><span>.</span><span>.</span><span>.</span></span>
                        </span>
                    </template>
                </button>

                {{-- Divider --}}
                <div class="auth-divider">
                    <span class="auth-divider-line"></span>
                    <span class="auth-divider-text">Or continue with</span>
                    <span class="auth-divider-line"></span>
                </div>

                {{-- Social --}}
                <div class="auth-social-row">
                    <a href="{{ route('google.login') }}" class="auth-social-btn" title="Google">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                    </a>
                    <a href="{{ url('/') }}" class="auth-social-btn auth-social-apple" title="Apple">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                    </a>
                    <a href="{{ url('/') }}" class="auth-social-btn auth-social-facebook" title="Facebook">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877F2">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>

@include('auth._auth_styles')

<style>
/* ── Register-specific extras ── */
.auth-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .65rem;
}
.auth-card-wide {
    max-width: 1080px;
}
.auth-terms {
    display: flex;
    align-items: center;
    gap: .5rem;
}
.auth-checkbox {
    width: 15px; height: 15px;
    accent-color: #6366f1;
    cursor: pointer;
    flex-shrink: 0;
}
.auth-terms-label {
    font-size: .8rem;
    color: #6b7280;
    cursor: pointer;
}
/* Collapse side-by-side fields on narrow screens */
@media (max-width: 640px) {
    .auth-row-2 { grid-template-columns: 1fr; }
}
/* Also collapse on short/medium viewports to prevent overflow */
@media (max-height: 820px) and (min-width: 901px) {
    .auth-row-2 { gap: .5rem; }
    .auth-form   { gap: .65rem; }
}
</style>

@endsection