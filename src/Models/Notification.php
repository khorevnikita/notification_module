<?php

namespace Khonik\Notifications\Models;

use Khonik\Notifications\Jobs\PushNotification;
use Khonik\Notifications\Mail\EmailNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'title', 'text'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function send()
    {
        switch ($this->type) {
            case "push":
                $this->sendPush();

                break;
            case "email":
                $this->sendEmail();
                break;
            case "any":
                $this->sendPush();
                $this->sendEmail();
                break;
        }
        $this->sent_at = Carbon::now();
        $this->save();
        return $this;
    }


    protected function sendPush()
    {
        $customers = $this->users()->whereNotNull('device_token')->whereNotNull('device_type')->get();
        foreach ($customers as $customer) {
            dispatch(new PushNotification($customer, $this->title, $this->text));
        }

    }

    protected function sendEmail()
    {
        $customers = $this->users()->get();
        foreach ($customers as $customer) {
            Mail::to($customer)->queue(new EmailNotification($this->title, $this->text));
        }
    }

    public function getSentAtAttribute($v)
    {
        if (!$v) {
            return null;
        }
        return Carbon::parse($v)->format("H:i:s d.m.Y");
    }
}
