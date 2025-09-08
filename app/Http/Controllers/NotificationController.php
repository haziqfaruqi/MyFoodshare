<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Mark as read
        $notification->markAsRead();
        
        // Get the action URL from notification data
        $data = $notification->data;
        if (isset($data['action_url'])) {
            return redirect($data['action_url']);
        }
        
        return redirect()->back();
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        
        return response()->json(['count' => $count]);
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string'
        ]);

        Auth::user()->update([
            'fcm_token' => $request->fcm_token
        ]);

        return response()->json(['success' => true]);
    }

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'push_notifications_enabled' => 'required|boolean',
            'email_notifications_enabled' => 'sometimes|boolean',
            'notification_types' => 'sometimes|array',
        ]);

        $preferences = [];
        if ($request->has('email_notifications_enabled')) {
            $preferences['email_notifications_enabled'] = $request->email_notifications_enabled;
        }
        if ($request->has('notification_types')) {
            $preferences['notification_types'] = $request->notification_types;
        }

        Auth::user()->update([
            'push_notifications_enabled' => $request->push_notifications_enabled,
            'notification_preferences' => $preferences,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return response()->json(['success' => true]);
    }
}