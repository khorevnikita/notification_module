<?php

namespace Khonik\Notifications\Traits;

use Khonik\Notifications\Models\Notification;

trait NotificationsReceiver
{
    public function manualNotifications()
    {
        return $this->belongsToMany(Notification::class);
    }
}
