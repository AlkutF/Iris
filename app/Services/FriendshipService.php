<?php
namespace App\Services;

use App\Models\Friendship;
use App\Models\FriendRequest;

class FriendshipService
{
    public function getFriendship($userId, $profileId)
    {
        return Friendship::where(function ($query) use ($userId, $profileId) {
            $query->where('user_id', $userId)->where('friend_id', $profileId);
        })->orWhere(function ($query) use ($userId, $profileId) {
            $query->where('user_id', $profileId)->where('friend_id', $userId);
        })->first();
    }

    public function getFriendRequest($userId, $profileId)
    {
        return FriendRequest::where(function ($query) use ($userId, $profileId) {
            $query->where('sender_id', $userId)->where('receiver_id', $profileId);
        })->orWhere(function ($query) use ($userId, $profileId) {
            $query->where('sender_id', $profileId)->where('receiver_id', $userId);
        })->first();
    }
}
