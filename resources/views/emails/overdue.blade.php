<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Task Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #d9534f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .progress-bar {
            display: block;
            height: 10px;
            background-color: #d9534f;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Overdue Task Notification</h2>
        <p>Dear {{ $task->user->name }},</p>
        <p>The following task has not been completed before the due date:</p>

        <table>
            <tr>
                <th>Task</th>
                <th>Due Date</th>
                <th>Progress</th>
            </tr>
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->due_date->format('d/m/Y H:i') }}</td>
                <td>
                    @php
                        $total = $task->subTasks->count();
                        $completed = $task->subTasks->where('status', 'Close')->count();
                        $progress = $total > 0 ? round(($completed / $total) * 100, 2) : 0;
                    @endphp
                    {{ $progress }}%
                    <div class="progress-bar" style="width: {{ $progress }}%;"></div>
                </td>
            </tr>
        </table>

        <h3>Subtasks</h3>
        <table>
            <tr>
                <th>#</th>
                <th>Subtask</th>
                <th>Status</th>
            </tr>
            @foreach ($task->subTasks as $index => $subTask)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $subTask->name }}</td>
                    <td>{{ $subTask->status }}</td>
                </tr>
            @endforeach
        </table>

        <p>Please ensure that the task and its subtasks are completed as soon as possible.</p>
        <p>Thank you,</p>
        <p><strong>Task Management System</strong></p>
    </div>
</body>
</html>
