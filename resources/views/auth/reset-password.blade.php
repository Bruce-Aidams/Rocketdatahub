@php
    $hideNav = true;
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp
@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

{{-- Auth page root view --}}
<div class="auth-viewport" x-data="{
        loading: false,
        showPass: false,
        showConfirm: false,
        submit() { this.loading = true; this.$refs.resetForm.submit(); }
    }">

    {{-- Top Navigation Header --}}
    <div class="w-full max-w-[440px] flex items-center justify-between px-2 mb-6">
        <a href="{{ route('login') }}" class="w-10 h-10 rounded-full bg-white dark:bg-slate-900 shadow-sm flex items-center justify-center border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <span class="text-lg font-black text-slate-855 dark:text-slate-100 tracking-tight">Reset Password</span>
    </div>

    {{-- Premium Auth Card --}}
    <div class="auth-mobile-card">
        <div class="auth-card-gradient-bar"></div>

        <!-- Avatar Icon -->
        <div class="auth-avatar-wrapper">
            <div class="auth-avatar-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.306 7M7 9h8V1" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-black text-slate-850 dark:text-white text-center tracking-tight mb-2">Reset Password</h2>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-extrabold text-center leading-relaxed mb-8 uppercase tracking-widest">Enter your email and configure your new secure password.</p>

        {{-- Alerts --}}
        @if($errors->any())
            <div class="mb-4 p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 rounded-2xl text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" x-ref="resetForm" @submit.prevent="submit" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email address --}}
            <div class="auth-input-group">
                <label class="auth-input-label">Email Address</label>
                <div class="auth-input-wrapper">
                    <span class="auth-input-icon-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}"
                           required autofocus placeholder="yourname@email.com"
                           class="auth-input-field">
                </div>
            </div>

            {{-- New Password --}}
            <div class="auth-input-group">
                <label class="auth-input-label">New Password</label>
                <div class="auth-input-wrapper">
                    <span class="auth-input-icon-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input id="password" :type="showPass ? 'text' : 'password'" name="password"
                           required placeholder="New password"
                           class="auth-input-field">
                    <button type="button" @click="showPass = !showPass" class="auth-input-icon-right">
                        <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m4.066-1.426A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="auth-input-group">
                <label class="auth-input-label">Confirm New Password</label>
                <div class="auth-input-wrapper">
                    <span class="auth-input-icon-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                           required placeholder="Confirm new password"
                           class="auth-input-field">
                    <button type="button" @click="showConfirm = !showConfirm" class="auth-input-icon-right">
                        <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showConfirm" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m4.066-1.426A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" :disabled="loading" class="auth-submit-btn mt-6">
                <template x-if="!loading"><span>Reset Password</span></template>
                <template x-if="loading">
                    <span class="flex items-center gap-1 animate-pulse-text">
                        Resetting...
                    </span>
                </template>
            </button>
        </form>
    </div>

    {{-- Bottom Navigation Bar --}}
    <!-- <div class="auth-bottom-nav">
        <a href="{{ url('/') }}" class="auth-bottom-nav-item">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Home</span>
        </a>
        <a href="{{ url('/#features') }}" class="auth-bottom-nav-item">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span>Services</span>
        </a>
        <a href="{{ route('orders.new') }}" class="auth-bottom-nav-book-btn">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
        </a>
        <a href="{{ url('/#pricing') }}" class="auth-bottom-nav-item">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span>Shop</span>
        </a>
        <a href="{{ route('dashboard') }}" class="auth-bottom-nav-item active">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
            <span>Account</span>
        </a>
    </div> -->
</div>

@include('auth._auth_styles')

@endsection