@extends('layouts.admin')

@section('title', 'API Management')

@section('content')
    <div class="space-y-6 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-700"
        x-data="{ 
                                                                                                                                                                                    tab: 'providers',
                                                                                                                                                                                    modalOpen: false, 
                                                                                                                                                                                    modalTab: 'general',
                                                                                                                                                                                    editMode: false,
                                                                                                                                                                                    provider: { id: '', name: '', network_type: '', base_url: '', request_method: 'POST', request_headers: '', request_body: '', webhook_url: '', api_key: '', secret_key: '', is_active: true },
                                                                                                                                                                                    resetForm() {
                                                                                                                                                                                        this.provider = { id: '', name: '', network_type: '', base_url: '', request_method: 'POST', request_headers: '', request_body: '', webhook_url: '', api_key: '', secret_key: '', is_active: true };
                                                                                                                                                                                        this.editMode = false;
                                                                                                                                                                                        this.modalTab = 'general';
                                                                                                                                                                                    },
                                                                                                                                                                                    openAdd() {
                                                                                                                                                                                        this.resetForm();
                                                                                                                                                                                        this.modalOpen = true;
                                                                                                                                                                                    },
                                                                                                                                                                                    openEdit(p) {
                                                                                                                                                                                        this.provider = JSON.parse(JSON.stringify(p));
                                                                                                                                                                                        this.provider.request_headers = p.request_headers ? JSON.stringify(p.request_headers, null, 2) : '';
                                                                                                                                                                                        this.provider.request_body = p.request_body ? JSON.stringify(p.request_body, null, 2) : '';
                                                                                                                                                                                        this.editMode = true;
                                                                                                                                                                                        this.modalTab = 'general';
                                                                                                                                                                                        this.modalOpen = true;
                                                                                                                                                                                    }
                                                                                                                                                                                }">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 rounded-xl bg-cyan-500/10 flex items-center justify-center text-cyan-500 ring-1 ring-cyan-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-blue-900 dark:text-white">API Management</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage external provider connections and
                        transaction logs.</p>
                </div>
            </div>

            <button x-show="tab === 'providers'" @click="openAdd()"
                class="h-11 px-6 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/20 hover:opacity-90 active:scale-95 transition-all flex items-center gap-2 whitespace-nowrap border-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Provider
            </button>
        </div>

        <!-- Tab Controls -->
        <div class="flex gap-2 p-1 bg-slate-100 dark:bg-slate-800/50 rounded-xl w-fit overflow-x-auto">
            <button @click="tab = 'providers'"
                :class="tab === 'providers' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">
                Active Providers
            </button>
            <button @click="tab = 'user_keys'"
                :class="tab === 'user_keys' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">
                User Keys
            </button>
            <button @click="tab = 'connectivity'"
                :class="tab === 'connectivity' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">
                Connectivity (Webhooks)
            </button>
            <button @click="tab = 'logs'"
                :class="tab === 'logs' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">
                System Logs
            </button>
            <button @click="tab = 'docs'"
                :class="tab === 'docs' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">
                Documentation
            </button>
        </div>

        <!-- Connectivity View -->
        <div x-show="tab === 'connectivity'" class="space-y-6">
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
                <div class="flex items-center gap-4 mb-10">
                    <div
                        class="w-12 h-12 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 transition-transform hover:scale-105">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-slate-900 dark:text-white">Global Webhooks</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-500 mt-1 uppercase tracking-widest font-bold">
                            External Event Integration</p>
                    </div>
                </div>

                <div class="space-y-8 max-w-3xl">
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div class="space-y-1.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[.2em]">Webhook
                                Endpoint URL</label>
                            <input type="url" name="webhook_url" id="webhook_url"
                                value="{{ \App\Models\Setting::where('key', 'webhook_url')->first()?->value }}"
                                placeholder="https://external-service.com/api/webhook"
                                class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm font-mono focus:ring-2 focus:ring-primary/20 outline-none transition-all dark:text-white">
                            <p class="text-[9px] text-slate-400 italic">Target URL for automated outbound POST requests.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[.2em]">Signing
                                Secret Key</label>
                            <input type="text" name="webhook_secret" id="webhook_secret"
                                value="{{ \App\Models\Setting::where('key', 'webhook_secret')->first()?->value }}"
                                placeholder="WH_SECRET_KEY..."
                                class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm font-mono focus:ring-2 focus:ring-primary/20 outline-none transition-all dark:text-white">
                        </div>
                    </div>

                    <div
                        class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                        <h5 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-5">
                            Events to Monitor</h5>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach(['Order Created', 'Order Completed', 'Order Failed', 'Deposit Successful', 'Manual Adjustment'] as $event)
                                <label
                                    class="flex items-center p-3.5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl cursor-pointer hover:border-primary/50 transition-all group">
                                    <input type="checkbox"
                                        class="w-4 h-4 text-primary rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:ring-primary/20">
                                    <span
                                        class="ml-3 text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-primary transition-colors">{{ $event }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="button"
                            @click="async () => {
                                                                                                                                                                                const url = document.getElementById('webhook_url').value;
                                                                                                                                                                                const secret = document.getElementById('webhook_secret').value;
                                                                                                                                                                                const response = await fetch('{{ route('admin.settings.update') }}', {
                                                                                                                                                                                    method: 'PUT',
                                                                                                                                                                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                                                                                                                                                                                    body: JSON.stringify({ settings: { webhook_url: url, webhook_secret: secret } })
                                                                                                                                                                                });
                                                                                                                                                                                if(response.ok) window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Webhook configuration synchronized!', type: 'success' } }));
                                                                                                                                                                                else window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Failed to save webhook settings.', type: 'error' } }));
                                                                                                                                                                            }"
                            class="h-12 px-8 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-2xl font-bold text-xs uppercase shadow-lg shadow-emerald-500/20 hover:opacity-90 active:scale-[0.98] transition-all flex items-center gap-3 border-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                </path>
                            </svg>
                            Save Infrastructure Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Providers View -->
        <div x-show="tab === 'providers'" class="space-y-6">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse($providers as $p)
                        <div
                            class="bg-white dark:bg-slate-900 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col hover:shadow-md transition-all group overflow-hidden relative">
                            <!-- Status Toggle & Network Badge -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest
                                                                                                                                                                                                                    {{ $p->network_type === 'MTN' ? 'bg-amber-100 text-amber-600' :
                    ($p->network_type === 'TELECEL' ? 'bg-rose-100 text-rose-600' :
                        ($p->network_type === 'AT' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500')) }}">
                                        {{ $p->network_type ?: 'Global' }}
                                    </div>
                                </div>

                                <button @click="async () => {
                                                    const newState = !{{ $p->is_active ? 'true' : 'false' }};
                                                    const response = await fetch('{{ route('admin.api.providers.update', $p->id) }}', {
                                                        method: 'PUT',
                                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                                        body: JSON.stringify({ is_active: newState })
                                                    });
                                                    if(response.ok) {
                                                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Provider status updated!', type: 'success' } }));
                                                        setTimeout(() => window.location.reload(), 500);
                                                    } else {
                                                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Update failed.', type: 'error' } }));
                                                    }
                                                }"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none {{ $p->is_active ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }}">
                                    <span class="sr-only">Toggle Status</span>
                                    <span
                                        class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform {{ $p->is_active ? 'translate-x-5' : 'translate-x-1' }}"></span>
                                </button>
                            </div>

                            <div class="space-y-1 mb-6">
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white leading-tight truncate">{{ $p->name }}
                                </h4>
                                <p class="text-[10px] text-slate-500 font-mono truncate opacity-60">{{ $p->base_url }}</p>
                            </div>

                            <div class="mt-auto pt-4 flex items-center gap-2 border-t border-slate-50 dark:border-slate-800/50">
                                <button @click="openEdit({{ $p->toJson() }})"
                                    class="flex-1 h-10 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 shadow-md shadow-indigo-500/10 transition-all border-none">
                                    Config
                                </button>
                                <form action="{{ route('admin.api.providers.destroy', $p->id) }}" method="POST"
                                    onsubmit="return confirm('Terminate this provider connection?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-10 h-10 flex items-center justify-center text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-xl transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                @empty
                    <div class="col-span-full py-24 text-center">
                        <div
                            class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">No Providers Found</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-500 mt-2 max-w-xs mx-auto">Establish a new API
                            connection to begin processing transactions.</p>
                        <button @click="openAdd()"
                            class="mt-8 px-6 py-3 bg-primary text-white rounded-2xl font-bold text-xs uppercase shadow-xl shadow-primary/20 hover:opacity-90 transition-all">Add
                            Provider</button>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- User Keys View -->
        <div x-show="tab === 'user_keys'" class="space-y-6">
            <div
                class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
                <div class="px-8 py-6 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">User API Keys</h3>
                    <p class="text-[10px] text-slate-500 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5">
                        Manage user-generated access tokens</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    User</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    Key Name</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    Preview</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    Created</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800 font-medium">
                            @forelse($userKeys as $key)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-500 border border-slate-200 dark:border-slate-700">
                                                {{ substr($key->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-slate-900 dark:text-white">
                                                    {{ $key->user->name ?? 'Unknown User' }}
                                                </p>
                                                <p class="text-[10px] text-slate-500">{{ $key->user->email ?? 'No Email' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-700 dark:text-slate-300">{{ $key->name }}</td>
                                    <td class="px-6 py-4">
                                        <code
                                            class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 text-[10px] font-mono text-slate-500 border border-slate-200 dark:border-slate-700">{{ $key->key_preview }}</code>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500">{{ $key->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="{{ route('api-keys.destroy', $key->id) }}" method="POST"
                                            onsubmit="return confirm('Revoke this user API key? This cannot be undone.');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-[10px] font-bold text-rose-500 hover:text-rose-600 uppercase tracking-widest hover:underline bg-rose-50 dark:bg-rose-900/10 px-3 py-1.5 rounded-lg transition-colors">Revoke</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-24 text-center">
                                        <div
                                            class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                                                </path>
                                            </svg>
                                        </div>
                                        <span class="text-slate-400 dark:text-slate-600 text-xs italic">No user keys
                                            found.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Logs View -->
        <div x-show="tab === 'logs'" class="space-y-6">
            <div
                class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
                <div
                    class="px-8 py-6 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Traffic Logs</h3>
                        <p
                            class="text-[10px] text-slate-500 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5">
                            Real-time Transaction Analysis</p>
                    </div>

                    <div class="relative min-w-[140px]">
                        <form action="{{ route('admin.api') }}" method="GET">
                            <input type="hidden" name="tab" value="logs">
                            <select name="per_page" onchange="this.form.submit()"
                                class="h-10 w-full px-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold uppercase tracking-widest outline-none focus:ring-4 focus:ring-primary/10 transition-all dark:text-slate-400 appearance-none cursor-pointer">
                                @foreach([10, 20, 50, 100, 200] as $val)
                                    <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>{{ $val }}
                                        Per Page</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>


                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                            <tr>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    Timestamp</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    Provider</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    Endpoint</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-800 font-medium">
                            @forelse($logs as $log)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm font-bold text-slate-900 dark:text-white block">{{ $log->created_at->format('M d, H:i') }}</span>
                                        <span
                                            class="text-[10px] text-slate-500 dark:text-slate-500 uppercase tracking-tighter">{{ $log->created_at->format('s.u') }}ms</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 py-1 rounded-lg text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/50">
                                            {{ $log->provider->name ?? 'SYSTEM' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-1.5 py-0.5 rounded text-[8px] font-black bg-slate-900 dark:bg-white text-white dark:text-slate-900 uppercase">
                                                {{ $log->method }}
                                            </span>
                                            <code class="text-[10px] text-slate-500 font-mono truncate max-w-[200px]"
                                                title="{{ $log->endpoint }}">{{ $log->endpoint }}</code>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $hc = $log->status_code >= 400 ? 'bg-rose-50 dark:bg-rose-900/20 text-rose-600' : 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black {{ $hc }}">
                                            {{ $log->status_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button @click="$dispatch('open-log-modal', {{ $log->toJson() }})"
                                            class="text-[10px] font-bold text-primary uppercase tracking-widest hover:underline">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-24 text-center text-slate-400 dark:text-slate-600 italic">No
                                        logs available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($logs->isNotEmpty())
                    <div
                        class="px-8 py-4 border-t border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex items-center justify-between">
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest italic">Total Records:
                            {{ $logs->total() }}
                        </div>
                        <div>{{ $logs->links() }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Provider Management Modal -->
        <div x-show="modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm shadow-2xl" @click="modalOpen = false"></div>

            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-2xl animate-in zoom-in-95 duration-200 border border-slate-100 dark:border-slate-800"
                @click.stop>
                <div class="px-6 py-4 border-b border-slate-50 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white"
                                x-text="editMode ? 'Edit Provider' : 'Add Provider'"></h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Configure API connection details
                            </p>
                        </div>
                        <button @click="modalOpen = false"
                            class="p-2 text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Tabs -->
                    <div class="flex gap-1 p-1 bg-slate-100 dark:bg-slate-800/50 rounded-xl">
                        <button type="button" @click="modalTab = 'general'"
                            :class="modalTab === 'general' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500'"
                            class="flex-1 py-2 rounded-lg text-xs font-bold transition-all">General</button>
                        <button type="button" @click="modalTab = 'config'"
                            :class="modalTab === 'config' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500'"
                            class="flex-1 py-2 rounded-lg text-xs font-bold transition-all">Config</button>
                        <button type="button" @click="modalTab = 'security'"
                            :class="modalTab === 'security' ? 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white shadow-sm' : 'text-slate-500'"
                            class="flex-1 py-2 rounded-lg text-xs font-bold transition-all">Security</button>
                    </div>
                </div>

                <form
                    :action="editMode ? '{{ url('admin/api-management/providers') }}/' + provider.id : '{{ route('admin.api.providers.store') }}'"
                    method="POST">
                    @csrf
                    <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="p-6 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar space-y-6">
                        <!-- General Tab -->
                        <div x-show="modalTab === 'general'" class="space-y-6 animate-in fade-in slide-in-from-bottom-2">
                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Provider
                                    Name</label>
                                <input type="text" name="name" x-model="provider.name" required placeholder="Provider Name"
                                    class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                            </div>

                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Network
                                    Assignment</label>
                                <select name="network_type" x-model="provider.network_type"
                                    class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 transition-all dark:text-white appearance-none cursor-pointer">
                                    <option value="">Universal</option>
                                    <option value="MTN">MTN</option>
                                    <option value="TELECEL">TELECEL</option>
                                    <option value="AT">AT (AirtelTigo)</option>
                                </select>
                            </div>

                            <div class="pt-4 border-t border-slate-50 dark:border-slate-800">
                                <label
                                    class="flex items-center group cursor-pointer p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-dotted border-slate-200 dark:border-slate-700">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="is_active" value="1" x-model="provider.is_active"
                                            class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full peer peer-checked:bg-emerald-500 transition-all">
                                        </div>
                                        <div
                                            class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5">
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <span
                                            class="text-xs font-bold text-slate-700 dark:text-slate-200 block uppercase tracking-tight">Active
                                            Status</span>
                                        <span
                                            class="text-[9px] text-slate-500 dark:text-slate-500 font-bold uppercase tracking-tighter">Enable
                                            this provider for live transactions</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Config Tab -->
                        <div x-show="modalTab === 'config'" class="space-y-6 animate-in fade-in slide-in-from-bottom-2">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2 space-y-1.5">
                                    <label
                                        class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Endpoint
                                        URL</label>
                                    <input type="url" name="base_url" x-model="provider.base_url" required
                                        placeholder="https://api.example.com/endpoint"
                                        class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-mono focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Method</label>
                                    <select name="request_method" x-model="provider.request_method"
                                        class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 transition-all dark:text-white appearance-none cursor-pointer">
                                        <option value="GET">GET</option>
                                        <option value="POST">POST</option>
                                        <option value="PUT">PUT</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Request
                                    Headers (JSON)</label>
                                <textarea name="request_headers" x-model="provider.request_headers" rows="4"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-[11px] font-mono focus:ring-2 focus:ring-primary/20 transition-all dark:text-white custom-scrollbar"
                                    placeholder='{ "Authorization": "Bearer ...", "Content-Type": "application/json" }'></textarea>
                            </div>

                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Body
                                    Template (JSON)</label>
                                <textarea name="request_body" x-model="provider.request_body" rows="6"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-[11px] font-mono focus:ring-2 focus:ring-primary/20 transition-all dark:text-white custom-scrollbar"
                                    placeholder='{ "network": "MTN", "phone": "@phone", "data_code": "@data_code" }'></textarea>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div x-show="modalTab === 'security'" class="space-y-6 animate-in fade-in slide-in-from-bottom-2">
                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">API
                                    Key / Public Key ENV Variable</label>
                                <input type="text" name="api_key" x-model="provider.api_key" placeholder="Public API Key"
                                    class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-mono focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                                <p class="text-[9px] text-slate-400 italic">Name of the variable in your .env file</p>
                            </div>

                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Secret
                                    Key ENV Variable</label>
                                <input type="password" name="secret_key" x-model="provider.secret_key"
                                    placeholder="Secret API Key"
                                    class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-mono focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                            </div>

                            <div class="space-y-1.5 pt-4">
                                <label
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest uppercase">Webhook
                                    Callback URL</label>
                                <input type="url" name="webhook_url" x-model="provider.webhook_url"
                                    placeholder="https://..."
                                    class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-mono focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                                <p class="text-[9px] text-slate-400 italic">Endpoint where the provider sends transaction
                                    status updates</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-6 bg-slate-50 dark:bg-slate-800/20 border-t border-slate-50 dark:border-slate-800 flex flex-col md:flex-row gap-4">
                        <button type="button" @click="modalOpen = false"
                            class="flex-1 h-12 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-[11px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Cancel</button>
                        <button type="submit"
                            class="flex-[2] h-12 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:opacity-90 transition-all border-none"
                            x-text="editMode ? 'Update Provider' : 'Save Provider'"></button>
                    </div>
                </form>
            </div>
        </div>

        <!-- API Log Details Modal -->
        <div x-data="{ open: false, log: {} }" x-show="open" @open-log-modal.window="log = $event.detail; open = true"
            class="fixed inset-0 z-[110] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="open = false"></div>

            <div
                class="relative w-full max-w-3xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh] border border-slate-100 dark:border-slate-800 animate-in zoom-in-95 duration-200">
                <div
                    class="px-8 py-6 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Transaction Details</h3>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold font-mono tracking-widest mt-0.5"
                            x-text="'ID: ' + log.id"></p>
                    </div>
                    <button @click="open = false"
                        class="p-2 text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-8 overflow-y-auto space-y-8 custom-scrollbar">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div
                            class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-[.2em] mb-2">Code</p>
                            <p class="text-2xl font-black"
                                :class="log.status_code >= 400 ? 'text-rose-600' : 'text-emerald-600'"
                                x-text="log.status_code"></p>
                        </div>
                        <div
                            class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-[.2em] mb-2">Method</p>
                            <p class="text-lg font-black text-slate-900 dark:text-white uppercase" x-text="log.method"></p>
                        </div>
                        <div
                            class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-[.2em] mb-2">Provider</p>
                            <p class="text-sm font-black text-slate-900 dark:text-white truncate uppercase"
                                x-text="log.provider ? log.provider.name : 'SYSTEM'"></p>
                        </div>
                        <div
                            class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-100 dark:border-slate-700/50">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-[.2em] mb-2">Time</p>
                            <p class="text-sm font-black text-slate-900 dark:text-white"
                                x-text="new Date(log.created_at).toLocaleTimeString()"></p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[.2em]">Endpoint</label>
                            <div class="bg-slate-900 rounded-xl p-4 border border-slate-800 shadow-inner">
                                <code class="text-emerald-400 text-[11px] font-mono break-all" x-text="log.endpoint"></code>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[.2em]">Response</label>
                            <div
                                class="bg-slate-900 rounded-2xl p-6 border border-slate-800 shadow-inner max-h-[400px] overflow-auto custom-scrollbar">
                                <pre class="text-slate-300 text-[10px] font-mono leading-relaxed"
                                    x-text="JSON.stringify(log.response ? (typeof log.response === 'string' ? JSON.parse(log.response) : log.response) : (log.error_message || 'NO_DATA'), null, 2)"></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="px-8 py-5 border-t border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex justify-end">
                    <button @click="open = false"
                        class="px-6 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-[10px] font-bold uppercase text-slate-700 dark:text-slate-300">Close</button>
                </div>
            </div>
        </div>
        <!-- CloudTech API Documentation View -->
        <div x-show="tab === 'docs'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <div
                class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-8 md:p-12 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">

                <!-- Documentation Header -->
                <div class="flex items-center gap-6 mb-12">
                    <div
                        class="w-14 h-14 bg-cyan-500/10 rounded-2xl flex items-center justify-center text-cyan-600 dark:text-cyan-400 ring-1 ring-cyan-500/20">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight">CloudTech
                            API Documentation</h4>
                        <p class="text-[10px] text-slate-500 dark:text-slate-500 mt-1 uppercase tracking-widest font-black">
                            Technical Integration & Management Guide</p>
                    </div>
                </div>

                <div class="space-y-16">
                    <!-- Getting Started -->
                    <div class="space-y-4">
                        <h5 class="text-xs font-black uppercase tracking-[.2em] text-cyan-600">Getting Started</h5>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 leading-relaxed max-w-3xl">
                            This guide provides comprehensive instructions for both Admin and Agents to connect their
                            systems to the CloudTech platform. Utilize these endpoints to automate orders, manage users, and
                            synchronize data across your infrastructure.
                        </p>
                    </div>

                    <!-- Connectivity Details -->
                    <div class="grid lg:grid-cols-2 gap-8">
                        <!-- Base URL -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-black">01</span>
                                <h4 class="text-[10px] font-black uppercase tracking-[.2em] text-slate-400">Endpoint Access
                                </h4>
                            </div>
                            <div
                                class="bg-slate-950 rounded-2xl p-6 font-mono text-xs text-emerald-400 border border-slate-800 flex items-center justify-between group">
                                <span>{{ url('/api') }}</span>
                                <button onclick="navigator.clipboard.writeText('{{ url('/api') }}')"
                                    class="opacity-0 group-hover:opacity-100 transition-opacity text-slate-500 hover:text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Auth -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <span
                                    class="w-6 h-6 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-black">02</span>
                                <h4 class="text-[10px] font-black uppercase tracking-[.2em] text-slate-400">Authentication
                                </h4>
                            </div>
                            <div
                                class="bg-slate-950 rounded-2xl p-6 font-mono text-xs text-slate-300 border border-slate-800">
                                <div class="text-purple-400 mb-1">headers: <span class="text-white">{</span></div>
                                <div class="pl-4"><span class="text-sky-400">"Authorization"</span>: <span
                                        class="text-emerald-400">"Bearer YOUR_KEY"</span></div>
                                <div class="text-white mt-1">}</div>
                            </div>
                        </div>
                    </div>

                    <!-- API Routes Grid -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <span
                                class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-black">03</span>
                            <h4 class="text-sm font-black uppercase tracking-[.2em] text-slate-400">Core API Routes</h4>
                        </div>

                        <div class="grid xl:grid-cols-2 gap-12">
                            <!-- Agent Section -->
                            <div class="space-y-6">
                                <h5
                                    class="text-[10px] font-black uppercase tracking-[.3em] text-indigo-500 border-b border-indigo-500/10 pb-2">
                                    Agent / Reseller API</h5>
                                <div class="space-y-3">
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                        <span
                                            class="px-2 py-0.5 bg-sky-500/10 text-sky-500 text-[9px] font-black uppercase rounded">GET</span>
                                        <code class="text-xs font-bold text-slate-700 dark:text-slate-200">/networks</code>
                                        <span
                                            class="text-[10px] text-slate-400 ml-auto uppercase font-bold tracking-tight">Public
                                            networks</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                                        <span
                                            class="px-2 py-0.5 bg-sky-500/10 text-sky-500 text-[9px] font-black uppercase rounded">GET</span>
                                        <code class="text-xs font-bold text-slate-700 dark:text-slate-200">/products</code>
                                        <span
                                            class="text-[10px] text-slate-400 ml-auto uppercase font-bold tracking-tight">Active
                                            Bundles</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-emerald-500/10 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30">
                                        <span
                                            class="px-2 py-0.5 bg-emerald-500/20 text-emerald-600 text-[9px] font-black uppercase rounded">POST</span>
                                        <code class="text-xs font-bold text-slate-700 dark:text-slate-200">/orders</code>
                                        <span
                                            class="text-[10px] text-slate-400 ml-auto uppercase font-bold tracking-tight">Place
                                            Order</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-emerald-500/10 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30">
                                        <span
                                            class="px-2 py-0.5 bg-emerald-500/20 text-emerald-600 text-[9px] font-black uppercase rounded">POST</span>
                                        <code
                                            class="text-xs font-bold text-slate-700 dark:text-slate-200">/orders/bulk</code>
                                        <span
                                            class="text-[10px] text-slate-400 ml-auto uppercase font-bold tracking-tight">Bulk
                                            Upload</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Admin Section -->
                            <div class="space-y-6">
                                <h5
                                    class="text-[10px] font-black uppercase tracking-[.3em] text-rose-500 border-b border-rose-500/10 pb-2">
                                    System Management (Admin Only)</h5>
                                <div class="space-y-3">
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-rose-50/30 dark:bg-rose-950/10 border border-rose-100/50 dark:border-rose-900/30">
                                        <span
                                            class="px-2 py-0.5 bg-sky-500/10 text-sky-500 text-[9px] font-black uppercase rounded">GET</span>
                                        <code
                                            class="text-xs font-bold text-slate-700 dark:text-slate-200">/admin/users</code>
                                        <span
                                            class="text-[10px] text-slate-400 ml-auto uppercase font-bold tracking-tight">User
                                            List</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-rose-50/30 dark:bg-rose-950/10 border border-rose-100/50 dark:border-rose-900/30">
                                        <span
                                            class="px-2 py-0.5 bg-sky-500/10 text-sky-500 text-[9px] font-black uppercase rounded">GET</span>
                                        <code
                                            class="text-xs font-bold text-slate-700 dark:text-slate-200">/admin/analytics</code>
                                        <span
                                            class="text-[10px] text-slate-400 ml-auto uppercase font-bold tracking-tight">Platform
                                            Stats</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-rose-50/30 dark:bg-rose-950/10 border border-rose-100/50 dark:border-rose-900/30">
                                        <span
                                            class="px-2 py-0.5 bg-sky-500/10 text-sky-500 text-[9px] font-black uppercase rounded">GET</span>
                                        <code
                                            class="text-xs font-bold text-slate-700 dark:text-slate-200">/admin/api-logs</code>
                                        <span
                                            class="text-[10px] text-slate-400 ml-auto uppercase font-bold tracking-tight">Traffic
                                            Logs</span>
                                    </div>
                                    <div
                                        class="flex items-center gap-4 p-4 rounded-xl bg-rose-50/30 dark:bg-rose-950/10 border border-rose-100/50 dark:border-rose-900/30">
                                        <span
                                            class="px-2 py-0.5 bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase rounded">POST</span>
                                        <code
                                            class="text-xs font-bold text-slate-700 dark:text-slate-200">/admin/products</code>
                                        <span
                                            class="text-[10px] text-slate-400 ml-auto uppercase font-bold tracking-tight">Create
                                            Bundle</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Webhooks -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <span
                                class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-black">04</span>
                            <h4 class="text-sm font-black uppercase tracking-[.2em] text-slate-400">Webhook Sync</h4>
                        </div>
                        <div class="grid lg:grid-cols-2 gap-8">
                            <div
                                class="p-8 rounded-3xl bg-indigo-50/30 dark:bg-indigo-950/10 border border-indigo-100/50 dark:border-indigo-900/30 space-y-4">
                                <h5 class="text-[10px] font-black uppercase text-indigo-600 tracking-widest">Outgoing Events
                                </h5>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed">External systems receive
                                    real-time updates for order completions and failure events.</p>
                                <div
                                    class="bg-slate-950 rounded-2xl p-6 font-mono text-[10px] text-slate-300 border border-slate-800">
                                    <span class="text-purple-400">{</span><br>
                                    <span class="pl-4">"event": <span
                                            class="text-emerald-400">"order.completed"</span>,</span><br>
                                    <span class="pl-4">"data": { "status": "success" }</span><br>
                                    <span class="text-purple-400">}</span>
                                </div>
                            </div>

                            <div
                                class="p-8 rounded-3xl bg-emerald-50/30 dark:bg-emerald-950/10 border border-emerald-100/50 dark:border-emerald-900/30 space-y-4">
                                <h5 class="text-[10px] font-black uppercase text-emerald-600 tracking-widest">Incoming
                                    Webhooks</h5>
                                <p class="text-xs text-slate-500 font-medium leading-relaxed">Providers push transaction
                                    status updates to your system via: <code>/api/webhooks/incoming</code></p>
                                <div class="pt-2">
                                    <span
                                        class="inline-flex px-3 py-1 bg-emerald-500 text-white rounded-lg text-[9px] font-black uppercase">Active
                                        Endpoint</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Codes -->
                    <div class="space-y-8">
                        <div class="flex items-center gap-4">
                            <span
                                class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-black">05</span>
                            <h4 class="text-sm font-black uppercase tracking-[.2em] text-slate-400">Infrastructure Result
                                Codes</h4>
                        </div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div
                                class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-center">
                                <p class="text-[9px] font-black text-indigo-500 mb-1">200</p>
                                <p
                                    class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-tighter">
                                    Success</p>
                            </div>
                            <div
                                class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-center">
                                <p class="text-[9px] font-black text-rose-500 mb-1">401</p>
                                <p
                                    class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-tighter">
                                    Unauthorized</p>
                            </div>
                            <div
                                class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-center">
                                <p class="text-[9px] font-black text-amber-500 mb-1">422</p>
                                <p
                                    class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-tighter">
                                    Validation</p>
                            </div>
                            <div
                                class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800 text-center">
                                <p class="text-[9px] font-black text-slate-400 mb-1">500</p>
                                <p
                                    class="text-[10px] font-black text-slate-700 dark:text-slate-300 uppercase tracking-tighter">
                                    Server Error</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection