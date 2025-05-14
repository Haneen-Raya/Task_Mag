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

    public function __construct(StatusService $status)
    {
        $this->status = $status;
    }
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Gate::authorize('viewAny', Status::class);
        return response()->json($this->status->list());
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        Gate::authorize('view', $status);
        return response()->json($status->load('tasks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatusRequest $request)
    {
        Gate::authorize('create', Status::class);
        $status = $this->status->create($request->validated());
        return response()->json($status, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStatusRequest $request, Status $status)
    {

        Gate::authorize('update', $status);
        $status = $this->status->update($status, $request->validated());
        return response()->json($status);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        Gate::authorize('delete', $status);
        $this->status->delete($status);
        return response()->json(['message' => 'Deleted Successfuly' ]);
    }
}
