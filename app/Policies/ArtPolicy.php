<?php

namespace App\Policies;

use App\Models\Art;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class ArtPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Art $art): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Art $art): bool
    {
        return $user->id == $art->user_id && $user->role == 'artist';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Art $art): bool
    {
        return $user->id == $art->user_id && $user->role == 'artist';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Art $art): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Art $art): bool
    {
        //
    }
}
