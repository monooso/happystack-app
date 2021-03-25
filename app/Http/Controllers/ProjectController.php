<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a list of team projects
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $team = $request->user()->currentTeam;

        return response()->view('projects.index', [
            'projects' => $team->projects()->orderBy('name', 'asc')->get(),
        ]);
    }

    /**
     * Display the "create project" form
     *
     * @return Response
     */
    public function create(): Response
    {
        return response()->view('projects.create', ['project' => new Project()]);
    }

    /**
     * Display the "edit project" form
     *
     * @param Project $project
     *
     * @return Response
     */
    public function edit(Project $project): Response
    {
        return response()->view('projects.edit', ['project' => $project]);
    }
}
