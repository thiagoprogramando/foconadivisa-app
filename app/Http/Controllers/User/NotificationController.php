<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller {
    
    public function deleteNotification($id) {

        $notification = Notification::find($id);
        if($notification && $notification->delete()) {
            return redirect()->back();
        }

        return redirect()->back();
    }

}
