<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('subTasks')->orderBy('id','DESC')->get();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'subtasks.*.name' => 'required|string|max:255',
            'subtasks.*.status' => 'required|in:Open,Close',
        ]);

        $task = Task::create([
            'subject' => $request->title,
            'due_date' => $request->due_date,
        ]);

        foreach ($request->subtasks as $subtask) {
            $task->subTasks()->create([
                'name' => $subtask['name'],
                'status' => $subtask['status'],
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    public function addSubtask(Request $request)
    {
        SubTask::create($request->only(['task_id', 'name', 'status']));
        return redirect()->route('tasks.index');
    }

    public function fetchSubTaskData(Task $task)
    {
        return response()->json($task->subTasks);
    }
}
