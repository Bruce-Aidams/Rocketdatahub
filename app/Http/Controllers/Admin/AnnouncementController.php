<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,danger,success',
        ]);

        $announcement = Announcement::create($validated);

        if ($request->has('activate')) {
            Announcement::where('id', '!=', $announcement->id)->update(['is_active' => false]);
            $announcement->update(['is_active' => true]);
            return redirect()->back()->with('success', 'Announcement created and activated.');
        }

        return redirect()->back()->with('success', 'Announcement created successfully.');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,danger,success',
        ]);

        $announcement->update($validated);

        return redirect()->back()->with('success', 'Announcement updated successfully.');
    }

    public function toggleActive(Announcement $announcement)
    {
        if (!$announcement->is_active) {
            Announcement::where('id', '!=', $announcement->id)->update(['is_active' => false]);
            $announcement->update(['is_active' => true]);
            $msg = 'Announcement activated.';
        } else {
            $announcement->update(['is_active' => false]);
            $msg = 'Announcement deactivated.';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->back()->with('success', 'Announcement deleted.');
    }
}
