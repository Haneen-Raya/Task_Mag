<?php

// app/Services/TaskService.php
namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    /**
     *
     * @param \App\Models\User $user
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function list(User $user, array $filters = [])
    {
        $query = Task::with('status');
        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }
        if (isset($filters['status_id'])) {
            $query->where('status_id', $filters['status_id']);
        }
        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        return $query->paginate(3);
    }
    /**
     *
     * @param \App\Models\User $user
     * @param array $data
     * @return Task
     */
    public function create(User $user, array $data): Task
    {
        $User = $user->isAdmin() && isset($data['user_id'])
            ? $data['user_id']
            : $user->id;
        $data['user_id'] = $User;
        $data['status_id'] = 2;

        return Task::create($data);
    }
    /**
     *
     * @param \App\Models\Task $task
     * @param array $data
     * @return Task
     */
    public function update(Task $task, array $data): Task
    {

        $UserId = Auth::user()->isAdmin() && isset($data['user_id'])
            ? $data['user_id']
            : $task->user_id;
        $data['user_id'] = $UserId;
        $task->update($data);
        return $task;
    }
    /**
     * 
     * @param \App\Models\Task $task
     * @return void
     */
    public function delete(Task $task): void
    {
        $task->delete();
    }
}
