{{--
============================================================
API INTEGRATION COMMENTED OUT
To re-enable, uncomment the code below.
============================================================
@extends('layouts.admin')

@section('content')
<div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-6 md:p-12 border border-slate-100 dark:border-slate-800 shadow-sm relative overflow-hidden">
        
        <!-- Documentation Header -->
        <div class="flex items-center gap-6 mb-12">
            <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-cyan-500/30">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <h4 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">API Integration Guide</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 uppercase tracking-[.2em] font-bold">Comprehensive documentation for connecting external data vendors</p>
            </div>
        </div>

        <div class="space-y-16">
            <!-- Overview -->
            <div class="prose dark:prose-invert max-w-none">
                <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-sm md:text-base">
                    RocketDataHub's API Manager allows you to connect to any external data vending platform without writing code. By configuring <strong>Active Providers</strong>, you can automatically route customer data orders to your vendors in real-time. Follow this guide to set up your first connection.
                </p>
            </div>

            <!-- Step by Step Guide -->
            <div class="space-y-8 relative before:absolute before:inset-0 before:ml-6 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 dark:before:via-slate-800 before:to-transparent">
                
                <!-- Step 1 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full border-4 border-white dark:border-slate-900 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 font-black shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                        1
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <h5 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                            Create a Provider
                        </h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                            Go to the <strong>Active Providers</strong> tab and click "Add Provider". Enter the vendor's name and assign a network (e.g., MTN). If the vendor supports all networks, choose "Universal".
                        </p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full border-4 border-white dark:border-slate-900 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 font-black shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                        2
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <h5 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Authentication & Security
                        </h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-4">
                            In the <strong>Security</strong> tab, enter the API Key provided by your vendor. The system will automatically attach this to your requests as standard Bearer tokens and X-API-KEY headers.
                        </p>
                        <div class="bg-slate-900 rounded-xl p-4 font-mono text-[10px] text-slate-300 border border-slate-800">
                            <div class="text-emerald-400 mb-1">// Automatically injected headers:</div>
                            <div><span class="text-sky-400">"Authorization"</span>: <span class="text-amber-300">"Bearer YOUR_KEY"</span></div>
                            <div><span class="text-sky-400">"X-API-KEY"</span>: <span class="text-amber-300">"YOUR_KEY"</span></div>
                        </div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full border-4 border-white dark:border-slate-900 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 font-black shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                        3
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <h5 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Endpoint Configuration
                        </h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-4">
                            In the <strong>Config</strong> tab, set the vendor's purchase URL (e.g., <code>https://vendor.com/api/buy</code>) and set the Method to POST.
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-bold mb-2">Dynamic Payload Variables:</p>
                        <ul class="text-xs text-slate-500 dark:text-slate-400 space-y-1 mb-4 list-disc pl-4">
                            <li><code>{phone}</code> - Replaced with customer's phone</li>
                            <li><code>{network}</code> - Replaced with network ID</li>
                            <li><code>{package}</code> - Replaced with the data plan ID</li>
                            <li><code>{amount}</code> - Replaced with the price</li>
                        </ul>
                        <div class="bg-slate-900 rounded-xl p-4 font-mono text-[10px] text-slate-300 border border-slate-800">
                            <div class="text-emerald-400 mb-1">// Example Request Body Template:</div>
                            <span class="text-purple-400">{</span><br>
                            <span class="pl-4">"network": <span class="text-amber-300">"{network}"</span>,</span><br>
                            <span class="pl-4">"mobile": <span class="text-amber-300">"{phone}"</span>,</span><br>
                            <span class="pl-4">"plan_id": <span class="text-amber-300">"{package}"</span></span><br>
                            <span class="text-purple-400">}</span>
                        </div>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full border-4 border-white dark:border-slate-900 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-black shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                        4
                    </div>
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-3rem)] p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <h5 class="font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Testing the Connection
                        </h5>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed mb-4">
                            Before activating the provider, click the <strong>Test Connection</strong> button at the bottom of the modal. This will send a live request to the vendor using your configured keys and endpoint.
                        </p>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 border border-emerald-100 dark:border-emerald-800 flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div>
                                <p class="text-[10px] font-bold text-emerald-800 dark:text-emerald-300 uppercase tracking-widest mb-1">What to look for:</p>
                                <p class="text-xs text-emerald-600 dark:text-emerald-400">If successful, you will receive a <code class="bg-emerald-100 dark:bg-emerald-800 px-1 rounded">200 OK</code> status and the raw JSON response from the vendor will be displayed. You can use this response to verify your keys are correct.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Webhooks and Callbacks -->
            <div class="mt-16 pt-16 border-t border-slate-100 dark:border-slate-800">
                <h4 class="text-xl font-black text-slate-900 dark:text-white uppercase tracking-tight mb-8">Webhooks & External Callbacks</h4>
                <div class="grid lg:grid-cols-2 gap-8">
                    <div class="p-8 rounded-3xl bg-indigo-50/50 dark:bg-indigo-900/10 border border-indigo-100/50 dark:border-indigo-800/30">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            </div>
                            <h5 class="text-sm font-black uppercase text-slate-900 dark:text-white">Receiving Webhooks</h5>
                        </div>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed mb-4">If your vendor supports sending webhooks when an order is completed, provide them with this URL:</p>
                        <div class="bg-slate-900 rounded-xl p-4 font-mono text-[10px] text-emerald-400 border border-slate-800 flex items-center justify-between group">
                            <span>{{ url('/api/webhooks/incoming') }}</span>
                            <button onclick="navigator.clipboard.writeText('{{ url('/api/webhooks/incoming') }}')" class="text-slate-500 hover:text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="p-8 rounded-3xl bg-cyan-50/50 dark:bg-cyan-900/10 border border-cyan-100/50 dark:border-cyan-800/30">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-cyan-100 dark:bg-cyan-900/50 flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            </div>
                            <h5 class="text-sm font-black uppercase text-slate-900 dark:text-white">Sending Webhooks</h5>
                        </div>
                        <p class="text-xs text-slate-500 font-medium leading-relaxed mb-4">You can set a <strong>Webhook Callback URL</strong> in the Security tab of any Provider. RocketDataHub will push an event to that URL when an order completes.</p>
                        <div class="bg-slate-900 rounded-xl p-4 font-mono text-[10px] text-slate-300 border border-slate-800">
                            <span class="text-purple-400">{</span><br>
                            <span class="pl-4">"event": <span class="text-emerald-400">"order.completed"</span>,</span><br>
                            <span class="pl-4">"data": { "reference": "CT123", "status": "success" }</span><br>
                            <span class="text-purple-400">}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
--}}
