<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('projects', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Project::create($request->only('name'));
        return back();
    }
}
