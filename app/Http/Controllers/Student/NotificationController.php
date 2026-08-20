<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        return view('student.notifications', [
            'notifications' => auth()->user()->notificationsInternes()->latest()->paginate(15),
        ]);
    }

    public function marquerLue(Notification $notification): RedirectResponse
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->update(['lu' => true]);

        return back();
    }

    public function toutMarquerLu(): RedirectResponse
    {
        auth()->user()->notificationsInternes()->where('lu', false)->update(['lu' => true]);

        return back()->with('status', 'Toutes vos notifications ont été marquées comme lues.');
    }
}
