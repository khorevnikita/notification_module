<?php

use Khonik\Notifications\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware("auth")->prefix("api")->group(function () {
    Route::resource("notifications", NotificationController::class);
    Route::get("notifications/{notification}/get-users", [NotificationController::class, 'getUsers']);
    Route::post("notifications/{notification}/set-users", [NotificationController::class, 'setUsers']);
    Route::post("notifications/{notification}/send", [NotificationController::class, 'send']);
});
