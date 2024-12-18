@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Create New Task</h1>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Task Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Enter Task Title" required>
                    @error('title')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="due_date" class="form-label">Due Date & Time</label>
                    <input type="datetime-local" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" required>
                    @error('due_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <h4>Subtasks</h4>
                <div id="subtasks-container">
                    <div class="subtask-item mb-3">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="subtasks[0][name]" class="form-control" placeholder="Subtask Name" required>
                            </div>
                            <div class="col-md-4">
                                <select name="subtasks[0][status]" class="form-select" required>
                                    <option value="Open" selected>Open</option>
                                    <option value="Close">Close</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" id="add-subtask-btn" class="btn btn-secondary mb-3">Add Subtask</button>

                <button type="submit" class="btn btn-primary">Create Task</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let subtaskIndex = 1; // Counter for subtasks

        // Function to add new subtask fields
        document.getElementById('add-subtask-btn').addEventListener('click', function () {
            const container = document.getElementById('subtasks-container');
            const newSubtask = `
                <div class="subtask-item mb-3">
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" name="subtasks[${subtaskIndex}][name]" class="form-control" placeholder="Subtask Name" required>
                        </div>
                        <div class="col-md-4">
                            <select name="subtasks[${subtaskIndex}][status]" class="form-select" required>
                                <option value="Open" selected>Open</option>
                                <option value="Close">Close</option>
                            </select>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newSubtask);
            subtaskIndex++;
        });
    });
</script>
@endsection
