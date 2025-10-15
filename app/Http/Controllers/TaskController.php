<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::all();
        $projectId = $request->get('project_id');

        $tasks = Task::when($projectId, function ($q) use ($projectId) {
            $q->where('project_id', $projectId);
        })->orderBy('priority')->get();

        return view('tasks', compact('tasks', 'projects', 'projectId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $maxPriority = Task::max('priority') + 1;
        $validated['priority'] = $maxPriority;

        Task::create($validated);
        return back();
    }

    public function update(Request $request, Task $task)
    {
         $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id'
        ]);

        $task->update($validated);

        // If you'd like to redirect back with a flash message:
        return back()->with('success', 'Task updated.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back();
    }

    public function reorder(Request $request)
    {
        $tasks = $request->get('tasks'); // [id1, id2, id3...]

        foreach ($tasks as $index => $id) {
            Task::where('id', $id)->update(['priority' => $index + 1]);
        }

        return response()->json(['status' => 'success']);
    }
}
