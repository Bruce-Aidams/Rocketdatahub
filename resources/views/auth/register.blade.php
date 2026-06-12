@php
    $hideNav = true;
    /** @var \Illuminate\Support\ViewErrorBag $errors */
@endphp
@extends('layouts.app')

@section('title', 'Register')

@section('content')

{{-- Auth page root view --}}
<div class="auth-viewport" x-data="{
        loading: false,
        showPass: false,
        showConfirm: false,
        submit() { this.loading = true; this.$refs.registerForm.submit(); }
    }">

    {{-- Top Navigation Header --}}
    <div class="w-full max-w-[440px] flex items-center justify-between px-2 mb-6">
        <a href="{{ url('/') }}" class="w-10 h-10 rounded-full bg-white dark:bg-slate-900 shadow-sm flex items-center justify-center border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <span class="text-lg font-black text-slate-850 dark:text-slate-100 tracking-tight">My Account</span>
    </div>

    {{-- Premium Auth Card --}}
    <div class="auth-mobile-card my-6">
        <div class="auth-card-gradient-bar"></div>

        <!-- Avatar Icon -->
        <div class="auth-avatar-wrapper">
            <div class="auth-avatar-inner">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A9.75 9.75 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <h2 class="text-2xl font-black text-slate-850 dark:text-white text-center tracking-tight mb-2">Create Account</h2>
        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-extrabold text-center leading-relaxed mb-8 uppercase tracking-widest">Register to buy high-speed data & manage storefronts.</p>

        <!-- Toggle Pills -->
        <div class="flex bg-slate-100 dark:bg-slate-800 rounded-2xl p-1 mb-8">
            <a href="{{ route('login') }}" class="auth-tab-pill text-slate-400 dark:text-slate-500 hover:text-slate-650">Login</a>
            <a href="{{ route('register') }}" class="auth-tab-pill bg-white dark:bg-slate-700 shadow-sm text-primary">Sign Up</a>
        </div>

        {{-- Alerts --}}
        @if(session('error'))
            <div class="mb-4 p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 rounded-2xl text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 rounded-2xl text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}" x-ref="registerForm" @submit.prevent="submit" class="space-y-4">
            @csrf

            {{-- Full Name --}}
            <div class="auth-input-group">
                <label class="auth-input-label">Full Name</label>
                <div class="auth-input-wrapper">
                    <span class="auth-input-icon-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                           required autofocus placeholder="Full Name"
                           class="auth-input-field">
                </div>
            </div>

            {{-- Email Address --}}
            <div class="auth-input-group">
                <label class="auth-input-label">Email Address</label>
                <div class="auth-input-wrapper">
                    <span class="auth-input-icon-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required placeholder="yourname@email.com"
                           class="auth-input-field">
                </div>
            </div>

            {{-- Phone Number --}}
            <div class="auth-input-group">
                <label class="auth-input-label">Phone Number</label>
                <div class="auth-input-wrapper">
                    <span class="auth-input-icon-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </span>
                    <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                           required placeholder="e.g. 0546648126"
                           class="auth-input-field">
                </div>
            </div>

            {{-- Password --}}
            <div class="auth-input-group">
                <label class="auth-input-label">Password</label>
                <div class="auth-input-wrapper">
                    <span class="auth-input-icon-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input id="password" :type="showPass ? 'text' : 'password'" name="password"
                           required placeholder="Create password"
                           class="auth-input-field">
                    <button type="button" @click="showPass = !showPass" class="auth-input-icon-right">
                        <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m4.066-1.426A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div class="auth-input-group">
                <label class="auth-input-label">Confirm Password</label>
                <div class="auth-input-wrapper">
                    <span class="auth-input-icon-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                           required placeholder="Confirm password"
                           class="auth-input-field">
                    <button type="button" @click="showConfirm = !showConfirm" class="auth-input-icon-right">
                        <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="showConfirm" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.04m4.066-1.426A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
            </div>

            {{-- Referral Code --}}
            {{-- <div class="auth-input-group">
                <label class="auth-input-label">Referral Code (Optional)</label>
                <div class="auth-input-wrapper">
                    <span class="auth-input-icon-left">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </span>
                    <input id="referral_code" type="text" name="referral_code" value="{{ old('referral_code') }}"
                           placeholder="Referral code if any"
                           class="auth-input-field">
                </div>
            </div> --}}

            {{-- Terms Checkbox --}}
            <div class="flex items-center gap-3 py-2">
                <input type="checkbox" name="terms" id="terms" required class="w-5 h-5 accent-primary cursor-pointer rounded-lg border-slate-200">
                <label for="terms" class="text-xs font-semibold text-slate-500 dark:text-slate-400 cursor-pointer select-none">
                    I agree to the <a href="#" class="text-primary hover:underline">Privacy Policy & Terms</a>
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit" :disabled="loading" class="auth-submit-btn mt-6">
                <template x-if="!loading"><span>Create Account</span></template>
                <template x-if="loading">
                    <span class="flex items-center gap-1 animate-pulse-text">
                        Creating...
                    </span>
                </template>
            </button>

            <!-- {{-- Divider --}}
            <div class="pt-2">
                <div class="flex items-center gap-3 my-4">
                    <span class="flex-1 h-px bg-slate-100 dark:bg-slate-800"></span>
                    <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">Or register with</span>
                    <span class="flex-1 h-px bg-slate-100 dark:bg-slate-800"></span>
                </div>

                {{-- Social Buttons --}}
                <div class="flex justify-center items-center gap-4">
                    <a href="{{ route('google.login') }}" class="w-12 h-12 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Bottom Navigation Bar --}} -->
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