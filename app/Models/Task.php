<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject', 'due_date'
    ];
    public function subTasks()
    {
        return $this->hasMany(SubTask::class);
    }

    /**
     * Calculate the progress of the task based on subtasks.
     * @return float Progress percentage.
     */
    public function calculateProgress()
    {
        $totalSubTasks = $this->subTasks()->count();
        if ($totalSubTasks === 0) {
            return 0;
        }

        $completedSubTasks = $this->subTasks()->where('status', 'Close')->count();
        return ($completedSubTasks / $totalSubTasks) * 100;
    }
}
