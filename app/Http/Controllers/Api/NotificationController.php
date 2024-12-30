<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    use PaginationTrait;

    public function index(Request $request)
    {
        $user = $request->user();

        $type = $request->type; // 'read' or 'unread'
        $query = $user->notifications();

        if ($type === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($type === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $this->paginate($query->orderBy('created_at', 'desc'));

        return Response::success(
            'Notifications retrieved successfully.',
            NotificationResource::collection($notifications),
            200,
            $this->getPagination($notifications) // Add pagination if needed
        );
    }


    public function markAsRead($id, Request $request)
    {
        $user = $request->user();

        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return Response::success(
            'Notification marked as read successfully.',
            new NotificationResource($notification)
        );
    }

    // Mark a notification as unread
    public function markAsUnread($id, Request $request)
    {
        $user = $request->user();

        $notification = $user->notifications()->findOrFail($id);
        $notification->update(['read_at' => null]);

        return Response::success(
            'Notification marked as unread successfully.',
            new NotificationResource($notification)
        );
    }

}
