<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotifyOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-overdue-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $overdueTasks = Task::where('due_date', '<', now())
        ->whereDoesntHave('subTasks', fn ($query) => $query->where('status', 'Open'))
        ->get();

        foreach ($overdueTasks as $task) {
            Mail::to($task->user->email)->send(new OverdueTaskNotification($task));
        }

    }
}
