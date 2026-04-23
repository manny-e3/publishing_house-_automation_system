<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        $stages = Project::STAGES;
        $projects = $this->projectService->getProjects();

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

        $this->projectService->updateStage($project, $request->stage);

        return response()->json(['success' => true, 'message' => 'Project stage updated successfully and author notified.']);
    }
}
