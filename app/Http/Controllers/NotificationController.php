<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->limit(20)->get()->map(fn($n) => [
            'id' => $n->id,
            'data' => $n->data,
            'read' => $n->read_at !== null,
            'time' => $n->created_at->diffForHumans(),
        ]);
        $unread = auth()->user()->unreadNotifications()->count();
        return response()->json(['notifications' => $notifications, 'unread' => $unread]);
    }

    public function markRead(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
