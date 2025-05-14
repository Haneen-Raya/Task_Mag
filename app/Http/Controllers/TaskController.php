<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;

class TaskController extends Controller
{
  /**
   *
   * @var TaskService
   */
  protected TaskService $task;

    /**
     *
     * @param \App\Services\TaskService $task
     */
    public function __construct(TaskService $task)
    {
        $this->task = $task;
    }
    /**
     *
     * @return string[]
     */
    public static function middleware(): array
    {
        return [
            'auth',// Laravel 11+ uses 'auth:sanctum' or 'auth:web' typically. 'auth' is generic.
        ];
    }
    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Task::class);
        $tasks = $this->task->list($request->user(), $request->only(['status_id','priority']));
        return TaskResource::collection($tasks);
    }

    /**
     *
     * @param \App\Http\Requests\Task\StoreTaskRequest $request
     * @return JsonResponse|mixed
     */
    public function store(StoreTaskRequest $request)
    {
        Gate::authorize('create', Task::class);
        $task = $this->task->create($request->user(), $request->validated());
         return (new TaskResource($task->load('status')))
                ->response()
                ->setStatusCode(201);
    }

    /**
     *
     * @param \App\Models\Task $task
     * @return JsonResponse|mixed
     */
    public function show(Task $task)
    {
        Gate::authorize('view', $task);
          return new TaskResource($task->load(['user','status']));
    }

    /**
     *
     * @param \App\Http\Requests\Task\UpdateTaskRequest $request
     * @param \App\Models\Task $task
     * @return JsonResponse|mixed
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {

        Gate::authorize('update', $task);
        $updatedTask = $this->task->update($task, $request->validated());
         return new TaskResource($updatedTask->load('status'));
    }

    /**
     *
     * @param \App\Models\Task $task
     * @return JsonResponse|mixed
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);
        $this->task->delete($task);
       return response()->json(['message'=>'Deleted successfully']);
}}
