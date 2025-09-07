<?php

namespace App\Policies;

use App\Models\FoodListing;
use App\Models\User;

class FoodListingPolicy
{
    public function view(User $user, FoodListing $foodListing): bool
    {
        return $user->id === $foodListing->user_id || $user->isAdmin();
    }

    public function update(User $user, FoodListing $foodListing): bool
    {
        return $user->id === $foodListing->user_id;
    }

    public function delete(User $user, FoodListing $foodListing): bool
    {
        return $user->id === $foodListing->user_id;
    }
}