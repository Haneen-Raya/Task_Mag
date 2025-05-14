<?php

namespace App\Models;

use App\Models\User;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use SoftDeletes ;
    protected $fillable = [
        'user_id',
        'status_id',
        'title',
        'description',
        'due_date'
    ];

    protected $casts = [
        'due_date'  =>  'date'
    ];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }
}
