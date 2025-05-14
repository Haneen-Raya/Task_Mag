<?php
// app/Services/StatusService.php
namespace App\Services;

use App\Models\Status;

class StatusService 
{
    public function list()
    {
        return Status::paginate(5);
    }

    public function create(array $data): Status
    {
        return Status::create($data);
    }

    public function update(Status $status, array $data): Status
    {
        $status->update($data);
        return $status;
    }

    public function delete(Status $status): void
    {
        $status->delete();
    }
}
