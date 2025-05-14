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

    public function create(User $user, array $data): Task
    {
        $User = $user->isAdmin() && isset($data['user_id'])
            ? $data['user_id']
            : $user->id;
        $data['user_id'] = $User;
        $data['status_id'] = 2;

        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {

        $UserId = Auth::user()->isAdmin() && isset($data['user_id'])
            ? $data['user_id']
            : $task->user_id;
        $data['user_id'] = $UserId;
        $task->update($data);
        return $task;
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
