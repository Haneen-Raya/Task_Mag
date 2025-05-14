<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use App\Models\Status;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class TaskPolicy
{

    /**
     * Summary of before
     * @param \App\Models\User $user
     * @param mixed $ability
     * @return bool
     */
    public function before(User $user, $ability): bool|null
    {
        return $user->isAdmin() ? true : null;
    }
    /**
     *
     * @param \App\Models\User $user
     * @return Response
     */
    public function viewAny(User $user)
    {
        return Auth::user()->id === $user->id
            ? Response::allow()
            : Response::deny('You are not authorized to view these tasks.');
        ;
    }

    /**
     *
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return Response
     */
    public function view(User $user, Task $task)
    {
        return $task->user_id === $user->id
            ? Response::allow()
            : Response::deny('You are not allowed to view this task.');
    }

    /**
     *
     * @param \App\Models\User $user
     * @return Response
     */
    public function create(User $user)
    {
        if (!Status::query()->exists()) {
            return Response::deny('Cannot create a task without any statuses available.');
        }
        return Auth::user()->id === $user->id
            ? Response::allow()
            : Response::deny('You are not allowed to create tasks for other users.');
    }

    /**
     *
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return Response
     */
    public function update(User $user, Task $task)
    {
        return $task->user_id === $user->id
            ? Response::allow()
            : Response::deny('You are not allowed to update this task.');
    }

    /**
     * 
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     * @return Response
     */
    public function delete(User $user, Task $task)
    {
        return $task->user_id === $user->id
            ? Response::allow()
            : Response::deny('You are not allowed to delete this task.');
    }



}
