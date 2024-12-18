<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    use HasFactory;
    protected $fillable = ['task_id', 'name', 'status'];

    /**
     * Define a belongs-to relationship with the Task model.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
