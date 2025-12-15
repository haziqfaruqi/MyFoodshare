<?php

namespace App\Policies;

use App\Models\FoodListing;
use App\Models\User;

class FoodListingPolicy
{
    public function view(User $user, FoodListing $foodListing): bool
    {
        return $user->id === $foodListing->created_by || $user->isAdmin();
    }

    public function update(User $user, FoodListing $foodListing): bool
    {
        return $user->id === $foodListing->created_by;
    }

    public function delete(User $user, FoodListing $foodListing): bool
    {
        return $user->id === $foodListing->created_by;
    }
}