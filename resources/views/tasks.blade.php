-<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
</head>
<body class="p-5">

<h2>Task Manager</h2>

<form method="GET" class="mb-3">
    <select name="project_id" onchange="this.form.submit()" class="form-select w-25 d-inline">
        <option value="">All Projects</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}" {{ $projectId == $project->id ? 'selected' : '' }}>
                {{ $project->name }}
            </option>
        @endforeach
    </select>
</form>

<form method="POST" action="{{ route('tasks.store') }}" class="mb-3">
    @csrf
    <input type="text" name="name" placeholder="Task name" required class="form-control w-50 d-inline">
    <select name="project_id" class="form-select w-25 d-inline">
        <option value="">No Project</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}">{{ $project->name }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary">Add Task</button>
</form>

<ul id="task-list" class="list-group w-50">
    @foreach($tasks as $task)
        <li class="list-group-item" data-id="{{ $task->id }}">
            {{ $task->name }}
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="float-end">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger">x</button>
            </form>
        </li>
    @endforeach
</ul>

<br>

<a href="/projects">Go to projects</a>


<script>
$(function(){
    $('#task-list').sortable({
        update: function(event, ui) {
            let tasks = $(this).sortable('toArray', { attribute: 'data-id' });
            $.post('{{ route("tasks.reorder") }}', { _token: $('meta[name="csrf-token"]').attr('content'), tasks });
        }
    });
});
</script>

</body>
</html>
