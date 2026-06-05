@php($hideNav = true)
@extends('layouts.app')

@section('title', 'Recover Password')

@section('content')

<div class="auth-page" x-data="{
        loading: false,
        submit() { this.loading = true; this.$refs.forgotForm.submit(); }
    }">

    <div class="auth-bg">
        <div class="auth-bg-orb auth-bg-orb-1"></div>
        <div class="auth-bg-orb auth-bg-orb-2"></div>
    </div>

    <div class="auth-card">

        {{-- ─── Left: Brand copy ─── --}}
        <div class="auth-left">
            <a href="{{ url('/') }}" class="auth-logo-link">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="auth-logo-img">
                <span class="auth-logo-name">{{ config('app.name') }}</span>
            </a>
            <div class="auth-brand-copy">
                <h1 class="auth-heading">Forgot Your<br>Password? 🔒</h1>
                <p class="auth-subtext">
                    Enter your email and we'll send you a reset link.<br>
                    Remember it?
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

            @if(session('status'))
                <div class="auth-alert auth-alert-success">{{ session('status') }}</div>
            @endif
            @if($errors->any())
                <div class="auth-alert auth-alert-error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" x-ref="forgotForm" @submit.prevent="submit" class="auth-form">
                @csrf

                {{-- Email --}}
                <div class="auth-field">
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus placeholder="Enter Email Address"
                           class="auth-input">
                    <span class="auth-input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                </div>

                {{-- Submit --}}
                <button type="submit" :disabled="loading" class="auth-btn-primary">
                    <template x-if="!loading"><span>Send Reset Link</span></template>
                    <template x-if="loading">
                        <span class="auth-loading">
                            Sending
                            <span class="auth-dots"><span>.</span><span>.</span><span>.</span></span>
                        </span>
                    </template>
                </button>

                {{-- Back to login --}}
                <div style="text-align:center; margin-top:.5rem;">
                    <a href="{{ route('login') }}" class="auth-back-link">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        Back to Sign In
                    </a>
                </div>

            </form>
        </div>

    </div>
</div>

@include('auth._auth_styles')

<style>
.auth-back-link {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .82rem;
    color: #6366f1;
    font-weight: 600;
    text-decoration: none;
}
.auth-back-link:hover { text-decoration: underline; }
</style>

@endsection