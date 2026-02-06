<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $reseller->name }}'s Data Hub - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen relative">

    <div x-data="purchasePage">

        {{-- Toast Notification --}}
        {{-- Toast Notification --}}
        <div x-show="toast.visible" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-[150] min-w-[320px] max-w-sm w-full px-4"
            style="display: none;">

            <div :class="toast.type === 'success' ? 'bg-slate-900/90 dark:bg-white/90 text-white dark:text-slate-900 border-white/10' : 'bg-red-500/90 text-white border-red-400/20'"
                class="px-6 py-4 rounded-2xl shadow-2xl backdrop-blur-md border flex items-center gap-4">

                <div x-show="toast.type === 'success'"
                    class="shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center shadow-lg shadow-green-500/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                <div x-show="toast.type === 'error'"
                    class="shrink-0 w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest opacity-80"
                        x-text="toast.type === 'success' ? 'Success' : 'Error'"></h4>
                    <p class="text-sm font-bold leading-tight mt-0.5" x-text="toast.message"></p>
                </div>
            </div>
        </div>

        {{-- Confirmation Modal --}}
        <template x-teleport="body">
            <div x-show="confirmModal.visible" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="confirmModal.visible = false"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-2xl animate-in zoom-in-95 duration-200"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0">

                    <div class="p-6">
                        <div
                            class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Confirm Purchase?</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 leading-relaxed">
                            You are about to purchase <span
                                class="font-bold text-slate-900 dark:text-white block mt-1 text-base"
                                x-text="confirmModal.bundleName"></span>
                            for <span class="font-mono font-bold text-primary block mt-1 text-lg" x-text="phone"></span>
                        </p>

                        <div class="flex gap-4">
                            <button @click="confirmModal.visible = false"
                                class="flex-1 h-12 rounded-xl font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                                Cancel
                            </button>
                            <button @click="processPurchase"
                                class="flex-1 h-12 rounded-xl font-bold bg-primary text-white shadow-lg shadow-primary/25 hover:shadow-primary/40 hover:bg-primary-focus transition-all active:scale-95">
                                Confirm Purchase
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div
            class="max-w-6xl mx-auto px-4 md:px-6 py-8 md:py-20 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">

            {{-- Back Button --}}
            <a href="{{ route('store.show', $reseller->referral_code) }}"
                class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Networks
            </a>

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-colors duration-500 shadow-sm"
                        :class="getNetworkColor(selectedNetwork, 'icon-active')">
                        <span class="text-lg font-black" x-text="selectedNetwork.substring(0,1)"></span>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-blue-900 dark:text-white">Buy <span
                                x-text="selectedNetwork"></span> Data</h2>
                        <p class="text-slate-500 dark:text-slate-400 font-medium">Select a package and pay securely.</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-5">
                {{-- Form Section --}}
                <div class="md:col-span-3">
                    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm h-full overflow-hidden transition-colors duration-500"
                        :class="getNetworkColor(selectedNetwork, 'border')">
                        <div
                            class="p-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="text-lg font-bold text-blue-900 dark:text-white">Order Details</h3>
                            <p class="text-sm text-slate-500">Managed by {{ $reseller->store_name ?? $reseller->name }}
                            </p>
                        </div>
                        <div class="p-6">
                            <form @submit.prevent="handleSubmit" class="space-y-6">

                                {{-- Step 1: Email (Added for Guest) --}}
                                <div class="space-y-2">
                                    <label
                                        class="text-sm font-semibold flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                        <div
                                            class="w-6 h-6 bg-slate-100 dark:bg-slate-800 rounded-md flex items-center justify-center text-[10px] font-bold">
                                            1</div>
                                        Your Email Address
                                    </label>
                                    <input type="email" x-model="email" placeholder="customer@example.com"
                                        class="w-full h-14 pl-4 pr-4 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-primary/10 outline-none transition-all font-bold text-sm"
                                        :class="getNetworkColor(selectedNetwork, 'focus-border')">
                                </div>

                                {{-- Step 2: Select Bundle --}}
                                <div class="space-y-2" x-ref="step2">
                                    <label
                                        class="text-sm font-semibold flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                        <div
                                            class="w-6 h-6 bg-slate-100 dark:bg-slate-800 rounded-md flex items-center justify-center text-[10px] font-bold">
                                            2</div>
                                        Select Data Bundle
                                    </label>
                                    <div class="relative group">
                                        <select x-model="selectedBundleId"
                                            class="w-full h-14 pl-4 pr-10 bg-slate-50 dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-xl focus:ring-4 focus:ring-primary/10 outline-none appearance-none transition-all font-bold text-sm cursor-pointer"
                                            :class="getNetworkColor(selectedNetwork, 'focus-border')">
                                            <option value="" disabled selected>Choose a package...</option>
                                            <template x-for="bundle in bundles" :key="bundle.id">
                                                <option :value="bundle.id"
                                                    x-text="bundle.name + ' (' + bundle.data_amount + ') - GHS ' + parseFloat(bundle.display_price).toFixed(2)">
                                                </option>
                                            </template>
                                        </select>
                                        <div
                                            class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Step 3: Recipient Phone --}}
                                <div class="space-y-2 transition-all duration-300" x-ref="step3"
                                    :class="!selectedBundleId ? 'opacity-50 pointer-events-none' : ''">
                                    <label
                                        class="text-sm font-semibold flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                        <div
                                            class="w-6 h-6 bg-slate-100 dark:bg-slate-800 rounded-md flex items-center justify-center text-[10px] font-bold">
                                            3</div>
                                        Recipient Phone Number
                                    </label>
                                    <div class="relative group">
                                        <input type="tel" x-model="phone" @input="validatePhone"
                                            placeholder="0XXXXXXXXX" maxlength="10"
                                            class="w-full h-14 pl-4 pr-10 bg-slate-50 dark:bg-slate-800 border-2 rounded-xl outline-none transition-all font-mono font-bold text-lg tracking-wider"
                                            :class="phoneState.colorClass">

                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 transition-colors duration-300"
                                            :class="phoneState.iconColor">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path x-show="phoneState.valid" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                <path x-show="!phoneState.valid" stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-start">
                                        <div class="text-[10px] text-slate-500 transition-all">
                                            Valid Prefixes: <span class="font-bold"
                                                x-text="getNetworkPrefixes(selectedNetwork)"></span>
                                        </div>
                                        <div x-show="phone.length > 0" class="text-[10px] font-bold"
                                            :class="phoneState.valid ? 'text-emerald-500' : 'text-red-500'"
                                            x-text="phoneState.message">
                                        </div>
                                    </div>
                                </div>

                                {{-- Pay Button --}}
                                <button type="submit"
                                    :disabled="isLoading || !selectedBundleId || !phoneState.valid || !isValidEmail"
                                    class="w-full h-16 text-lg shadow-xl transition-all active:scale-[0.98] rounded-2xl font-black text-white flex items-center justify-center gap-3 group disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                                    :class="isLoading ? 'bg-slate-400 cursor-wait' : getNetworkColor(selectedNetwork, 'btn')">
                                    <span x-show="!isLoading">Confirm & Pay</span>
                                    <span x-show="isLoading" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        Processing...
                                    </span>
                                    <svg x-show="!isLoading"
                                        class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Live Preview Section --}}
                <div class="hidden md:block md:col-span-2 space-y-6">
                    {{-- Preview Card --}}
                    <div class="text-white rounded-2xl overflow-hidden relative shadow-2xl min-h-[300px] flex flex-col justify-between border-none transition-colors duration-500"
                        :class="getNetworkColor(selectedNetwork, 'card-bg')">
                        <div
                            class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none">
                        </div>
                        <div
                            class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full blur-2xl translate-y-1/4 -translate-x-1/4 pointer-events-none">
                        </div>

                        <div class="p-8 relative z-10">
                            <h4 class="text-xs font-black uppercase tracking-[0.2em] opacity-60 mb-2">Invoice Preview
                            </h4>
                            <h3 class="text-2xl font-bold">New Order</h3>

                            <div class="space-y-6 mt-8">
                                <div class="space-y-1">
                                    <p class="text-xs font-bold uppercase opacity-60">Network Provider</p>
                                    <p class="text-lg font-bold" x-text="selectedNetwork"></p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs font-bold uppercase opacity-60">Package Bundle</p>
                                    <p class="text-lg font-bold truncate"
                                        x-text="selectedBundle ? selectedBundle.name : '---'"></p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs font-bold uppercase opacity-60">Recipient</p>
                                    <p class="text-xl font-mono font-bold" x-text="phone || '---'"></p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 bg-black/20 backdrop-blur-sm relative z-10">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold opacity-80">Total Payable</span>
                                <span class="text-3xl font-black">GHS <span
                                        x-text="selectedBundle ? parseFloat(selectedBundle.display_price).toFixed(2) : '0.00'"></span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Form for Paystack Redirect -->
    <form id="paystack-form" action="{{ route('store.purchase') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="reseller_code" value="{{ $reseller->referral_code }}">
        <input type="hidden" name="bundle_id" id="form-bundle-id">
        <input type="hidden" name="email" id="form-email">
        <input type="hidden" name="phone" id="form-phone">
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('purchasePage', () => ({
                bundles: {!! $bundles->toJson() !!},
                selectedNetwork: '{{ $network }}',
                selectedBundleId: '',
                phone: '',
                email: '',
                isLoading: false,
                toast: { visible: false, message: '', type: 'success' },
                confirmModal: { visible: false, bundleName: '' },

                init() {
                    this.$watch('confirmModal.visible', value => {
                        if (value) document.body.classList.add('overflow-hidden');
                        else document.body.classList.remove('overflow-hidden');
                    });
                },

                phoneState: { valid: false, message: '', colorClass: 'border-slate-200 dark:border-slate-700', iconColor: 'text-slate-400' },

                get selectedBundle() {
                    return this.bundles.find(b => b.id == this.selectedBundleId) || null;
                },

                get isValidEmail() {
                    return /^\S+@\S+\.\S+$/.test(this.email);
                },

                showToast(message, type = 'success') {
                    this.toast.message = message;
                    this.toast.type = type;
                    this.toast.visible = true;
                    setTimeout(() => this.toast.visible = false, 3000);
                },

                getNetworkColor(network, type) {
                    const net = (network || '').toUpperCase();
                    let colors = {
                        'bg': 'bg-primary/10',
                        'text': 'text-primary',
                        'border': 'border-slate-200 dark:border-slate-800',
                        'active-btn': 'border-primary bg-primary/5 text-primary',
                        'icon-active': 'bg-primary text-white shadow-primary/20',
                        'focus-border': 'focus:border-primary',
                        'btn': 'bg-primary shadow-primary/30 hover:shadow-primary/40',
                        'card-bg': 'bg-slate-900'
                    };

                    if (net === 'MTN') {
                        colors = {
                            'bg': 'bg-yellow-400/10',
                            'text': 'text-yellow-800',
                            'border': 'border-yellow-200 dark:border-yellow-900/30',
                            'active-btn': 'border-yellow-400 bg-yellow-400 text-black shadow-lg shadow-yellow-400/20',
                            'icon-active': 'bg-yellow-400 text-black shadow-yellow-400/20',
                            'focus-border': 'focus:border-yellow-400 focus:ring-yellow-400/20',
                            'btn': 'bg-yellow-400 text-black hover:bg-yellow-300 shadow-yellow-400/30 hover:shadow-yellow-400/40',
                            'card-bg': 'bg-gradient-to-br from-yellow-500 to-yellow-600 text-black'
                        };
                    } else if (net === 'TELECEL') {
                        colors = {
                            'bg': 'bg-red-500/10',
                            'text': 'text-red-500',
                            'border': 'border-red-200 dark:border-red-900/30',
                            'active-btn': 'border-red-600 bg-red-600 text-white shadow-lg shadow-red-600/20',
                            'icon-active': 'bg-red-600 text-white shadow-red-600/20',
                            'focus-border': 'focus:border-red-500 focus:ring-red-500/20',
                            'btn': 'bg-red-600 text-white hover:bg-red-500 shadow-red-600/30 hover:shadow-red-600/40',
                            'card-bg': 'bg-gradient-to-br from-red-600 to-red-700'
                        };
                    } else if (net === 'AT' || net === 'AIRTELTIGO') {
                        colors = {
                            'bg': 'bg-blue-600/10',
                            'text': 'text-blue-600',
                            'border': 'border-blue-200 dark:border-blue-900/30',
                            'active-btn': 'border-blue-600 bg-blue-600 text-white shadow-lg shadow-blue-600/20',
                            'icon-active': 'bg-blue-600 text-white shadow-blue-600/20',
                            'focus-border': 'focus:border-blue-500 focus:ring-blue-500/20',
                            'btn': 'bg-blue-600 text-white hover:bg-blue-500 shadow-blue-600/30 hover:shadow-blue-600/40',
                            'card-bg': 'bg-gradient-to-br from-blue-600 to-blue-700'
                        };
                    }

                    return colors[type] || '';
                },

                getNetworkPrefixes(network) {
                    const net = (network || '').toUpperCase();
                    switch (net) {
                        case "MTN": return "024, 054, 055, 059, 053, 025";
                        case "TELECEL": return "020, 050";
                        case "AIRTELTIGO": case "AT": return "027, 057, 026, 056";
                        default: return "";
                    }
                },

                validatePhone() {
                    const phoneDigits = this.phone.replace(/\D/g, '');

                    if (phoneDigits.length === 0) {
                        this.phoneState = { valid: false, message: '', colorClass: 'border-slate-200 dark:border-slate-700', iconColor: 'text-slate-400' };
                        return;
                    }

                    if (phoneDigits.length !== 10) {
                        this.phoneState = { valid: false, message: 'Must be 10 digits', colorClass: 'border-red-500 focus:ring-red-500/10', iconColor: 'text-red-500' };
                        return;
                    }

                    const validPrefixes = this.getNetworkPrefixes(this.selectedNetwork).split(', ').map(p => p.trim());
                    const hasValidPrefix = validPrefixes.some(prefix => this.phone.startsWith(prefix));

                    if (hasValidPrefix) {
                        this.phoneState = { valid: true, message: 'Valid Number', colorClass: 'border-emerald-500 ring-2 ring-emerald-500/10', iconColor: 'text-emerald-500' };
                    } else {
                        this.phoneState = { valid: false, message: 'Invalid ' + this.selectedNetwork + ' Prefix', colorClass: 'border-red-500 ring-2 ring-red-500/10', iconColor: 'text-red-500' };
                    }
                },

                handleSubmit() {
                    // Final Validation Check
                    this.validatePhone();
                    if (!this.selectedBundleId) { this.showToast('Please select a bundle.', 'error'); return; }
                    if (!this.phoneState.valid) { this.showToast(this.phoneState.message || 'Invalid Phone Number', 'error'); return; }
                    if (!this.isValidEmail) { this.showToast('Please enter a valid email address.', 'error'); return; }

                    // Show Confirmation Modal
                    this.confirmModal.bundleName = this.selectedBundle.name;
                    this.confirmModal.visible = true;
                },

                async processPurchase() {
                    // Submit the real form
                    document.getElementById('form-bundle-id').value = this.selectedBundleId;
                    document.getElementById('form-phone').value = this.phone;
                    document.getElementById('form-email').value = this.email;
                    document.getElementById('paystack-form').submit();
                    this.isLoading = true;
                }
            }));
        });
    </script>
</body>

</html>