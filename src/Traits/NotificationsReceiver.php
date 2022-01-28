<?php

namespace Khonik\Notifications\Traits;

use Khonik\Notifications\Models\Notification;

trait NotificationsReceiver
{
    public function scopeReceiverOfNotification($query,$notification_id){
        if(!$notification_id){
            return $query;
        }
        return $query->whereHas("manualNotifications", function ($q) use ($notification_id) {
            $q->where("notifications.id", $notification_id);
        });
    }

    public function manualNotifications()
    {
        return $this->belongsToMany(Notification::class);
    }
}
