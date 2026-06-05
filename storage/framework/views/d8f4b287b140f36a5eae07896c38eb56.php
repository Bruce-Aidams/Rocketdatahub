<?php $__env->startSection('title', 'API Management'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-700" x-data="{ 
                                            tab: 'providers',
                                            modalOpen: false, 
                                            modalTab: 'general',
                                            editMode: false,
                                            loading: false,
                                            provider: { 
                                                id: '', name: '', network_type: '', base_url: '', request_method: 'POST', 
                                                timeout_seconds: 30, retry_attempts: 3,
                                                response_success_field: 'success', response_data_field: 'data', response_error_field: 'error',
                                                webhook_url: '', api_key: '', secret_key: '', is_active: true,
                                                status_endpoint: '', status_request_method: 'GET'
                                            },
                                            resetForm() {
                                                this.provider = { 
                                                    id: '', name: '', network_type: '', base_url: '', request_method: 'POST', 
                                                    request_headers: '', request_body: '', request_body_template: '',
                                                    timeout_seconds: 30, retry_attempts: 3,
                                                    response_success_field: 'success', response_data_field: 'data', response_error_field: 'error',
                                                    webhook_url: '', api_key: '', secret_key: '', is_active: true,
                                                    status_endpoint: '', status_request_method: 'GET'
                                                };
                                                this.editMode = false;
                                                this.modalTab = 'general';
                                            },
                                            openAdd() {
                                                this.resetForm();
                                                this.modalOpen = true;
                                            },
                                            openEdit(p) {
                                                this.provider = JSON.parse(JSON.stringify(p));
                                                this.provider.request_headers = p.request_headers ? (typeof p.request_headers === 'string' ? p.request_headers : JSON.stringify(p.request_headers, null, 2)) : '';
                                                this.provider.request_body = p.request_body ? (typeof p.request_body === 'string' ? p.request_body : JSON.stringify(p.request_body, null, 2)) : '';
                                                // Ensure defaults for new fields if they are missing
                                                this.provider.timeout_seconds = p.timeout_seconds || 30;
                                                this.provider.retry_attempts = p.retry_attempts || 3;
                                                this.provider.response_success_field = p.response_success_field || 'success';
                                                this.provider.response_data_field = p.response_data_field || 'data';
                                                this.provider.response_error_field = p.response_error_field || 'error';
                                                this.provider.status_endpoint = p.status_endpoint || '';
                                                this.provider.status_request_method = p.status_request_method || 'GET';

                                                this.editMode = true;
                                                this.modalTab = 'general';
                                                this.modalOpen = true;
                                            },
                                            async testProviderConnection() {
                                                this.loading = true;
                                                try {
                                                    const response = await fetch('/admin/api-management/providers/test', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                            'Accept': 'application/json'
                                                        },
                                                        body: JSON.stringify(this.provider)
                                                    });
                                                    const data = await response.json();

                                                    if (response.ok && data.success) {
                                                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Connection Successful! ' + (data.message || ''), type: 'success' } }));
                                                    } else {
                                                        throw new Error(data.message || 'Connection failed');
                                                    }
                                                } catch (error) {
                                                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: error.message || 'Connection Test Failed', type: 'error' } }));
                                                } finally {
                                                    this.loading = false;
                                                }
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
        <div class="flex gap-2 p-1 bg-slate-100 dark:bg-slate-800/50 rounded-xl w-fit max-w-full overflow-x-auto">
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
                class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-8 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
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
                                value="<?php echo e(\App\Models\Setting::where('key', 'webhook_url')->first()?->value); ?>"
                                placeholder="https://external-service.com/api/webhook"
                                class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm font-mono focus:ring-2 focus:ring-primary/20 outline-none transition-all dark:text-white">
                            <p class="text-[9px] text-slate-400 italic">Target URL for automated outbound POST requests.</p>
                        </div>

                        <div class="space-y-1.5">
                            <label
                                class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[.2em]">Signing
                                Secret Key</label>
                            <input type="text" name="webhook_secret" id="webhook_secret"
                                value="<?php echo e(\App\Models\Setting::where('key', 'webhook_secret')->first()?->value); ?>"
                                placeholder="WH_SECRET_KEY..."
                                class="w-full h-11 px-4 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm font-mono focus:ring-2 focus:ring-primary/20 outline-none transition-all dark:text-white">
                        </div>
                    </div>

                        <div
                            class="p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                            <h5 class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-5">
                                Events to Monitor</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php
                                    $savedEventsJson = \App\Models\Setting::where('key', 'webhook_events')->first()?->value ?? '[]';
                                    $savedEvents = json_decode($savedEventsJson, true) ?? [];
                                    if (!is_array($savedEvents)) $savedEvents = [];

                                    $availableEventsJson = \App\Models\Setting::where('key', 'available_webhook_events')->first()?->value;
                                    if (!$availableEventsJson) {
                                        $availableEvents = ['Order Created', 'Order Processing', 'Order Completed', 'Order Failed', 'Deposit Successful', 'Manual Adjustment'];
                                    } else {
                                        $availableEvents = json_decode($availableEventsJson, true) ?? [];
                                    }
                                ?>
                                <div id="events-data" data-available="<?php echo e(json_encode($availableEvents)); ?>" class="hidden"></div>
                                <?php $__currentLoopData = $availableEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label
                                        class="flex items-center p-3.5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl cursor-pointer hover:border-primary/50 transition-all group">
                                        <input type="checkbox" name="webhook_events[]" value="<?php echo e($event); ?>" <?php echo e(in_array($event, $savedEvents) ? 'checked' : ''); ?>

                                            class="w-4 h-4 text-primary rounded-md border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 focus:ring-primary/20">
                                        <span
                                            class="ml-3 text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-primary transition-colors"><?php echo e($event); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="mt-6 pt-5 border-t border-slate-100 dark:border-slate-800/50 flex flex-col sm:flex-row items-center gap-3">
                                <input type="text" id="new_custom_event_name" placeholder="Define Custom Event Name..."
                                    class="w-full sm:w-64 h-11 px-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-primary/20 dark:text-white transition-all">
                                <button type="button"
                                    @click="async () => {
                                        const newEvName = document.getElementById('new_custom_event_name').value.trim();
                                        if (!newEvName) return;
                                        let evs = JSON.parse(document.getElementById('events-data').dataset.available);
                                        if (evs.includes(newEvName)) {
                                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Event already exists', type: 'error' } }));
                                            return;
                                        }
                                        evs.push(newEvName);
                                        const response = await fetch('<?php echo e(route('admin.settings.update')); ?>', {
                                            method: 'PUT',
                                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
                                            body: JSON.stringify({ settings: { available_webhook_events: JSON.stringify(evs) } })
                                        });
                                        if(response.ok) {
                                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Custom event successfully added!', type: 'success' } }));
                                            setTimeout(() => window.location.reload(), 600);
                                        } else {
                                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Failed to add custom event.', type: 'error' } }));
                                        }
                                    }"
                                    class="w-full sm:w-auto h-11 px-6 bg-slate-800 dark:bg-slate-200 text-white dark:text-slate-900 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-700 dark:hover:bg-white transition-all whitespace-nowrap">
                                    Add Event
                                </button>
                            </div>
                        </div>

                    <div class="pt-4">
                        <button type="button"
                            @click="async () => {
    const url = document.getElementById('webhook_url').value;
    const secret = document.getElementById('webhook_secret').value;
    const selectedEvents = Array.from(document.querySelectorAll(`input[name='webhook_events[]']:checked`)).map(cb => cb.value);
    const response = await fetch('<?php echo e(route("admin.settings.update")); ?>', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
        body: JSON.stringify({ settings: { webhook_url: url, webhook_secret: secret, webhook_events: JSON.stringify(selectedEvents) } })
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
                <?php $__empty_1 = true; $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                                                                                                                                                                                                                                                                                                                                                        <?php echo e($p->network_type === 'MTN' ? 'bg-amber-100 text-amber-600' :
                    ($p->network_type === 'TELECEL' ? 'bg-rose-100 text-rose-600' :
                        ($p->network_type === 'AT' ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-500'))); ?>">
                                        <?php echo e($p->network_type ?: 'Global'); ?>

                                    </div>
                                </div>

                                <button
                                    @click="async () => {
                                                                                                                                                                                        const newState = !<?php echo e($p->is_active ? 'true' : 'false'); ?>;
                                                                                                                                                                                        const response = await fetch('<?php echo e(route('admin.api.providers.update', $p->id)); ?>', {
                                                                                                                                                                                            method: 'PUT',
                                                                                                                                                                                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
                                                                                                                                                                                            body: JSON.stringify({ is_active: newState })
                                                                                                                                                                                        });
                                                                                                                                                                                        if(response.ok) {
                                                                                                                                                                                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Provider status updated!', type: 'success' } }));
                                                                                                                                                                                            setTimeout(() => window.location.reload(), 500);
                                                                                                                                                                                        } else {
                                                                                                                                                                                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Update failed.', type: 'error' } }));
                                                                                                                                                                                        }
                                                                                                                                                                                    }"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none <?php echo e($p->is_active ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700'); ?>">
                                    <span class="sr-only">Toggle Status</span>
                                    <span
                                        class="inline-block h-3 w-3 transform rounded-full bg-white transition-transform <?php echo e($p->is_active ? 'translate-x-5' : 'translate-x-1'); ?>"></span>
                                </button>
                            </div>

                            <div class="space-y-1 mb-6">
                                <h4 class="text-lg font-bold text-slate-900 dark:text-white leading-tight truncate"><?php echo e($p->name); ?>

                                </h4>
                                <p class="text-[10px] text-slate-500 font-mono truncate opacity-60"><?php echo e($p->base_url); ?></p>
                            </div>

                            <div class="mt-auto pt-4 flex items-center gap-2 border-t border-slate-50 dark:border-slate-800/50">
                                <button @click="openEdit(<?php echo e($p->toJson()); ?>)"
                                    class="flex-1 h-10 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:opacity-90 shadow-md shadow-indigo-500/10 transition-all border-none">
                                    Config
                                </button>
                                <form action="<?php echo e(route('admin.api.providers.destroy', $p->id)); ?>" method="POST"
                                    onsubmit="return confirm('Terminate this provider connection?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
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
                <?php endif; ?>
            </div>
        </div>

        <!-- User Keys View -->
        <div x-show="tab === 'user_keys'" class="space-y-6">
            <div
                class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
                <div
                    class="px-6 md:px-8 py-6 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
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
                            <?php $__empty_1 = true; $__currentLoopData = $userKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-500 border border-slate-200 dark:border-slate-700">
                                                <?php echo e(substr($key->user->name ?? '?', 0, 1)); ?>

                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-slate-900 dark:text-white">
                                                    <?php echo e($key->user->name ?? 'Unknown User'); ?>

                                                </p>
                                                <p class="text-[10px] text-slate-500"><?php echo e($key->user->email ?? 'No Email'); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-700 dark:text-slate-300"><?php echo e($key->name); ?></td>
                                    <td class="px-6 py-4">
                                        <code
                                            class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 text-[10px] font-mono text-slate-500 border border-slate-200 dark:border-slate-700"><?php echo e($key->key_preview); ?></code>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500"><?php echo e($key->created_at->format('M d, Y')); ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <form action="<?php echo e(route('api-keys.destroy', $key->id)); ?>" method="POST"
                                            onsubmit="return confirm('Revoke this user API key? This cannot be undone.');">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                class="text-[10px] font-bold text-rose-500 hover:text-rose-600 uppercase tracking-widest hover:underline bg-rose-50 dark:bg-rose-900/10 px-3 py-1.5 rounded-lg transition-colors">Revoke</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
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
                            <?php endif; ?>
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
                    class="px-6 md:px-6 md:px-8 py-6 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Traffic Logs</h3>
                        <p
                            class="text-[10px] text-slate-500 dark:text-slate-500 font-bold uppercase tracking-widest mt-0.5">
                            Real-time Transaction Analysis</p>
                    </div>

                    <div class="relative min-w-[140px]">
                        <form action="<?php echo e(route('admin.api')); ?>" method="GET">
                            <input type="hidden" name="tab" value="logs">
                            <select name="per_page" onchange="this.form.submit()"
                                class="h-10 w-full px-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold uppercase tracking-widest outline-none focus:ring-4 focus:ring-primary/10 transition-all dark:text-slate-400 appearance-none cursor-pointer">
                                <?php $__currentLoopData = [10, 20, 50, 100, 200]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($val); ?>" <?php echo e(request('per_page', 10) == $val ? 'selected' : ''); ?>><?php echo e($val); ?>

                                        Per Page</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-sm font-bold text-slate-900 dark:text-white block"><?php echo e($log->created_at->format('M d, H:i')); ?></span>
                                        <span
                                            class="text-[10px] text-slate-500 dark:text-slate-500 uppercase tracking-tighter"><?php echo e($log->created_at->format('s.u')); ?>ms</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex px-2 py-1 rounded-lg text-[10px] font-bold uppercase bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/50">
                                            <?php echo e($log->provider->name ?? 'SYSTEM'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-1.5 py-0.5 rounded text-[8px] font-black bg-slate-900 dark:bg-white text-white dark:text-slate-900 uppercase">
                                                <?php echo e($log->method); ?>

                                            </span>
                                            <code class="text-[10px] text-slate-500 font-mono truncate max-w-[200px]"
                                                title="<?php echo e($log->endpoint); ?>"><?php echo e($log->endpoint); ?></code>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php
                                            $hc = $log->status_code >= 400 ? 'bg-rose-50 dark:bg-rose-900/20 text-rose-600' : 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600';
                                        ?>
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black <?php echo e($hc); ?>">
                                            <?php echo e($log->status_code); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button @click="$dispatch('open-log-modal', <?php echo e($log->toJson()); ?>)"
                                            class="text-[10px] font-bold text-primary uppercase tracking-widest hover:underline">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-24 text-center text-slate-400 dark:text-slate-600 italic">No
                                        logs available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($logs->isNotEmpty()): ?>
                    <div
                        class="px-6 md:px-8 py-4 border-t border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex items-center justify-between">
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest italic">Total Records:
                            <?php echo e($logs->total()); ?>

                        </div>
                        <div><?php echo e($logs->links()); ?></div>
                    </div>
                <?php endif; ?>
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
                    :action="editMode ? '<?php echo e(url('admin/api-management/providers')); ?>/' + provider.id : '<?php echo e(route('admin.api.providers.store')); ?>'"
                    method="POST">
                    <?php echo csrf_field(); ?>
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

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2 space-y-1.5">
                                    <label
                                        class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status Check Endpoint URL (Optional)</label>
                                    <input type="url" name="status_endpoint" x-model="provider.status_endpoint"
                                        placeholder="https://api.example.com/status?ref={request_id}"
                                        class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-mono focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status Method</label>
                                    <select name="status_request_method" x-model="provider.status_request_method"
                                        class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 transition-all dark:text-white appearance-none cursor-pointer">
                                        <option value="GET">GET</option>
                                        <option value="POST">POST</option>
                                        <option value="PUT">PUT</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Timeout
                                        (Seconds)</label>
                                    <input type="number" name="timeout_seconds" x-model="provider.timeout_seconds" min="5"
                                        max="300"
                                        class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                                </div>
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Retry
                                        Attempts</label>
                                    <input type="number" name="retry_attempts" x-model="provider.retry_attempts" min="0"
                                        max="10"
                                        class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Request
                                    Headers (JSON)</label>
                                <textarea name="request_headers" x-model="provider.request_headers" rows="3"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-[11px] font-mono focus:ring-2 focus:ring-primary/20 transition-all dark:text-white custom-scrollbar"
                                    placeholder='{ "Authorization": "Bearer ...", "Content-Type": "application/json" }'></textarea>
                            </div>

                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Request
                                    Body Template (JSON with Placeholders)</label>
                                <textarea name="request_body_template" x-model="provider.request_body_template" rows="4"
                                    class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-[11px] font-mono focus:ring-2 focus:ring-primary/20 transition-all dark:text-white custom-scrollbar"
                                    placeholder='{ "network": "MTN", "phone": "{phone}", "amount": "{amount}", "package": "{package}" }'></textarea>
                                <p class="text-[9px] text-slate-400 italic">Available placeholders: {phone}, {amount},
                                    {package}, {network}, {api_key}, {api_secret}</p>
                            </div>

                            <div
                                class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                                <h5 class="text-[10px] font-black uppercase text-slate-400 mb-3 tracking-widest">Response
                                    Field Mapping</h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-bold text-slate-500 uppercase">Success Field</label>
                                        <input type="text" name="response_success_field"
                                            x-model="provider.response_success_field"
                                            class="w-full h-10 px-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono focus:ring-2 focus:ring-primary/20 dark:text-white">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-bold text-slate-500 uppercase">Data Field</label>
                                        <input type="text" name="response_data_field" x-model="provider.response_data_field"
                                            class="w-full h-10 px-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono focus:ring-2 focus:ring-primary/20 dark:text-white">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-[9px] font-bold text-slate-500 uppercase">Error Field</label>
                                        <input type="text" name="response_error_field"
                                            x-model="provider.response_error_field"
                                            class="w-full h-10 px-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono focus:ring-2 focus:ring-primary/20 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div x-show="modalTab === 'security'" class="space-y-6 animate-in fade-in slide-in-from-bottom-2">
                            <div class="space-y-1.5">
                                <label
                                    class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">API Key / Public Key</label>
                                <input type="text" name="api_key" x-model="provider.api_key" placeholder="Enter actual key OR env variable name (e.g. PAYSTACK_PUBLIC_KEY)"
                                    class="w-full h-12 px-4 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700/50 rounded-2xl text-sm font-mono focus:ring-2 focus:ring-primary/20 transition-all dark:text-white">
                                <p class="text-[9px] text-slate-400 italic">Enter the actual key value or the name of the variable from your .env file.</p>
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
                        <button type="button" @click="testProviderConnection()" :disabled="loading"
                            class="h-12 px-6 bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-500/20 rounded-2xl text-[11px] font-black uppercase tracking-widest hover:bg-cyan-500/20 transition-all flex items-center justify-center gap-2">
                            <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            <span x-text="loading ? 'Testing...' : 'Test Connection'"></span>
                        </button>
                        <div class="flex-1 flex gap-4">
                            <button type="button" @click="modalOpen = false"
                                class="flex-1 h-12 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-[11px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Cancel</button>
                            <button type="submit"
                                class="flex-[2] h-12 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest shadow-xl shadow-indigo-500/20 hover:opacity-90 transition-all border-none"
                                x-text="editMode ? 'Update Provider' : 'Save Provider'"></button>
                        </div>
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
                    class="px-6 md:px-8 py-6 border-b border-slate-50 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/20">
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

                <div class="p-6 md:p-8 overflow-y-auto space-y-8 custom-scrollbar">
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
                    class="px-6 md:px-8 py-5 border-t border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20 flex justify-end">
                    <button @click="open = false"
                        class="px-6 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-[10px] font-bold uppercase text-slate-700 dark:text-slate-300">Close</button>
                </div>
            </div>
        </div>
        <!-- RocketDataHub API Documentation View -->
        <div x-show="tab === 'docs'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 md:p-12 border border-slate-100 dark:border-slate-800 shadow-sm text-center">
                
                <div class="w-20 h-20 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-3xl flex items-center justify-center text-white shadow-lg shadow-cyan-500/30 mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                
                <h4 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-4">Developer Documentation</h4>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 max-w-lg mx-auto">
                    We've moved the API Integration Guide and all other system documentation to a dedicated Developer Documentation portal.
                </p>
                
                <a href="<?php echo e(route('admin.docs')); ?>" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-indigo-600 to-blue-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:opacity-90 shadow-xl shadow-indigo-500/20 transition-all">
                    <span>View Full Documentation</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\bruce\OneDrive\Desktop\Projects\RocketDataHub\resources\views/admin/api/index.blade.php ENDPATH**/ ?>