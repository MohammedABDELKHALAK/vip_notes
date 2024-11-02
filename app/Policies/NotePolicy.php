<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class NotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Note $note)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        // Allow the user to create a note if they are authenticated
        return $user instanceof User;
        
    }

    public function show(User $user, Note $note)
    {
        // Allow the user to create a note if they are authenticated
        return $user->id === $note->user_id;
        
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }
}
