<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller {
    
    public function deleteNotification($id) {
        try {

            $notification = Notification::findOrFail($id);
    
            if ($notification->url) {
                $url = $notification->url;
                $notification->delete();
                return redirect($url);
            }
    
            $notification->delete();
            return redirect()->back();
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('info', 'Notificação não encontrada.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ocorreu um erro ao tentar visualizar a notificação.');
        }
    }    

}
