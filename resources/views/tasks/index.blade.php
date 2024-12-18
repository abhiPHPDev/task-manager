@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>My Task List</h1>

    <table id="tasks-table" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Sr No.</th>
                <th>Task Subject</th>
                <th>Created Date & Time</th>
                <th>Number of Tasks</th>
                <th>Due Date & Time</th>
                <th>Progress</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $index => $task)
            <tr class="{{ $task->id }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $task->title }}</td>
                <td>{{ $task->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $task->subTasks->count() }}</td>
                <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y H:i') }}</td>
                <td>
                    @php
                        $completed = $task->subTasks->where('status', 'Close')->count();
                        $total = $task->subTasks->count();
                        $progress = $total > 0 ? round(($completed / $total) * 100, 2) : 0;
                    @endphp
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $progress }}%
                        </div>
                    </div>
                </td>
                <td>
                    <a role="button" href="javascript:toggleSubtasks({{ $task->id }})"><i class="bi bi-plus"></i></a>
                </td>
            </tr>
            <tr id="subtasks-row-{{ $task->id }}" class="collapse">
                <td colspan="7">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Subtask Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="subtasks-table-{{ $task->id }}">
                            <!-- Subtasks will be loaded dynamically -->
                        </tbody>
                    </table>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    function toggleSubtasks(taskId) {
        const subtaskRow = document.getElementById(`subtasks-row-${taskId}`);
        const subtaskTable = document.getElementById(`subtasks-table-${taskId}`);

        if (subtaskRow.classList.contains('show')) {
            // Collapse the row
            subtaskRow.classList.remove('show');
        } else {
            // Expand the row
            subtaskRow.classList.add('show');

            // If subtasks are already loaded, don't fetch again
            if (subtaskTable.dataset.loaded === "true") {
                $("tr."+taskId).find('a').find('i').removeClass('bi-plus').addClass('bi-dash');
                    $("tr."+taskId).find('a').attr('href',"javascript:hidesubtasks("+taskId+")");
                return;
            }

            // Fetch subtasks via AJAX
            fetch(`/subtasks/data/${taskId}`)
                .then(response => response.json())
                .then(data => {
                    subtaskTable.innerHTML = ''; // Clear previous content
                    data.forEach((subTask, index) => {
                        const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${subTask.name}</td>
                                <td>${subTask.status}</td>
                            </tr>
                        `;
                        subtaskTable.insertAdjacentHTML('beforeend', row);
                    });
                    $("tr."+taskId).find('a').find('i').removeClass('bi-plus').addClass('bi-dash');
                    $("tr."+taskId).find('a').attr('href',"javascript:hidesubtasks("+taskId+")");
                    // Mark this table as loaded
                    subtaskTable.dataset.loaded = "true";
                })
                .catch(error => {
                    console.error('Error fetching subtasks:', error);
                });
        }
    }

    function hidesubtasks(taskId){
        const subtaskRow = $(`#subtasks-row-${taskId}`);
        const subtaskTable = $(`#subtasks-table-${taskId}`);
        subtaskRow.removeClass('show');
        $("tr."+taskId).find('a').find('i').removeClass('bi-dash').addClass('bi-plus');
        $("tr."+taskId).find('a').attr('href',"javascript:toggleSubtasks("+taskId+")");
    }
</script>
@endsection
