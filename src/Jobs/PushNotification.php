<?php

namespace Khonik\Notifications\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $title;
    public $text;
    public $action;
    public $data;

    /**
     * @param User $user
     * @param string $title
     * @param string $text
     * @param string $action
     * @param array $data
     */
    public function __construct(User $user, string $title, string $text, string $action = '', array $data = [])
    {
        $this->user = $user;
        $this->title = $title;
        $this->text = $text;
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->user;
        if ($user->device_type === "android") {
            # android body
            $json_data = [
                "data" => [
                    "action" => $this->action,
                    "title" => $this->title,
                    "body" => $this->text,
                    "sound" => "default",
                ],
                "to" => $user->device_token
            ];
            if ($this->data) {
                $json_data['data']['data'] = $this->data;
            }
        } else {
            # ios body
            $json_data = [
                "to" => $user->device_token,
                "notification" => [
                    "body" => $this->text,
                    "title" => $this->title,
                    "action" => $this->action,
                    "sound" => "default"
                ],
            ];
            if ($this->data) {
                $json_data['data'] = $this->data;
            }
        }
        $data = json_encode($json_data);

        $url = 'https://fcm.googleapis.com/fcm/send';
        $server_key = config("services.firebase.key");
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $server_key
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Oops! FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
    }
}
