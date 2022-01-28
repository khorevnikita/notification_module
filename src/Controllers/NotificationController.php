<?php

namespace Khonik\Notifications\Controllers;

use App\Http\Controllers\Controller;
use Khonik\Notifications\Requests\NotificationCreateRequest;
use Khonik\Notifications\Requests\SetCustomersRequest;
use App\Models\User;
use Khonik\Notifications\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request):JsonResponse
    {
        $notifications = Notification::withCount("users");

        if ($request->search) {
            $notifications = $notifications->where("title", "like", "%$request->search%");
        }

        if ($request->sortBy) {
            $notifications = $notifications->orderBy($request->sortBy, $request->sortDesc ? "desc" : "asc");
        }

        if ($request->type && in_array($request->type,['push','email'])) {
            $notifications = $notifications->where("type", $request->type);
        }

        $total = $notifications->count();
        $take = 30;
        $skip = ($request->page - 1) * $take;
        $notifications = $notifications->skip($skip)->take($take)->get();
        return response()->json([
            'status' => "success",
            'notifications' => $notifications,
            'total' => $total,
            'pages' => ceil($total / $take)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param NotificationCreateRequest $request
     * @return JsonResponse
     */
    public function store(NotificationCreateRequest $request):JsonResponse
    {
        $notification = new Notification($request->all());
        $notification->save();
        return response()->json([
            'status' => "success",
            'notification' => $notification,
        ]);
    }

    /**
     * @param Notification $notification
     * @return void
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * @param Notification $notification
     * @return void
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * @param NotificationCreateRequest $request
     * @param Notification $notification
     * @return JsonResponse
     */
    public function update(NotificationCreateRequest $request, $notification_id):JsonResponse
    {
        $notification = Notification::findOrFail($notification_id);
        $notification->fill($request->all());
        $notification->save();
        return response()->json([
            'status' => "success",
            'notification' => $notification,
        ]);
    }

    /**
     * @param Notification $notification
     * @return JsonResponse
     */
    public function destroy($notification_id):JsonResponse
    {
        $notification = Notification::findOrFail($notification_id);
        $notification->delete();
        return response()->json([
            'status' => "success",
        ]);
    }

    public function getUsers($notification_id): JsonResponse
    {
        $notification = Notification::findOrFail($notification_id);
        return response()->json([
            'status' => "success",
            'users' => $notification->users()->get()
        ]);
    }

    /**
     * @param int $notification_id
     * @param SetCustomersRequest $request
     * @return JsonResponse
     */
    public function setUsers($notification_id, SetCustomersRequest $request): JsonResponse
    {
        $notification = Notification::findOrFail($notification_id);
        $notification->users()->sync($request->users);
        return response()->json([
            'status' => "success",
        ]);
    }

    /**
     * @param int $notification_id
     * @return JsonResponse
     */
    public function send($notification_id): JsonResponse
    {
        $notification = Notification::findOrFail($notification_id);
        $notification->send();

        return response()->json([
            'status' => "success",
            'notification' => $notification
        ]);
    }
}
