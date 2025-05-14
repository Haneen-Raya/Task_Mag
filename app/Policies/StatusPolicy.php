<?php

namespace App\Policies;

use App\Models\Status;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class StatusPolicy
{
    use HandlesAuthorization;
    /**
     * 
     * @param \App\Models\User $user
     * @param mixed $ability
     * @return bool
     */
    public function before(User $user, $ability)
    {
        return $user->isAdmin();
    }

    /**
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     *
     * @param \App\Models\User $user
     * @param \App\Models\Status $status
     * @return bool
     */
    public function view(User $user, Status $status): bool
    {
        return false;
    }

    /**
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     *
     * @param \App\Models\User $user
     * @param \App\Models\Status $status
     * @return bool
     */
    public function update(User $user, Status $status): bool
    {
        return false;
    }

    /**
     *
     * @param \App\Models\User $user
     * @param \App\Models\Status $status
     * @return bool
     */
    public function delete(User $user, Status $status): bool
    {
        return false;
    }

    /**
     *
     * @param \App\Models\User $user
     * @param \App\Models\Status $status
     * @return bool
     */
    public function restore(User $user, Status $status): bool
    {
        return false;
    }

    /**
     *
     * @param \App\Models\User $user
     * @param \App\Models\Status $status
     * @return bool
     */
    public function forceDelete(User $user, Status $status): bool
    {
        return false;
    }

}
