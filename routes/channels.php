<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channels for restaurant owners
Broadcast::channel('restaurant.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId && $user->role === 'donor';
});

// Private channels for pickup verification
Broadcast::channel('pickup.{verificationId}', function ($user, $verificationId) {
    $verification = \App\Models\PickupVerification::find($verificationId);
    if (!$verification) {
        return false;
    }

    // Determine donor_id from food listing
    $donorId = $verification->foodListing->restaurantProfile ? $verification->foodListing->restaurantProfile->user_id : $verification->foodListing->user_id;

    return $user->id === $donorId || $user->id === $verification->recipient_id;
});

// Private admin dashboard channel
Broadcast::channel('admin.dashboard', function ($user) {
    return $user->role === 'admin';
});

// Private channels for recipients
Broadcast::channel('recipient.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId && $user->role === 'recipient';
});