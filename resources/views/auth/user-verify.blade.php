@php
    $hideNav = true;
@endphp
@extends('layouts.app')

@section('title', 'Verification Required')

@section('content')

{{-- Auth page root view --}}
<div class="auth-viewport" x-data="{ requested: {{ session('success') ? 'true' : 'false' }} }">

    {{-- Top Navigation Header --}}
    <div class="w-full max-w-[440px] flex items-center justify-between px-2 mb-6">
        <form action="{{ route('logout') }}" method="POST" id="logout-form">
            @csrf
            <button type="submit" class="w-10 h-10 rounded-full bg-white dark:bg-slate-900 shadow-sm flex items-center justify-center border border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors cursor-pointer">
                <svg class="w-5 h-5 text-slate-650 dark:text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
        <span class="text-lg font-black text-slate-850 dark:text-slate-100 tracking-tight">Access Restricted</span>
    </div>

    {{-- Premium Auth Card --}}
    <div class="auth-mobile-card">
        <div class="auth-card-gradient-bar"></div>

        <!-- Avatar Icon (Displays user profile initials avatar) -->
        <div class="auth-avatar-wrapper">
            <div class="auth-avatar-inner border-2 border-primary/20">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=696CFF&background=F1F5F9&size=128&font-size=0.35&bold=true"
                     alt="Profile Picture"
                     class="w-full h-full object-cover">
            </div>
        </div>

        <h2 class="text-2xl font-black text-slate-850 dark:text-white text-center tracking-tight mb-2">Access Restricted</h2>
        <p class="text-[10px] text-primary font-black text-center uppercase tracking-[0.2em] mb-6">Verification Pending</p>

        <div class="space-y-6 text-center">
            <p class="text-slate-500 dark:text-slate-400 text-xs leading-relaxed max-w-sm mx-auto">
                Your account is currently in a <strong>Pending Verification</strong> state. To maintain the security of our data ecosystem, all new accounts must be manually approved by our security team.
            </p>

            <!-- Status Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/30 rounded-2xl mx-auto">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                </span>
                <span class="text-[9px] font-black uppercase tracking-widest text-amber-600 dark:text-amber-450">Awaiting Approval</span>
            </div>

            <!-- Action Button -->
            <div class="pt-2">
                <template x-if="!requested">
                    <form action="{{ route('verification.request') }}" method="POST">
                        @csrf
                        <button type="submit" class="auth-submit-btn">
                            <span>Request Activation</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>
                </template>
                <template x-if="requested">
                    <div class="space-y-3">
                        <div class="px-6 py-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-450 rounded-2xl border border-emerald-500/20 text-[10px] font-black uppercase tracking-widest">
                            Request Submitted
                        </div>
                        <p class="text-slate-400 dark:text-slate-500 text-[10px] font-bold tracking-wide">Expected approval time: 2-4 hours</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Bottom Navigation Bar --}}
    <div class="auth-bottom-nav">
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
    </div>
</div>

@include('auth._auth_styles')

@endsection