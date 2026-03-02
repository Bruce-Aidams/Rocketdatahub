@extends('layouts.admin')
@section('title', 'Announcements')

@section('content')
    <div class="space-y-6 lg:space-y-8" x-data="{ 
                                showCreateModal: false,
                                showEditModal: false,
                                editingAnnouncement: null,
                                openEditModal(announcement) {
                                    this.editingAnnouncement = announcement;
                                    this.showEditModal = true;
                                }
                            }">

        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 animate-fade-in-up">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none mb-2">
                    Announcements</h1>
                <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400">Broadcast important messages to all
                    users.</p>
            </div>

            <button @click="showCreateModal = true"
                class="group relative inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-br from-primary to-indigo-600 hover:from-primary/90 hover:to-indigo-600/90 text-white rounded-2xl transition-all duration-300 shadow-xl shadow-primary/20 hover:shadow-2xl hover:shadow-primary/30 hover:-translate-y-0.5 overflow-hidden w-full sm:w-auto">
                <div
                    class="absolute inset-0 bg-white/20 group-hover:translate-x-full transition-transform duration-700 -skew-x-12 -ml-8 w-1/3">
                </div>
                <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm font-bold tracking-wide relative z-10">New Announcement</span>
            </button>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
            {{-- Total Announcements --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 relative overflow-hidden group hover:border-primary/20 transition-colors">
                <div
                    class="absolute -right-6 -top-6 w-32 h-32 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors duration-500">
                </div>
                <div class="flex items-center gap-5 relative z-10">
                    <div
                        class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center border border-indigo-100 dark:border-indigo-800/30">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">
                            Total Announcements</h3>
                        <div class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                            {{ $announcements->total() }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Active Announcement --}}
            <div
                class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800 relative overflow-hidden group hover:border-emerald-500/20 transition-colors">
                <div
                    class="absolute -right-6 -top-6 w-32 h-32 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-colors duration-500">
                </div>
                <div class="flex items-center gap-5 relative z-10">
                    <div
                        class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center border border-emerald-100 dark:border-emerald-800/30">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-1">
                            Currently Active</h3>
                        <div class="text-lg font-black text-slate-900 dark:text-white tracking-tight mt-2">
                            @php
                                $active = \App\Models\Announcement::where('is_active', true)->first();
                            @endphp
                            @if($active)
                                <span
                                    class="inline-flex items-center gap-2 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-3 py-1 rounded-lg text-sm border border-emerald-200 dark:border-emerald-500/20">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    {{ \Illuminate\Support\Str::limit($active->title, 20) }}
                                </span>
                            @else
                                <span class="text-slate-400">None</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Announcements Table --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 overflow-hidden shadow-sm animate-fade-in-up"
            style="animation-delay: 0.1s;">
            <div
                class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-primary/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white uppercase tracking-tight">Announcement
                        History</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-slate-800">
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                                Title</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">
                                Message Preview</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 text-center">
                                Type</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 text-center">
                                Status</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 text-right">
                                Date</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/50">
                        @forelse($announcements as $announcement)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="font-bold text-sm text-slate-900 dark:text-white tracking-tight">{{ $announcement->title }}</span>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate">
                                    <span
                                        class="text-xs text-slate-500 dark:text-slate-400 font-medium">{{ \Illuminate\Support\Str::limit($announcement->message, 50) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $typeColors = [
                                            'info' => 'bg-indigo-50 text-indigo-600 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                                            'warning' => 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                                            'danger' => 'bg-rose-50 text-rose-600 border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20',
                                            'success' => 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                                        ];
                                        $typeIcon = [
                                            'info' => '<svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                                            'warning' => '<svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
                                            'danger' => '<svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                                            'success' => '<svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border {{ $typeColors[$announcement->type] ?? $typeColors['info'] }}">
                                        {!! $typeIcon[$announcement->type] ?? $typeIcon['info'] !!}
                                        {{ $announcement->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($announcement->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-100 text-slate-500 border border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700">
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span
                                        class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ $announcement->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- Toggle Status --}}
                                        <form action="{{ route('admin.announcements.toggle', $announcement) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="p-2 rounded-xl border transition-all duration-200 flex items-center justify-center {{ $announcement->is_active ? 'bg-amber-50 text-amber-600 border-amber-200 hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20 dark:hover:bg-amber-500/20' : 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20 dark:hover:bg-emerald-500/20' }}"
                                                title="{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}">
                                                @if($announcement->is_active)
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636">
                                                        </path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>

                                        {{-- Edit --}}
                                        <button type="button" @click="openEditModal({{ json_encode($announcement) }})"
                                            class="p-2 rounded-xl bg-slate-50 text-slate-600 border border-slate-200 hover:bg-slate-100 hover:text-slate-900 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700 dark:hover:bg-slate-700 dark:hover:text-white transition-all duration-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </button>

                                        {{-- Delete --}}
                                        <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 hover:bg-rose-100 hover:text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/20 dark:hover:bg-rose-500/20 dark:hover:text-rose-300 transition-all duration-200"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div
                                            class="w-16 h-16 bg-slate-50 dark:bg-slate-800/50 rounded-2xl flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-800">
                                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3
                                            class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-tight mb-1">
                                            No Announcements Found</h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">Ready to broadcast your first
                                            message?</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($announcements->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>

        {{-- Create Modal --}}
        <div x-show="showCreateModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showCreateModal = false"></div>

            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-2xl animate-in zoom-in-95 duration-200 border border-slate-100 dark:border-slate-800"
                @click.stop>
                <div
                    class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-primary/10 flex items-center justify-center shadow-inner">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">Create
                                Announcement</h3>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Broadcast a new
                                message</p>
                        </div>
                    </div>
                    <button @click="showCreateModal = false"
                        class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-slate-200 dark:hover:border-slate-600 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.announcements.store') }}" method="POST" class="p-6">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Title</label>
                            <input type="text" name="title" required
                                class="w-full h-11 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500"
                                placeholder="e.g. Network Maintenance Notice">
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Type</label>
                            <select name="type" required
                                class="w-full h-11 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                                <option value="info">Info (Blue)</option>
                                <option value="warning">Warning (Yellow)</option>
                                <option value="danger">Danger (Red)</option>
                                <option value="success">Success (Green)</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Message</label>
                            <textarea name="message" required rows="4"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 resize-y"
                                placeholder="Enter the detailed announcement message here..."></textarea>
                        </div>

                        <label
                            class="flex items-center gap-3 p-4 border border-slate-200 dark:border-slate-700 rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group mt-2">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" name="activate" value="1" class="peer sr-only">
                                <div
                                    class="w-10 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500">
                                </div>
                            </div>
                            <div>
                                <span class="block text-sm font-bold text-slate-900 dark:text-white tracking-tight">Activate
                                    Immediately</span>
                                <span class="block text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">This will
                                    deactivate any currently active announcement.</span>
                            </div>
                        </label>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="showCreateModal = false"
                            class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-bold uppercase tracking-wider rounded-xl transition-colors">Cancel</button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-primary hover:bg-primary/90 text-white text-sm font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-primary/30 hover:shadow-primary/40 transition-all">Create</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Modal --}}
        <div x-show="showEditModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak>
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showEditModal = false"></div>

            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl overflow-hidden shadow-2xl animate-in zoom-in-95 duration-200 border border-slate-100 dark:border-slate-800"
                @click.stop>
                <div
                    class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 flex items-center justify-center shadow-inner">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-tight">Edit
                                Announcement</h3>
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5"
                                x-text="'Updating ID: ' + (editingAnnouncement ? editingAnnouncement.id : '')"></p>
                        </div>
                    </div>
                    <button @click="showEditModal = false"
                        class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 bg-white dark:bg-slate-800 rounded-xl border border-slate-100 dark:border-slate-700 hover:border-slate-200 dark:hover:border-slate-600 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <form :action="`/admin/announcements/${editingAnnouncement ? editingAnnouncement.id : ''}`" method="POST"
                    class="p-6">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Title</label>
                            <input type="text" name="title" x-model="editingAnnouncement.title" required
                                class="w-full h-11 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Type</label>
                            <select name="type" x-model="editingAnnouncement.type" required
                                class="w-full h-11 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl px-4 text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all appearance-none cursor-pointer">
                                <option value="info">Info (Blue)</option>
                                <option value="warning">Warning (Yellow)</option>
                                <option value="danger">Danger (Red)</option>
                                <option value="success">Success (Green)</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-[11px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-2">Message</label>
                            <textarea name="message" x-model="editingAnnouncement.message" required rows="4"
                                class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-sm font-medium text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all resize-y"></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="showEditModal = false"
                            class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-bold uppercase tracking-wider rounded-xl transition-colors">Cancel</button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold uppercase tracking-wider rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transition-all">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection