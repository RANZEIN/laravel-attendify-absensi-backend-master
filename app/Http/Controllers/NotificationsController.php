?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;



class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        // Mark as read if not already
        if (!$notification->read_at) {
            $notification->markAsRead();
        }

        // Redirect based on notification type
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return redirect()->route('notifications.index');
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read');
    }
}
