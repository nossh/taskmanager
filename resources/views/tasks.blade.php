<!DOCTYPE html>
<html>
<head>
    <title>Task Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-5">

<div class="container">
    <h2 class="mb-4">Task Manager</h2>

    {{-- filter --}}
    <form method="GET" class="mb-3">
        <select name="project_id" onchange="this.form.submit()" class="form-select w-25 d-inline">
            <option value="">All Projects</option>
            @foreach($projects as $project)
                <option value="{{ $project->id }}" {{ (int)$projectId === $project->id ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- create --}}
    <form method="POST" action="{{ route('tasks.store') }}" class="mb-3 row g-2 align-items-center">
        @csrf
        <div class="col-auto w-50">
            <input type="text" name="name" placeholder="Task name" required class="form-control">
        </div>

        <div class="col-auto w-25">
            <select name="project_id" class="form-select">
                <option value="">No Project</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-auto">
            <button class="btn btn-primary">Add Task</button>
        </div>
    </form>

    {{-- tasks --}}
    <ul id="task-list" class="list-group w-75">
        @foreach($tasks as $task)
            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{ $task->id }}">
                <div>
                    <strong>#{{ $task->priority }}</strong>
                    &nbsp; {{ $task->name }}
                    @if($task->project)
                        <span class="badge bg-secondary ms-2">{{ $task->project->name }}</span>
                    @endif
                </div>

                <div>
                    {{-- Edit button triggers modal --}}
                    <button class="btn btn-sm btn-outline-secondary me-2 btn-edit"
                        data-id="{{ $task->id }}"
                        data-name="{{ htmlspecialchars($task->name, ENT_QUOTES, 'UTF-8') }}"
                        data-project-id="{{ $task->project_id ?? '' }}">
                        Edit
                    </button>

                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this task?')">Delete</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
</div>

{{-- Edit Modal (single reusable modal) --}}
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editTaskForm" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="edit-task-name" class="form-label">Name</label>
                    <input id="edit-task-name" name="name" type="text" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="edit-task-project" class="form-label">Project</label>
                    <select id="edit-task-project" name="project_id" class="form-select">
                        <option value="">No Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </form>
  </div>
</div>

<script>
$(function(){
    // sortable + send order to server
    $('#task-list').sortable({
        update: function(event, ui) {
            let tasks = $(this).sortable('toArray', { attribute: 'data-id' });
            $.post('{{ route("tasks.reorder") }}',
                { _token: $('meta[name="csrf-token"]').attr('content'), tasks }
            );
        }
    });

    // Edit button click: populate and open modal
    const editModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
    $(document).on('click', '.btn-edit', function(){
        const id = $(this).data('id');
        const name = $(this).data('name');
        const projectId = $(this).data('project-id');

        $('#edit-task-name').val(name);
        $('#edit-task-project').val(projectId);

        // set form action to tasks.update route
        $('#editTaskForm').attr('action', '/tasks/' + id);

        editModal.show();
    });

   
});
</script>

</body>
</html>

