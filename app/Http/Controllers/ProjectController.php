<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Events\ProjectStageUpdated;
use App\Mail\ProjectStatusUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $stages = Project::STAGES;
        $projects = Project::with('prospect')->latest()->paginate(10);

        return view('admin.projects.index', compact('projects', 'stages'));
    }

    public function show(Project $project)
    {
        $project->load(['prospect', 'invoice']);
        return view('admin.projects.show', compact('project'));
    }

    public function updateStage(Request $request, Project $project)
    {
        $request->validate([
            'stage' => 'required|string|in:' . implode(',', array_keys(Project::STAGES))
        ]);

        $project->update([
            'current_stage' => $request->stage
        ]);

        event(new ProjectStageUpdated($project, $request->stage));

        return response()->json(['success' => true, 'message' => 'Project stage updated successfully and author notified.']);
    }
}
