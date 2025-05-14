<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Status\StoreStatusRequest;
use App\Http\Requests\Status\UpdateStatusRequest;
use App\Models\Status;
use App\Models\Task;
use App\Services\StatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StatusController extends Controller
{

    protected StatusService $status;
    /**
     *
     * @param \App\Services\StatusService $status
     */
    public function __construct(StatusService $status)
    {
        $this->status = $status;
    }
    /**
     *
     * @return string[]
     */
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }
    /**
     *
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        Gate::authorize('viewAny', Status::class);
        return response()->json($this->status->list());
    }
    /**
     *
     *
     * @param \App\Models\Status $status
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function show(Status $status)
    {
        Gate::authorize('view', $status);
        return response()->json($status->load('tasks'));
    }

    /**
     *
     * @param \App\Http\Requests\Status\StoreStatusRequest $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store(StoreStatusRequest $request)
    {
        Gate::authorize('create', Status::class);
        $status = $this->status->create($request->validated());
        return response()->json($status, 201);
    }

    /**
     *
     * @param \App\Http\Requests\Status\UpdateStatusRequest $request
     * @param \App\Models\Status $status
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(UpdateStatusRequest $request, Status $status)
    {

        Gate::authorize('update', $status);
        $status = $this->status->update($status, $request->validated());
        return response()->json($status);
    }

    /**
     * 
     * @param \App\Models\Status $status
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function destroy(Status $status)
    {
        Gate::authorize('delete', $status);
        $this->status->delete($status);
        return response()->json(['message' => 'Deleted Successfuly' ]);
    }
}
