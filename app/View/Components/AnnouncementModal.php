<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnnouncementModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        $announcement = \App\Models\Announcement::where('is_active', true)->first();
        return view('components.announcement-modal', compact('announcement'));
    }
}
