<h2>Projects</h2>
<form method="POST" action="{{ route('projects.store') }}">
    @csrf
    <input type="text" name="name" placeholder="Project name" required>
    <button>Add</button>
</form>

<ul>
@foreach($projects as $project)
    <li>{{ $project->name }}</li>
@endforeach
</ul>

<a href="/tasks">Go to tasks</a>