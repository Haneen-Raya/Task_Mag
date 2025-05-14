<?php
// app/Services/StatusService.php
namespace App\Services;

use App\Models\Status;

class StatusService
{
    /**
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function list()
    {
        return Status::paginate(5);
    }
    /**
     *
     * @param array $data
     * @return Status
     */
    public function create(array $data): Status
    {
        return Status::create($data);
    }
    /**
     *
     * @param \App\Models\Status $status
     * @param array $data
     * @return Status
     */
    public function update(Status $status, array $data): Status
    {
        $status->update($data);
        return $status;
    }
    /**
     * 
     * @param \App\Models\Status $status
     * @return void
     */
    public function delete(Status $status): void
    {
        $status->delete();
    }
}
