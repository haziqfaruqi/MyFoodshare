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
    return $verification && ($user->id === $verification->donor_id || $user->id === $verification->recipient_id);
});

// Private admin dashboard channel
Broadcast::channel('admin.dashboard', function ($user) {
    return $user->role === 'admin';
});

// Private channels for recipients
Broadcast::channel('recipient.{userId}', function ($user, $userId) {
    return $user->id === (int) $userId && $user->role === 'recipient';
});